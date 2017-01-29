<?php namespace TB\User\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait;

    protected $settingModel = 'TB\User\Entities\UserSetting';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'timer',
        'status_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $rules = [
        'name' => 'required|unique',
        'email' => 'required|unique',
        'password'   => 'required|confirmed',
    ];

    public function getStatusAttribute($value) 
    {
        if (1 == $this->status_id) {
            return 'Active';
        }

        return 'Not Active';
    }  

    public function settings()
    {
        return $this->hasMany($this->settingModel);
    }

    public function listSettings()
    {
        $currentValues = [];
        foreach ($this->settings as $item) {
            $currentValues[$item->key] = $item;
        }

        $default = $this->defaultSettings();

        return $currentValues + $default;
    }

    public function defaultSettings()
    {
        $def = [
            'weekly_time_report' => new $this->settingModel,
            'daily_ot_report' => new $this->settingModel,
            'timezone' => new $this->settingModel,
        ];

        foreach ($def as $key => $value) {
            $def[$key]->key = $key;
        }

        return $def;
    }

    public function getTimezoneId()
    {
        return $this->listSettings()['timezone']->value;
    }

    public function getTimezone()
    {
        $timezone_identifiers = \DateTimeZone::listIdentifiers();
        $id = $this->listSettings()['timezone']->value;
        
        if ($id && isset($timezone_identifiers[$id])) {
            return $timezone_identifiers[$id];
        }

        return \Config::get('app.timezone');
    }

    public function getTimezoneAttribute()
    {
        return $this->getTimezone();
    }
}
