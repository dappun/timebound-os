<?php namespace TB\User\Http\Requests;

use TB\Core\Http\Requests\Request;
use TB\User\Entities\User;

class CreateUserRequest extends Request
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
            'name'  => 'required|unique:users,name',
            'email' => 'required|unique:users,email',
            'password'   => 'required|confirmed',
        ];
    }
}
