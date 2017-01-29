<?php namespace TB\Timesheet\Http\Requests;

use TB\Core\Http\Requests\Request;
use TB\Timesheet\Entities\Timesheet;

class CreateTimesheetRequest extends Request
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
            'start_date' => 'required',
            'start_time' => 'required',
            'description' => 'required'
        ];
    }
}
