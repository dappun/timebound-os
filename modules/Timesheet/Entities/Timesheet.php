<?php namespace TB\Timesheet\Entities;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timesheet extends Model
{
    use SoftDeletes;

    public $table = 'timesheets';
    
    protected $dates = ['deleted_at'];

    public $fillable = [
        'project_id',
        'ticket',
        'user_id',
        'category_id',
        'start',
        'end',
        'description',
        'duration'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'start' => 'required',
        'description' => 'required'
    ];

    public function getDurationAttribute($value) 
    {
        // Only displays the minutes and hours
        return gmhours($value);
        // return gmdate('H:i:s', $value);
    }

    public function getDurationNumberAttribute($value) 
    {
        return $value;
    }

    public function getStartAttribute($value)
    {
        if ($value) {
            $date = convertDate($value, 'db-to-user');
            return $date->format('Y-m-d H:i:s');
        }
    }

    public function getEndAttribute($value)
    {
        if ($value) {
            $date = convertDate($value, 'db-to-user');
            return $date->format('Y-m-d H:i:s');
        }
    }
}
