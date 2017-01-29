<?php namespace TB\Timesheet\Http\Requests;

use TB\Core\Http\Requests\Request;
use TB\Timesheet\Entities\Timesheet;

class TimerRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required'
        ];
    }
}
