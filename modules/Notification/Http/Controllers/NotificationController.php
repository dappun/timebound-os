<?php namespace TB\Notification\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class NotificationController extends Controller {
	
	public function index()
	{
		return view('notification::index');
	}
	
}