<?php namespace TB\Core\Http\Controllers;

use Nwidart\Modules\Routing\Controller;
use Flash;
use Illuminate\Support\Facades\Auth as Auth;
use InfyOm\Generator\Utils\ResponseUtil;

class BaseController extends Controller 
{
	protected $user;
		
	public function __construct()
	{
		if (\Auth::check()) {
            $this->user = [
                'id' => \Auth::id(),
                'name' => \Auth::user()->name
            ];
        }
	}
	public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }
}