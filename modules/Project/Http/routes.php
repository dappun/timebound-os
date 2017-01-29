<?php

Route::group(['middleware' => 'web', 'prefix' => '/',  'namespace' => '\TB\Project\Http\Controllers'], function()
{
	Route::resource('projects', 'ProjectController');
});