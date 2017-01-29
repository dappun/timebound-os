<?php

Route::group([
	'middleware' => 'web', 
	'prefix' => '/', 
	'namespace' => '\TB\Client\Http\Controllers'
	], function()
{
	Route::resource('clients', 'ClientController');
});
