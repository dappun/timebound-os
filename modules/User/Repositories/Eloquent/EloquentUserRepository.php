<?php namespace TB\User\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use TB\Core\Repositories\Eloquent\EloquentBaseRepository;
use TB\User\Entities\User;
use TB\User\Repositories\UserRepository;

class EloquentUserRepository extends EloquentBaseRepository implements UserRepository
{
    public $singularName = 'user';
    
    /**
     * @param Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function listAll()
    {
        return $this->model->orderBy('name')->pluck('name', 'id');
    }

    public function activeAll()
    {
        return $this->model->where('status_id', 1)->orderBy('name')->pluck('name', 'id');
    }

    public function userWithSettingsOn($key){
        return \DB::table('users')
            ->leftJoin('user_settings', 'user_settings.user_id', '=', 'users.id')
            ->where('key', $key)
            ->where('value', 1)
            ->get();   
    }

    function saveSettings($idOrObject, $input)
    {
        if (is_integer($idOrObject)) {
            $user = $this->model->find($idOrObject);
        } else {
            $user = $idOrObject;
        }

        // dd($user->listSettings());

        $currentValues = [];
        foreach ($user->listSettings() as $item) {
            $currentValues[$item->key] = $item;
        }

        \DB::beginTransaction();

        try {
            foreach ($currentValues as $key => $value) {
                $s = $currentValues[$key];
                if ($key == 'timezone') {
                    $s->value = $input[$key];
                } else {
                    $s->value = isset($input[$key]) ? 1 : 0;
                }

                $s->user_id = $user->id;
                $s->key = $key;

                $s->save();
            }
        }
        catch(\Exception $e)
        {
           \DB::rollback();
           throw $e;
        }

        \DB::commit();
    }
}
