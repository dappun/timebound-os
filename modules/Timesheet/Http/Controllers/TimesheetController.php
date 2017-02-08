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

class TimesheetController extends BaseController 
{
	private $timesheetRepository;
    protected $user;

    public function __construct(TimesheetRepository $timesheetRepo, ProjectRepository $projectRepo)
    {
        parent::__construct();
        $this->timesheetRepository = $timesheetRepo;
        $this->projectRepository = $projectRepo;

        $this->middleware('auth');
        $this->middleware('timezone');
    }

    /**
     * Display a listing of the Timesheet.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $projects = $this->projectRepository->options($this->user['id'], 'select2', true);
        \View::share('projectOptions', $projects);
        
        return view('timesheet::index');
    }

    /**
     * Show the form for creating a new Timesheet.
     *
     * @return Response
     */
    public function create()
    {
        $projects = $this->projectRepository->options($this->user['id'], 'array', false);

        return view('timesheet::create')
            ->with('projects', $projects);
    }

    /**
     * Store a newly created Timesheet in storage.
     *
     * @param CreateTimesheetRequest $request
     *
     * @return Response
     */
    public function store(CreateTimesheetRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = $this->user['id'];
        $timesheet = $this->timesheetRepository->create($input);

        if ($request->ajax()){
            return json_encode($timesheet);
        } else {
            Flash::success('Time Entry saved successfully.');

            return redirect(route('timer.create'));
        }
    }

    /**
     * Show the form for editing the specified Timesheet.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $timesheet = $this->timesheetRepository->findMine($id, $this->user['id']);
        $timesheet->start = $timesheet->startConverted();
        $timesheet->end = $timesheet->endConverted();

        if (empty($timesheet)) {
            Flash::error('Time Entry not found');
            return redirect(route('timer.index'));
        }

        $projects = $this->projectRepository->options($this->user['id'], 'array', false);

        return view('timesheet::edit')
            ->with('timesheet', $timesheet)
            ->with('projects', $projects);
    }

    /**
     * Update the specified Timesheet in storage.
     *
     * @param  int              $id
     * @param UpdateTimesheetRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimesheetRequest $request)
    {
        $timesheet = $this->timesheetRepository->findMine($id, $this->user['id']);

        if (empty($timesheet)) {
            if ($request->ajax()){
                return json_encode(array('error' => 'empty'));
            } else {
                Flash::error('Time Entry not found');
                return redirect(route('timer.index'));
            }
        }

        $input       = $request->all();
        $input['id'] = $id;
        $timesheet   = $this->timesheetRepository->update($input);

        if ($request->ajax()){
            return json_encode($timesheet);
        } else {
            Flash::success('Time Entry updated successfully.');

            return redirect(route('timer.index'));
        }
    }

    /**
     * Remove the specified Timesheet from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $timesheet = $this->timesheetRepository->findMine($id, $this->user['id']);

        if (empty($timesheet)) {
            Flash::error('Time Entry not found');
            return redirect(route('timer.index'));
        }

        $timesheet->delete();

        Flash::success('Time Entry deleted successfully.');
        return redirect(route('timer.index'));
    }

    public function stopwatch(TimerRequest $request) 
    {
        $input = $request->all();
        $input['user_id'] = $this->user['id'];
        $result = $this->timesheetRepository->stopwatch($input);

        if ($request->ajax()){
            return json_encode($result);
        } else {
            Flash::success('Time Entry saved successfully.');
            return redirect(route('timer.index'));
        }
    }

    public function copy($id)
    {
        $timesheet = $this->timesheetRepository->findMine($id, $this->user['id']);

        if (empty($timesheet)) {
            Flash::error('Time Entry not found');
            return redirect(route('timer.index'));
        }

        return view('timesheet::copy_confirm')->with('timesheet', $timesheet);
    }

    public function copy_post($id)
    {
        $timesheet = $this->timesheetRepository->findMine($id, $this->user['id']);

        if (empty($timesheet)) {
            Flash::error('Time Entry not found');
            return redirect(route('timer.index', $timesheet->id));
        }

        $this->timesheetRepository->copy($id);
        return redirect(route('timer.index'));
    }
}