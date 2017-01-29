<?php namespace TB\Notification\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use TB\Core\Repositories\Eloquent\EloquentBaseRepository;
use TB\Notification\Entities\Notification;
use TB\Notification\Repositories\NotificationRepository;

class EloquentNotificationRepository extends EloquentBaseRepository implements NotificationRepository
{
    public function __construct($model)
    {
        parent::__construct($model);
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notification::class;
    }

    public function existThisDate($cond)
    {
        \DB::enableQueryLog();
        $query = $this->model->where('user_id', $cond['user_id'])
            ->where('type', $cond['type'])
            ->where('ref_id', $cond['ref_id']);

        $query->where('created_at', '>=', $cond['date'] . ' 00:00:00'); 
        $query->where('created_at', '<=', $cond['date'] . ' 23:59:59'); 

        $result = $query->get();
        // dd(\DB::getQueryLog());
        return $result;
    }
}
