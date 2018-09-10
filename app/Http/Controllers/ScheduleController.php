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
			->groupBy('nilai_master_modul.modul')
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();
		$data =  NilaiMasterModul::getDataBySemester($user['id'], $last_kuliah_user)->get();

		return response()->json(
			[
				'data' => $data,
				'data_semester' => $list_semester,
				'keterangan' => $last_kuliah == $last_kuliah_user ? 'saat ini' : 'semester lalu',
			]
		);
	}

	public function getBySemester(Request $request, $kuliah)
	{
		$user = $request->input('user');
		$kuliah = Kuliah::find($kuliah);
		$data =  NilaiMasterModul::getDataBySemester($user['id'], $kuliah->nomor)->get();
		
		return response()->json(
			[
				'data' => $data,
				'keterangan' => 'semester ('.$kuliah->tahun.'/'.$kuliah->semester.')',
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
