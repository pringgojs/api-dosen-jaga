<?php namespace App\Http\Controllers\Student;

use App\Models\Kuliah;
use App\Models\Mahasiswa;
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
		$student = Mahasiswa::find($user['id']);

		$list_schedule = NilaiMasterModul::joinKuliah()
			->joinKelas()
			->joinJurusan()
			->joinProgram()
			->joinMahasiswa()
			->joinMatakuliah()
			->where('mahasiswa.nomor', $user['id'])
			->whereRaw('kuliah.tahun = (SELECT max(tahun) from kuliah where kelas = '.$student->kelas.') and kuliah.semester = (SELECT max(semester) from kuliah where kelas = '.$student->kelas.')')
			->select([
				'kuliah.tahun',
				'kuliah.semester',
				'kelas.pararel',
				'kelas.kelas',
				'program.program',
				'jurusan.jurusan',
				'matakuliah.matakuliah',
				'nilai_master_modul.kuliah',
				'nilai_master_modul.modul',
				'nilai_master_modul.nomor as nomor_nilai_master_modul'
			])
			->groupBy('kuliah.kelas')
			->groupBy('nilai_master_modul.nomor')
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();
		return response()->json(
			[
				'data' => $list_schedule,
			]
		);
	}

}
