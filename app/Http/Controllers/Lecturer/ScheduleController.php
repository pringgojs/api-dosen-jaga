<?php namespace App\Http\Controllers\Lecturer;

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
		$list_semester = Kuliah::semester()->get();
		$last_kuliah_user = NilaiMasterModul::where('pengasuh', $user['id'])->orderBy('kuliah', 'DESC')->groupBy('kuliah')->first();
		$list_schedule =  NilaiMasterModul::getDataBySemester($user['id'], $last_kuliah_user->kuliah)->get();
		return response()->json(
			[
				'list_schedule' => $list_schedule,
				'list_semester' => $list_semester,
			]
		);
	}

	public function filter(Request $request)
	{
		$user = $request->input('user');
		$request = $request->input('request');
		$semester = explode('/', $request['semester']);
		$kelas = $request['kelas'];
		$matakuliah = $request['matakuliah'];
		$kuliah = Kuliah::select(['*'])
			->where('tahun', $semester[0])->where('semester', $semester[1])->where('kelas', $kelas)
			->where('matakuliah', $matakuliah)
			->where(function ($q) use ($user) {
				foreach (Kuliah::listDosen() as $dosen) {
					$q->orWhere($dosen, $user['id']);
				}
			})
			->first();
		if (!$kuliah) return [];
		
		return NilaiMasterModul::getDataBySemester($user['id'], $kuliah->nomor)->get();
		
	}
}
