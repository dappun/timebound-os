<?php namespace TB\Timesheet\Http\Controllers;

use TB\Core\Http\Controllers\BaseController; 
use TB\Timesheet\Http\Requests\CreateTimesheetRequest;
use TB\Timesheet\Http\Requests\UpdateTimesheetRequest;
use TB\Timesheet\Http\Requests\TimerRequest;
use TB\Timesheet\Repositories\TimesheetRepository;
use TB\Project\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Flash;
use Response;

class ApiTimesheetController extends BaseController 
{
	private $timesheetRepository;

    public function __construct(TimesheetRepository $timesheetRepo, ProjectRepository $projectRepo)
    {
        $this->timesheetRepository = $timesheetRepo;
        $this->projectRepository = $projectRepo;

        $this->middleware('auth');
        $this->middleware('timezone');

        env('APP_DEBUG', false);
    }

    /**
     * Display a listing of the Timesheet.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $conditions = [
            'user_id' => \Auth::id(),
            'start' => date('Y-m-d', strtotime('Last year')),
            'end' => date('Y-m-d', strtotime('Sunday this week')),
        ];

        $page = isset($input['page']) ? (int)$input['page'] : 0;
        $result = $this->timesheetRepository->history($conditions, ($page + 1), 20);
        
        $jsonResult = [];
        foreach ($result as $key => $item) {
            $end = date('Y-m-d H:i:s');
            if ($item->end) {
                $end = $item->end;
            }

            $jsonResult[] = [
                'id' => $item->id,
                'description' => $item->description,
                'ticket' => $item->ticket,
                'project' => [
                    'id' => $item->project_id,
                    'name' => $item->project_title,
                ],
                'user'  => [
                    'id' => $item->user_id,
                    'name' => $item->user_name,
                ],
                'date' => [
                    'start' => $item->start,
                    'end' => $item->end,
                    'duration' => (float)$item->getAttributes()['duration'],
                    'duration_formatted' => $item->duration,
                    'duration_raw' => computeDuration($item->start, $end, 'array')
                ],
            ];
        }

        $data = [
            'currentPage' => $result->currentPage(),
            'lastPage' => $result->lastPage(),
            'total' => $result->total(),
            'limit' => $result->perPage(),
            'data' => $jsonResult
        ];

        return response()->json($data);
    }

    public function ongoing()
    {
        $timesheet = $this->timesheetRepository->findOngoing(\Auth::id());
        $data = [];

        if ($timesheet) {
            $data = [
                'id' => $timesheet->id,
                'description' => $timesheet->description,
                'project_id' => $timesheet->project_id,
                'ticket' => $timesheet->ticket,
                'duration' => (float)$timesheet->getAttributes()['duration'],
                'duration_formatted' => $timesheet->duration,
                'duration_raw' => $timesheet->duration_raw,
                'start' => $timesheet->start
            ];
        }
            
        return response()->json($data);
    }

    public function stopwatch(TimerRequest $request) 
    {
        $input = $request->all();
        $input['user_id'] = \Auth::id();
        $item = $this->timesheetRepository->stopwatch($input);
        
        $end = date('Y-m-d H:i:s');
        if ($item->end) {
            $end = $item->end;
        }

        $data = [
            'id' => $item->id,
            'description' => $item->description,
            'project' => [
                'id' => $item->project_id,
                'name' => '',
            ],
            'user'  => [
                'id' => $item->user_id,
                'name' => '',
            ],
            'date' => [
                'start' => $item->start,
                'end' => $item->end,
                'duration' => (float)@$item->getAttributes()['duration'],
                'duration_formatted' => $item->duration,
                'duration_raw' => computeDuration($item->start, $end, 'array')
            ],
        ];

        return response()->json($data);
    }

    
}