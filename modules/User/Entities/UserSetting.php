<?php namespace TB\User\Entities;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'user_id',
        'key',
        'value',
    ];

    public static $rules = [
        'key' => 'required',
        'value' => 'required'
    ];

    public $timestamps = false;
}
