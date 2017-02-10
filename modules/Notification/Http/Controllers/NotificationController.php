<?php namespace TB\Notification\Http\Controllers;

use TB\Core\Http\Controllers\BaseController; 

class NotificationController extends Controller {
	
	public function index()
	{
		return view('notification::index');
	}
	
}