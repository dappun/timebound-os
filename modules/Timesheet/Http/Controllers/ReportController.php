<?php namespace TB\Timesheet\Http\Controllers;

use TB\Core\Http\Controllers\BaseController; 
use TB\Timesheet\Http\Requests\CreateTimesheetRequest;
use TB\Timesheet\Http\Requests\UpdateTimesheetRequest;
use TB\Timesheet\Http\Requests\TimerRequest;
use TB\Timesheet\Repositories\TimesheetRepository;
use TB\Project\Repositories\ProjectRepository;
use TB\User\Repositories\UserRepository;
use TB\Client\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Flash;
use Response;
use Carbon\Carbon;

class ReportController extends BaseController
{
    private $timesheetRepository;

    public function __construct(TimesheetRepository $timesheetRepo, 
        ProjectRepository $projectRepo,
        UserRepository $userRepo,
        ClientRepository $clientRepo
    ){
        $this->timesheetRepository = $timesheetRepo;
        $this->projectRepository = $projectRepo;
        $this->userRepository = $userRepo;
        $this->clientRepository = $clientRepo;

        $this->middleware('auth');
        
        if (\Auth::check()) {
            $this->user = [
                'id' => \Auth::id(),
                'name' => \Auth::user()->name
            ];
        }   
    }

    /**
     * Display a listing of the Timesheet.
     *
     * @param Request $request
     * @return Response
     */
    public function detailed(Request $request, $type = 'list')
    {
        $this->projects = $this->projectRepository->options($this->user['id'], 'array', 'All projects');
        $this->users = $this->userRepository->options($this->user['id'], 'array', 'All team members');
        $this->clients = $this->clientRepository->options($this->user['id'], 'array', 'All client');

        $url = \App\SaveReport::oldest('created_at')->where('ac_user_id','=', \Auth::user()->id)->get();
        // dd ($url);

        $method = 'report' . ucfirst($type);
        $urlQuery = $request->query();
        
        if ($urlQuery) {
            $toSave = $urlQuery;
            unset($toSave['page']);
            $request->session()->put('last_query_report', $toSave);
        } else {
            if ($storedQuery = $request->session()->get('last_query_report')) {
                $urlQuery = $storedQuery;
                $request->replace($storedQuery);
            }
        }

        $input = $request->all();

        \View::share('projects', $this->projects);
        \View::share('users', $this->users);
        \View::share('clients', $this->clients);
        \View::share('request', $request);
        \View::share('urlQuery', $urlQuery);

        return $this->{$method}($input)->with('url', $url);
    }

    public function save(Request $request)
    {
        $filters['created_at'] = Carbon::now();
        $filters['updated_at'] = Carbon::now();
        $filters = $request->only('name','ac_user_id','filter','ac_start','ac_end','p_id','u_id','c_id');
        $filters['url'] = json_encode($filters );
       //var_dump($filter);
        \App\SaveReport::create($filters);
    }

    private function reportList($request)
    {
        $conditions = $this->cleanRequestInput($request);
        $page = isset($request['page']) ? (int)$request['page'] : 1;
        $timesheets = $this->timesheetRepository->history($conditions, $page, 20);

        $total = $this->timesheetRepository->totalCount;
        \View::share('total', $total);

        $all = $this->timesheetRepository->groupByDay($conditions);
        
        $chartData = [
            'label' => [],
            'data' => [],
        ];

        foreach ($all as $date => $seconds) {
            $total = gmhours($seconds, 'number');
            
            $d = date('M-d', strtotime($date));
            $chartData['label'][] = $d;
            $chartData['data'][] = number_format($total, 2);
        }

        return view('timesheet::report/detailed')
            ->with('timesheets', $timesheets)
            ->with('chartData', $chartData);
    }

    public function reportTeam($request) 
    {
        $conditions = $this->cleanRequestInput($request);
        $report = $this->timesheetRepository->groupUserByDay($conditions);

        $dateRanges = calculateDateRanges([
            'from' => $conditions['start'],
            'to' => $conditions['end']
        ], 'day');

        $header = ['Name'];
        foreach ($dateRanges as $pairDate) {
            $t = strtotime($pairDate['from']);
            $header[] = date('M-d', $t) . '<br/><i>' . date('D', $t) . '</i>';
        }

        foreach ($this->users as $userID => $name) {
            if (!$userID) continue;

            $row = [$name];
            foreach ($dateRanges as $pairDate) {
                $total = isset($report[$userID][$pairDate['from']]) ? $report[$userID][$pairDate['from']] : 0;
                $row[] = gmhours($total, 'short');
            }
                
            $rows[] = $row;
        }

        $data = [
            'header' => $header,
            'rows' => $rows,
        ]; 

        return view('timesheet::report/teamweekly')
            ->with('header', $header)
            ->with('rows', $rows);

    }

    public function download(Request $request, $type)
    {
        $this->projects = $this->projectRepository->options($this->user['id'], 'array', 'All projects');
        $this->users = $this->userRepository->options($this->user['id'], 'array', 'All team members');
        $this->clients = $this->clientRepository->options($this->user['id'], 'array', 'All client');

        $folder = public_path('download');
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $filename = 'report_'.date('Ymd-Hi') . '.csv';
        $file = public_path('download/' . $filename);
        
        $input = $request->all();
        $method = 'download' . ucfirst($type);
        $csvData = $this->{$method}($input);

        $handle = fopen($file, 'w+');
        fputcsv($handle, $csvData['header']);
        foreach ($csvData['rows'] as $key => $value) {
            fputcsv($handle, $value);
        }
        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($file, $filename, $headers);
    }

    private function downloadList($request)
    {
        $row = [];
        $conditions = $this->cleanRequestInput($request);
        $timesheets = $this->timesheetRepository->history($conditions, 1, 1000);

        foreach($timesheets as $timesheet) {
            $seconds = $timesheet->getAttributes()['duration'];
            $hours = floor($seconds / 3600);
            $minutes = floor($seconds / 60 % 60);
            $secs = floor($seconds % 60);

            if ($secs >= 30) {
                $minutes++;
            }

            if ($minutes == 0) {
                $mins_rounded = 0;
            } else if ($minutes > 0 and $minutes <= 15) {
                $mins_rounded = .25;
            } else if ($minutes > 15 and $minutes <= 30) {
                $mins_rounded = .5;
            } else if ($minutes > 30 and $minutes <= 45) {
                $mins_rounded = .75;
            } else {
                $mins_rounded = 1;
            }

            $duration = $hours + $mins_rounded;

            $desc = '';
            if ($timesheet->ticket) {
                $desc .= $timesheet->ticket . ': ';
            }
            $desc .= $timesheet->description;

            $row[] = array(
                $this->users[$timesheet->user_id],
                $desc, 
                $timesheet->getAttributes()['start'],
                $timesheet->getAttributes()['end'],
                $duration
            );
            
        }

        $header = array('Reporter', 'Description', 'Start', 'End', 'Duration');

        return ['header' => $header, 'rows' => $row];
    }

    private function downloadTeam($request)
    {
        $conditions = $this->cleanRequestInput($request);
        $report = $this->timesheetRepository->groupUserByDay($conditions);
        
        $dateRanges = calculateDateRanges([
            'from' => $conditions['start'],
            'to' => $conditions['end']
        ], 'day');

        $header = ['Name'];
        foreach ($dateRanges as $pairDate) {
            $t = strtotime($pairDate['from']);
            $header[] = date('M-d', $t) . ' (' . date('D', $t) . ')';
        }

        foreach ($this->users as $userID => $name) {
            if (!$userID) continue;

            $row = [$name];
            foreach ($dateRanges as $pairDate) {
                $total = isset($report[$userID][$pairDate['from']]) ? $report[$userID][$pairDate['from']] : 0;
                $row[] = gmhours($total);
            }
                
            $rows[] = $row;
        }

        return [
            'header' => $header,
            'rows' => $rows,
        ]; 
    }

    private function cleanRequestInput($input)
    {
        $userID = $this->user['id'];
        if (\Entrust::hasRole('admin')) {
            $userID = null;
            if (isset($input['user_id'])) {
                $userID = (int)$input['user_id'];
            }
        }

        $conditions = [
            'user_id' => $userID
        ];

        $dateString = 'monday this week';
        if (isset($input['start']) && $input['start']) {
            $dateString = $input['start'];
        }

        $date = new \DateTime($dateString);
        $conditions['start'] = $date->format('Y-m-d');

        $dateString = 'sunday this week';
        if (isset($input['end']) && $input['end']) {
            $dateString = $input['end'];
        }

        $date = new \DateTime($dateString);
        $conditions['end'] = $date->format('Y-m-d');

        if (isset($input['project_id'])) {
            $conditions['project_id'] = (int)$input['project_id'];
        }

        if (isset($input['client_id'])) {
            $conditions['client_id'] = (int)$input['client_id'];
        }

        return $conditions;
    }

    public function reset(Request $request)
    {
        $refer = $request->get('d');
        if (!$refer) {
            $refer = route('report.detailed');
        } else {
            $refer = route('report.' . $refer);
        }
        
        $request->session()->put('last_query_report', false);
        return redirect($refer);
    }
}