<?php namespace TB\User\Http\Requests;

use TB\Core\Http\Requests\Request;
use TB\User\Entities\User;

class UpdatePasswordRequest extends Request
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
        if ($this->segment(1) == 'account') {
            $id = \Auth::id();
        } else {
            $id = $this->segment(2);
        }

        return [
            'password'  => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        ];
    }
}
