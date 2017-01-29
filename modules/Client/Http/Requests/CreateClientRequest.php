<?php namespace TB\Client\Http\Requests;

use TB\Core\Http\Requests\Request;
use TB\Client\Entities\Client;

class CreateClientRequest extends Request
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
        return Client::$rules;
    }
}
