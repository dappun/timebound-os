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

class ReportController extends BaseController
{
    /** @var  TimesheetRepository */
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

        $this->setUrl();

        $method = 'report' . ucfirst($type);

        $input = $request->all();
        if ($input) {
            $request->session()->put('last_query_report', $input);
        } else {
            // $input = $request->session()->get('last_query_report');
            // if ($input) {
                //$request->replace($input);    
            // }
        }

        $urlParam = '';
        if ($request->query()) {
            foreach ($request->query() as $key => $value) {
                $urlParam .= !$urlParam ? '?' : '&';
                $urlParam .= $key . '=' . urlencode($value);
            }
        }
            

        \View::share('projects', $this->projects);
        \View::share('users', $this->users);
        \View::share('clients', $this->clients);
        \View::share('request', $request);
        \View::share('urlParam', $urlParam);

        return $this->{$method}($input);
    }

    private function reportList($request)
    {
        $conditions = $this->cleanRequestInput($request);
        $page = isset($request['page']) ? (int)$request['page'] : 1;
        $timesheets = $this->timesheetRepository->history($conditions, $page, 20);

        $total = $this->timesheetRepository->totalCount;
        \View::share('total', $total);

        $all = $this->timesheetRepository->history($conditions, $page, 1000);
        $jsonData = [];
        $jsonData[] = ['Day', 'Hours', ['role' => 'annotation']];
        foreach ($all as $ts) {
            $seconds = (int)$ts->getAttributes()['duration'];
            // $hours = floor($seconds / 3600);
            // $minutes = floor($seconds / 60 % 60) / 60;

            $d = date('d', strtotime($ts->start));
            $jsonData[] = [$d, $seconds, 'h'];
        }

        return view('timesheet::report/detailed')
            ->with('timesheets', $timesheets)
            ->with('jsonData', $jsonData);
    }

    public function reportTeam($request) 
    {
        $conditions = $this->cleanRequestInput($request);
        $report = $this->timesheetRepository->groupByDay($conditions);
        
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
        $report = $this->timesheetRepository->groupByDay($conditions);
        
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

        if (!isset($input['start'])) {
            $conditions['start'] = date('Y-m-d', strtotime('Monday this week'));
        } else {
            $conditions['start'] = date('Y-m-d', strtotime($input['start']));
        }

        if (!isset($input['end'])) {
            $conditions['end'] = date('Y-m-d', strtotime('Sunday this week'));
        } else {
            $conditions['end'] = date('Y-m-d', strtotime($input['end']));
        }

        if (isset($input['project_id'])) {
            $conditions['project_id'] = (int)$input['project_id'];
        }

        if (isset($input['client_id'])) {
            $conditions['client_id'] = (int)$input['client_id'];
        }

        return $conditions;
    }

    private function setUrl()
    {
        $urlParam = [];
        if (isset($_GET['start'])) {
            $urlParam['start'] = strip_tags($_GET['start']);
        }

        if (isset($_GET['end'])) {
            $urlParam['end'] = strip_tags($_GET['end']);
        }

        if (isset($_GET['user_id'])) {
            $urlParam['user_id'] = strip_tags($_GET['user_id']);
        }

        if (isset($_GET['project_id'])) {
            $urlParam['project_id'] = strip_tags($_GET['project_id']);
        }

        if (isset($_GET['client_id'])) {
            $urlParam['client_id'] = strip_tags($_GET['client_id']);
        }

        \View::share('urlParam', $urlParam);
    }
}
