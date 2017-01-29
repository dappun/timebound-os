<?php namespace TB\Client\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use TB\Core\Repositories\Eloquent\EloquentBaseRepository;
use TB\Client\Entities\Client;
use TB\Client\Repositories\ClientRepository;

class EloquentClientRepository extends EloquentBaseRepository implements ClientRepository
{
	public $singularName = 'client';

	public function listAll()
    {
        return $this->model->orderBy('name')->lists('name', 'id');
    }

    public function activeAll()
    {
        return $this->model->where('status_id', 1)->orderBy('name')->pluck('name', 'id');
    }

    public function contributors($id, $conditions = [])
    {
        $query = $this->model
        	->leftJoin('projects', 'projects.client_id', '=', 'clients.id')
        	->leftJoin('timesheets', 'projects.id', '=', 'timesheets.project_id')
        	->leftJoin('users', 'timesheets.user_id', '=', 'users.id')
        	->where('clients.id', '=', $id)
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
        $result = $this->model
        	->leftJoin('projects', 'projects.client_id', '=', 'clients.id')
        	->leftJoin('timesheets', 'projects.id', '=', 'timesheets.project_id')
        	->where('clients.id', '=', $id)
            ->select(\DB::raw('sum(timesheets.duration) as total'))
            ->first();   
        if ($result) {
            return gmhours($result->total);
        }

        return 0;
    }

    public function projects()
    {
    	
    }
}
