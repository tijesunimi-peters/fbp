<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return Redirect::to('http://localhost/laravel/public/index.php/facelift/user-login');
			// return  Redirect::to('facelift.userLogin');

});

Route::controller('facelift', 'faceliftController');
// Route::controller('upload','faceliftControllerCont');
