<?php namespace TB\Project\Entities;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Project
 * @package App\Models
 * @version August 31, 2016, 2:17 am UTC
 */
class Project extends Model
{
    use SoftDeletes;

    public $table = 'projects';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'client_id',
        'status_id',
        'description',
        'private'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'status_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
    ];

    public function getStatusAttribute($value) 
    {
        if (1 == $this->status_id) {
            return 'Active';
        }

        return 'Not Active';
    }    

    public function getPrivacyAttribute($value) 
    {
        if (1 == $this->private) {
            return 'Private';
        }

        return 'Public';
    }  

    public function client()
    {
        return $this->hasOne('\TB\Client\Entities\Client', 'id', 'client_id');
    }

    public function clientName()
    {
        $client = $this->client()->first();
        if ($client) {
            return $client->name;    
        }

        return '';
        // dd($client);
        
    }
}
