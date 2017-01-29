<?php namespace TB\Project\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use TB\Core\Repositories\Eloquent\EloquentBaseRepository;
use TB\Project\Entities\Project;
use TB\Project\Repositories\ProjectRepository;

class EloquentProjectRepository extends EloquentBaseRepository implements ProjectRepository
{
    public $singularName = 'project';
    
	public function listAll()
    {
        return $this->model->orderBy('title')->lists('title', 'id');
    }

    protected function activeAll()
    {
        return $this->model->where('status_id', 1)->orderBy('title')->pluck('title', 'id');
    }

    public function members()
    {

    }

    public function client()
    {
        
    }

    public function contributors($id, $conditions = [])
    {
        $query = $this->model->where('projects.id', '=', $id)
            ->leftJoin('timesheets', 'projects.id', '=', 'timesheets.project_id')
            ->leftJoin('users', 'timesheets.user_id', '=', 'users.id')
            ->select([
                'users.*', 
                \DB::raw('sum(timesheets.duration) as total')
            ])
            ->groupBy('timesheets.user_id');

        $fr = date('Y-m-d H:i:s', strtotime($conditions['start']));
        $to = date('Y-m-d 23:59:59', strtotime($conditions['end']));

        if ($fr) {
           $query->where('start', '>=', $fr); 
        }

        if ($to) {
            $query->where(function($q) use ($to) {
                $q->where('end', '<=', $to)
                    ->orWhere('end', '0000-00-00 00:00:00')
                    ->orWhereNull('end');
            });
        }   

        $result = $query->get();

        return $result;
    }

    public function totalHours($id, $conditions = [])
    {
        $result = $this->model->where('projects.id', '=', $id)
            ->leftJoin('timesheets', 'projects.id', '=', 'timesheets.project_id')
            ->select(\DB::raw('sum(timesheets.duration) as total'))
            ->first();   
        if ($result) {
            return gmhours($result->total);
        }

        return 0;
    }
}
