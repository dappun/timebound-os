<?php namespace TB\Client\Entities;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Customer
 * @package App\Models
 * @version August 31, 2016, 2:18 am UTC
 */
class Client extends Model
{
    use SoftDeletes;

    public $table = 'clients';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'description',
        'status_id',
        'contact_person',
        'email',
        'address',
        'web',
        'phone1',
        'phone2'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    public function getStatusAttribute($value) 
    {
        if (1 == $this->status_id) {
            return 'Active';
        }

        return 'Not Active';
    } 

    public function projects()
    {
        return $this->hasMany('\TB\Project\Entities\Project');
    }
}
