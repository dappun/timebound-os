<?php

Route::group(['middleware' => 'web', 'prefix' => 'notification', 'namespace' => 'TB\Notification\Http\Controllers'], function()
{
	Route::get('/', 'NotificationController@index');
});