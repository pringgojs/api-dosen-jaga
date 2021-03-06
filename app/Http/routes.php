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
    Route::post('reset-password', 'LoginController@resetPassword');
	
	// lecturer
	Route::group(['prefix' => 'lecturer', 'namespace' => 'Lecturer'], function ()   {
		Route::post('schedule/filter', 'ScheduleController@filter');
		Route::post('schedule', 'ScheduleController@index');
		
		// Report
		Route::post('report/nilai-permodul/sync', 'ReportController@sync');
		Route::post('report/nilai-permodul/detail', 'ReportController@detail');
		Route::post('report/nilai-permodul/filter', 'ReportController@filter');
		Route::post('report/nilai-permodul', 'ReportController@nilaiPermodul');

		// materi
		Route::post('materi/update/{id}', 'MateriController@update');
		Route::post('materi/edit/{id}', 'MateriController@edit');
		Route::post('materi/filter', 'MateriController@filter');
		Route::post('materi/delete/{id}', 'MateriController@delete');
		Route::post('materi/store', 'MateriController@store');
		Route::post('materi/create', 'MateriController@create');
		Route::post('materi', 'MateriController@index');

		// etugas
		Route::post('e-tugas/set-nilai', 'EtugasController@setNilai');
		Route::post('e-tugas/filter', 'EtugasController@filter');
		Route::post('e-tugas/update/{id}', 'EtugasController@update');
		Route::post('e-tugas/detail/{id}', 'EtugasController@detail');
		Route::post('e-tugas/edit/{id}', 'EtugasController@edit');
		Route::post('e-tugas/delete/{id}', 'EtugasController@delete');
		Route::post('e-tugas/store', 'EtugasController@store');
		Route::post('e-tugas/create', 'EtugasController@create');
		Route::post('e-tugas', 'EtugasController@index');

		Route::post('index', 'LecturerController@index');
		
	});

	// student
	Route::group(['prefix' => 'student', 'namespace' => 'Student'], function ()   {
		Route::post('schedule/get-by-semester/{kuliah}', 'ScheduleController@getBySemester');
		Route::post('schedule', 'ScheduleController@index');
		
		// materi
		Route::post('materi/filter', 'MateriController@filter');
		Route::post('materi', 'MateriController@index');

		// etugas
		Route::post('etugas/filter', 'EtugasController@filter');
		Route::post('etugas/store', 'EtugasController@store');
		Route::post('etugas/detail/{id}', 'EtugasController@detail');
		Route::post('etugas', 'EtugasController@index');

		Route::post('dashboard', 'DashboardController@index');
		
	});

	Route::group(['prefix' => 'filter'], function ()   {
		Route::group(['prefix' => 'lecturer', 'namespace' => 'Lecturer'], function ()   {
			Route::post('get-modul', 'FilterController@getModul');
			Route::post('get-matakuliah', 'FilterController@getMatakuliah');
			Route::post('get-kelas', 'FilterController@getKelas');
			Route::post('get-jurusan', 'FilterController@getJurusan');
			Route::get('get-semester', 'FilterController@getSemester');
		});

		Route::group(['prefix' => 'student', 'namespace' => 'Student'], function ()   {
			Route::post('get-modul', 'FilterController@getModul');
			Route::post('get-matakuliah', 'FilterController@getMatakuliah');
			Route::post('get-kelas', 'FilterController@getKelas');
			Route::get('get-semester/{type}', 'FilterController@getSemester');
		});
	});
});



Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
