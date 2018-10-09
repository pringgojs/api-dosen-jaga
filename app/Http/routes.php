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
	Route::group(['prefix' => 'lecturer', 'namespace' => 'Lecturer'], function ()   {
		Route::post('schedule/get-by-semester/{kuliah}', 'ScheduleController@getBySemester');
		Route::post('schedule', 'ScheduleController@index');
		
		// materi
		Route::post('materi/get-by-kuliah', 'MateriController@getByKuliah');
		Route::post('materi/delete/{id}', 'MateriController@delete');
		Route::post('materi/store', 'MateriController@store');
		Route::post('materi', 'MateriController@index');

		// etugas
		Route::post('e-tugas/set-nilai', 'EtugasController@setNilai');
		Route::post('e-tugas/get-by-kuliah', 'EtugasController@getByKuliah');
		Route::post('e-tugas/update/{id}', 'EtugasController@update');
		Route::post('e-tugas/detail/{id}', 'EtugasController@detail');
		Route::post('e-tugas/edit/{id}', 'EtugasController@edit');
		Route::post('e-tugas/delete/{id}', 'EtugasController@delete');
		Route::post('e-tugas/store', 'EtugasController@store');
		Route::post('e-tugas', 'EtugasController@index');

		Route::post('index', 'LecturerController@index');
		
	});

	// student
	Route::group(['prefix' => 'student', 'namespace' => 'Student'], function ()   {
		Route::post('schedule/get-by-semester/{kuliah}', 'ScheduleController@getBySemester');
		Route::post('schedule', 'ScheduleController@index');
		
		// materi
		Route::post('materi/kuliah/{id}', 'MateriController@kuliah');
		Route::post('materi', 'MateriController@index');

		// etugas
		Route::post('etugas/kuliah/{id}', 'EtugasController@kuliah');
		Route::post('etugas/store', 'EtugasController@store');
		Route::post('etugas/detail/{id}', 'EtugasController@detail');
		Route::post('etugas', 'EtugasController@index');

		Route::post('dashboard', 'DashboardController@index');
		
	});
});


Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
