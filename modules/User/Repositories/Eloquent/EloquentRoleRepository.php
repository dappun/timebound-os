<?php namespace TB\User\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use TB\Core\Repositories\Eloquent\EloquentBaseRepository;
use TB\User\Entities\Role;
use TB\User\Repositories\RoleRepository;

class EloquentRoleRepository extends EloquentBaseRepository implements RoleRepository
{
    /**
     * @param Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function listBy()
    {
    	return $this->model->pluck('display_name', 'name');
    }
}
