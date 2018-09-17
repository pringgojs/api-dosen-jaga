<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['prefix' => 'api'], function ()   {
    Route::post('login', 'LoginController@login');
	
	// lecturer
	Route::group(['prefix' => 'lecturer'], function ()   {
		Route::post('schedule/get-by-semester/{kuliah}', 'ScheduleController@getBySemester');
		Route::post('schedule', 'ScheduleController@index');
		
		Route::post('materi/delete/{id}', 'MateriController@delete');
		Route::post('materi', 'MateriController@index');
		Route::post('materi/store', 'MateriController@store');


		Route::post('index', 'LecturerController@index');
		
	});
});


Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
