<?php namespace TB\Notification\Entities;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    public $table = 'notifications';
    
    public $fillable = [
        'type',
        'message',
        'user_id',
        'ref_id'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'required',
        'message' => 'required',
        'user_id' => 'required'
    ];
}
