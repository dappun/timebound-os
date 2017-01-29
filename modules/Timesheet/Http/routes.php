<?php

Route::group(['middleware' => 'web', 'prefix' => '/', 'namespace' => 'TB\Timesheet\Http\Controllers'], function()
{
	Route::resource('timer', 'TimesheetController');
	Route::get('timer/{id}/copy', 'TimesheetController@copy')->name('timer.copy');
	Route::post('timer/{id}/copy', 'TimesheetController@copy_post')->name('timer.copy.post');

	Route::get('api/timer', 'ApiTimesheetController@index')->name('api.timer.index');
	Route::post('api/timer/stopwatch', 'ApiTimesheetController@stopwatch')->name('api.timer.stopwatch');
	Route::get('api/timer/ongoing', 'ApiTimesheetController@ongoing')->name('api.timer.ongoing');
});

Route::group(['middleware' => 'web', 'prefix' => '/report', 'namespace' => 'TB\Timesheet\Http\Controllers'], function()
{
	Route::get('/{type?}', 'ReportController@detailed')->name('report.detailed');
	// Route::post('/', 'ReportController@detailed')->name('report.detailed');
	Route::get('/team', 'ReportController@teamweekly')->name('report.teamweekly');
	Route::get('/download/{type}', 'ReportController@download')->name('report.download');
});
