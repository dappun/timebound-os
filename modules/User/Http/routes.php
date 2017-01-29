<?php

Route::group([
	'middleware' => 'web', 
	'prefix' => 'admin', 
	'namespace' => 'TB\User\Http\Controllers'
	], function()
{
	Route::resource('/users', 'UserController');
});

Route::group(['middleware' => 'web', 'namespace' => 'TB\User\Http\Controllers'], function()
{
	Route::get('/account', 'AccountController@index')->name('account.view');
	Route::post('/account/update', 'AccountController@update')->name('account.update');
	Route::post('/account/pasword', 'AccountController@password')->name('account.password');
	Route::get('/account/setting', 'AccountController@setting')->name('account.setting');
	Route::post('/account/setting', 'AccountController@setting_update')->name('account.setting_update');

	Route::get('/user/avatar/{id}', ['uses' => 'AccountController@imageDelete', 'as' => 'avatar.delete']);
	Route::patch('/user/avatar/{id}', ['uses' => 'AccountController@imageUpload', 'as' => 'avatar.upload']);
});

	
