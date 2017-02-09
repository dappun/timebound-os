<?php namespace TB\Timesheet\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use TB\Core\Repositories\Eloquent\EloquentBaseRepository;
use TB\Timesheet\Entities\Timesheet;
use TB\Timesheet\Repositories\TimesheetRepository;
use Carbon\Carbon;

class EloquentTimesheetRepository extends EloquentBaseRepository implements TimesheetRepository
{
    public $totalCount;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description'
    ];

    public function __construct($model)
    {
        parent::__construct($model);

        $this->userTz = userTimezone();
        $this->dbTz = \Config::get('core.timezone.db');
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TimeEntry::class;
    }

    /**
     * @param  array $condition
     * @param  integer $page
     * @param  integer $limit
     * @return Paginator
     */
    public function history($condition, $page = 0, $limit = 10)
    {
        $query = $this->model->select(
            'timesheets.*', 
            // \DB::raw("CONVERT_TZ(start, 'UTC', '{$this->userTz}') as start"),
            // \DB::raw("CONVERT_TZ(end, 'UTC', '{$this->userTz}') as end"),
            'projects.title AS project_title', 
            'users.name AS user_name'
        );
        
        $query->leftJoin('projects', 'timesheets.project_id', '=', 'projects.id');
        $query->leftJoin('users', 'timesheets.user_id', '=', 'users.id');

        $this->setWhereConditions($query, $condition, [
            'user_id', 'project_id', 'client_id' => 'projects.client_id'
        ]);

        $fr = $this->_timeformatting($condition, 'start', 'Y-m-d 00:00:00');
        // Time is already converted in the field
        $fr = \Timezone::convertToUTC($fr, userTimezone(), 'Y-m-d H:i:s');

        $to = $this->_timeformatting($condition, 'end', 'Y-m-d 23:59:59');
        // Time is already converted in the field
        $to = \Timezone::convertToUTC($to, userTimezone(), 'Y-m-d H:i:s');

        if ($fr) {
           $query->where('start', '>=', $fr); 
        }

        if ($to) {
            $query->where('start', '<=', $to);
            $query->where('end', '!=', '0000-00-00 00:00:00');
        }

        // $query->where(function($q) use ($to) {
        //     $q->where('start', '<=', $to)
        //         ->orWhere('end', '0000-00-00 00:00:00')
        //         ->orWhereNull('end');
        // });

        $totalQuery = clone $query;
        $this->totalCount = $totalQuery->sum('timesheets.duration');

        $result = $query->orderBy('start', 'desc')->paginate($limit, ['*'], 'page', $page);

        return $result;
    }

    public function findOngoing($userId)
    {
        $query = $this->model->where('user_id', $userId);
        $query->where(function($q) {
              $q->Where('end', '0000-00-00 00:00:00')
                ->orWhereNull('end');
        });

        $result = $query->orderBy('start', 'asc')->first();

        if ($result) {
            $result->duration = computeDuration($result->start, date('Y-m-d H:i:s'));
            $result->duration_raw = computeDuration($result->start, date('Y-m-d H:i:s'), 'array');
        }
            
        return $result;
    }

    public function findIDWithCompleteData($id)
    {
        // Get object with some other info from the table such as the user name and project name
        $query = $this->model->select(
            'timesheets.*',
            'projects.title AS project_title', 
            'users.name AS user_name'
        );
        
        $query->leftJoin('projects', 'timesheets.project_id', '=', 'projects.id');
        $query->leftJoin('users', 'timesheets.user_id', '=', 'users.id');
        $query->where('timesheets.id', $id);

        $result = $query->first();
        return $result;
    }

    public function stopwatch($input)
    {
        $timer = $this->findOngoing($input['user_id']);
        if ($timer) {
            $input['id'] = $timer->id;
            return $this->stop($input);
        } else {
            return $this->start($input);
        }
    }

    public function start($input)
    {
        $cleanInput = [
            'description'   => strip_tags($input['description']),
            'project_id'    => (int)@$input['project_id'],
            'ticket'        => strip_tags($input['ticket']),
            'user_id'       => (int)$input['user_id'],
            'start'         => date('Y-m-d H:i:s')
        ];

        $obj = $this->create($cleanInput);
        $result = $this->findIDWithCompleteData($obj->id);
        return $result;
    }

    public function stop($input)
    {
        $cleanInput = [
            'id'            => $input['id'],
            'end'           => date('Y-m-d H:i:s'),
            'description'   => strip_tags($input['description']),
            'project_id'    => (int)@$input['project_id'],
            'ticket'        => strip_tags($input['ticket']),
        ];
        
        $obj = $this->update($cleanInput);
        $result = $this->findIDWithCompleteData($obj->id);
        return $result;
    }

    public function copy($id)
    {
        $old = $this->find($id);

        $newInput['start'] = date('Y-m-d H:i:s');

        $new = $old->replicate();
        $new->start = $this->_timeformatting($newInput, 'start');
        $new->end = null;
        $new->duration = 0;
        $new->save();

        $result = $this->findIDWithCompleteData($new->id);
        return $result;
    }

    public function create($input)
    {
        $tsObj = new $this->model();
        foreach ($tsObj->fillable as $tableName) {
            if (isset($input[$tableName])) {
                $tsObj->$tableName = $input[$tableName];    
            }
        }

        $tsObj->start = $this->_timeformatting($input, 'start');
        $tsObj->end = $this->_timeformatting($input, 'end');
        $tsObj->user_id = $input['user_id'];

        if ($tsObj->start && $tsObj->end) {
            $tsObj->duration = computeDuration($tsObj->start, $tsObj->end);    
        }
        

        date_default_timezone_set($this->dbTz);
        $tsObj->save();
        date_default_timezone_set($this->userTz);

        return $tsObj;
    }

    public function update($input)
    {
        $tsObj = $this->find($input['id']);
        
        foreach ($tsObj->fillable as $tableName) {
            if (isset($input[$tableName])) {
                $tsObj->$tableName = $input[$tableName];    
            }
        }

        if (isset($input['start']) || isset($input['start_date'])) {
            $tsObj->start = $this->_timeformatting($input, 'start');
        }
        
        if (isset($input['end']) || isset($input['end_date'])) {
            $tsObj->end = $this->_timeformatting($input, 'end');
        }

        if ($tsObj->start && $tsObj->end) {
            $tsObj->duration = computeDuration($tsObj->start, $tsObj->end);    
        }

        date_default_timezone_set($this->dbTz);
        $tsObj->save();
        date_default_timezone_set($this->userTz);

        return $tsObj;
    }

    private function _timeformatting($input, $field, $format = 'Y-m-d H:i:s')
    {
        $dateString = '';

        if (isset($input[$field]) && $input[$field]) {
            $dateString = date('Y-m-d H:i:s', strtotime($input[$field]));
        } else {
            if (isset($input[$field . '_date']) && $input[$field . '_date']) {
                $dateString = date('Y-m-d', strtotime($input[$field . '_date']));
            }

            if (isset($input[$field . '_time']) && $input[$field . '_time']) {
                $dateString .= ' ' . date('H:i:s', strtotime($input[$field . '_time']));
            }
        } 

        if (!$dateString) {
            return null;
        }

        $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateString, $this->userTz);
        $date->setTimezone($this->dbTz);

        return $date->format($format);
    }

    public function findMine($id, $userID)
    {
        return $this->model->where('user_id', '=', $userID)
            ->where('id', '=', $id)->first();
    }

    public function groupByDay($condition) 
    {
        $query = $this->model->select(
            \DB::raw("DATE(CONVERT_TZ(start, 'UTC', '{$this->userTz}')) as stat_day"),
            \DB::raw('SUM(duration) AS total_duration')
        );

        $fr = $this->_timeformatting($condition, 'start', 'Y-m-d 00:00:00');
        $to = $this->_timeformatting($condition, 'end', 'Y-m-d 23:59:59');
        $query->whereBetween('start', [
            \Timezone::convertToUTC($fr, userTimezone(), 'Y-m-d H:i:s'),
            \Timezone::convertToUTC($to, userTimezone(), 'Y-m-d H:i:s')
        ]);

        $this->setWhereConditions($query, $condition, [
            'user_id', 'project_id', 'client_id' => 'projects.client_id'
        ]);

        $query->leftJoin('projects', 'timesheets.project_id', '=', 'projects.id');
        $query->leftJoin('users', 'timesheets.user_id', '=', 'users.id');

        $groupBy = 'stat_day';
        $orderBy = 'stat_day';

        $query->groupBy($groupBy);
        $query->orderBy($orderBy);
        $result = $query->get();
        
        $dateRanges = calculateDateRanges([
            'from' => $condition['start'],
            'to' => $condition['end']
        ], 'day');

        $orderedResult = [];
        foreach ($result as $item) {
            $orderedResult[$item->stat_day] = $item->total_duration;
        }

        $orderedResult2 = [];
        foreach ($dateRanges as $key => $value) {
            $date = $value['from'];
            if (!isset($orderedResult[$date])) {
                $orderedResult2[$date] = 0;
            } else {
                $orderedResult2[$date] = (float)$orderedResult[$date];
            }
        }
        

        return $orderedResult2;
    }

    public function groupUserByDay($condition) 
    {
        $query = $this->model->select(
            \DB::raw("DATE(CONVERT_TZ(start, 'UTC', '{$this->userTz}')) as stat_day"),
            \DB::raw('SUM(duration) AS total_duration'),
            'user_id'
        );

        $fr = $this->_timeformatting($condition, 'start', 'Y-m-d 00:00:00');
        $to = $this->_timeformatting($condition, 'end', 'Y-m-d 23:59:59');
        $query->whereBetween('start', [
            \Timezone::convertToUTC($fr, userTimezone(), 'Y-m-d H:i:s'),
            \Timezone::convertToUTC($to, userTimezone(), 'Y-m-d H:i:s')
        ]);

        $this->setWhereConditions($query, $condition, [
            'user_id', 'project_id', 'client_id' => 'projects.client_id'
        ]);

        $query->leftJoin('projects', 'timesheets.project_id', '=', 'projects.id');
        $query->leftJoin('users', 'timesheets.user_id', '=', 'users.id');

        $groupBy = ['user_id', 'stat_day'];
        
        $query->groupBy($groupBy);
        $query->orderBy('user_id', 'stat_day');
        $result = $query->get();

        $orderedResult = [];
        foreach ($result as $item) {
            $orderedResult[$item->user_id][$item->stat_day] = $item->total_duration;
        }

        return $orderedResult;
    }

    public function overtimeList($userID, $date, $duration, $page = 0, $limit = 10)
    {
        $query = $this->model->select('timesheets.*', 'projects.title AS project_title', 'users.name AS user_name');
        
        $query->leftJoin('projects', 'timesheets.project_id', '=', 'projects.id');
        $query->leftJoin('users', 'timesheets.user_id', '=', 'users.id');
        $query->where('user_id', (int)$userID);

        $fr = $date . ' 00:00:00';
        $to = $date . ' 23:59:59';

        $query->where('start', '>=', $fr);
        $query->where('end', '<=', $to);
        $query->where('duration', '>', $duration);

        $result = $query->orderBy('start', 'desc')->paginate($limit, ['*'], 'page', $page);

        // dd(\DB::getQueryLog());

        return $result;
    }

    public function overlimit($limit)
    {
        $query = $this->model->select(
            'timesheets.*', 
            'projects.title AS project_title', 
            'users.name AS user_name',
            \DB::raw('TIME_TO_SEC(TIMEDIFF(NOW(), start)) as diff_in_seconds')
        );
        
        $query->leftJoin('projects', 'timesheets.project_id', '=', 'projects.id');
        $query->leftJoin('users', 'timesheets.user_id', '=', 'users.id');

        $query->where(function($q) {
            $q->where('end', '0000-00-00 00:00:00')
                ->orWhereNull('end');
        });

        $query->where(\DB::raw('TIME_TO_SEC(TIMEDIFF(NOW(), start))'), '>', $limit);

        return $query->get();
    }

    private function setWhereConditions(&$query, $condition, $fields = []) 
    {
        $dataClean = [
            'description' => 'strip_tags', 
            'ticket' => 'strip_tags',
        ];

        if ($fields == '*') {
            $fields = $this->model->fillable;
        }

        foreach ($fields as $fieldname => $dbFieldname) {
            if (is_integer($fieldname)) {
                $fieldname = $dbFieldname;
            }

            if (isset($condition[$fieldname]) && $condition[$fieldname]) {
                $value = $condition[$fieldname];
                if (isset($dataClean[$fieldname])) {
                    $value = $dataClean[$fieldname]($value);
                }

                $query->where($dbFieldname, $value);
            }
        }
    }
}
