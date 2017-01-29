<?php
Route::group(['middleware' => 'web'], function () {
    // Authentication Routes...
	Route::get('login', '\App\Http\Controllers\Auth\AuthController@showLoginForm');
	Route::post('login', '\App\Http\Controllers\Auth\AuthController@login');
	Route::get('logout', '\App\Http\Controllers\Auth\AuthController@logout');

	// Registration Routes...
	// Route::get('register', 'Auth\AuthController@showRegistrationForm');
	// Route::post('register', 'Auth\AuthController@register');

	Route::get('password/reset/{token?}', 'App\Http\Controllers\Auth\PasswordController@showResetForm');
	Route::post('password/email', 'App\Http\Controllers\Auth\PasswordController@sendResetLinkEmail');
	Route::post('password/reset', 'App\Http\Controllers\Auth\PasswordController@reset');

	Route::get('/', function () {
		if (\Auth::user()) {
			return redirect('/timer');
		} else {
			return redirect('/login');	
		}  
	});
});

	

	