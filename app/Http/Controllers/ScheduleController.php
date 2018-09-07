<?php namespace App\Http\Controllers;

use App\Models\Kuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMasterModul;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$user = $request->input('user');
		$last_kuliah_user = NilaiMasterModul::where('nilai_master_modul.pengasuh', $user['id'])->max('nilai_master_modul.kuliah');
		$last_kuliah = Kuliah::max('nomor');
		$kuliah = Kuliah::find($last_kuliah_user);
		$list_semester = NilaiMasterModul::joinDependence($user['id'])
			->groupBy('kuliah.tahun')
			->groupBy('kuliah.semester')
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();
		\Log::info($list_semester->toArray());
		$data =  NilaiMasterModul::joinDependence($user['id'])
			->where('nilai_master_modul.kuliah', $last_kuliah_user)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();

		return response()->json(
			[
				'data' => $data,
				'data_semester' => $list_semester,
				'keterangan' => $last_kuliah == $last_kuliah_user ? 'saat ini' : 'semester lalu',
			]
		);
	}

	public function getBySemester(Request $request)
	{
		$user = $request->input('user');
		$list_semester = NilaiMasterModul::joinDependence($user['id'])
			->groupBy('kuliah.tahun')
			->groupBy('kuliah.semester')
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();
		\Log::info($list_semester->toArray());
		$data =  NilaiMasterModul::joinDependence($user['id'])
			->where('nilai_master_modul.kuliah', $last_kuliah_user)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();

		return response()->json(
			[
				'data' => $data,
				'data_semester' => $list_semester,
				'keterangan' => $last_kuliah == $last_kuliah_user ? 'saat ini' : 'semester lalu',
			]
		);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
