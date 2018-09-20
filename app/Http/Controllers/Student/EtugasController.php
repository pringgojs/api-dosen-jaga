<?php namespace App\Http\Controllers\Student;

use App\Models\Tugas;
use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EtugasController extends Controller {

	public function index(Request $request)
	{
		$user = $request->input('user');
		$student = Mahasiswa::find($user['id']);
		$list_tugas = Tugas::getDataTugas($student->kelas)->get();
		$list_semester = Tugas::getDataByKuliah($student->kelas)->get();

		return response()->json(
			[
				'data' => $list_tugas,
				'data_semester' => $list_semester,
			]
		);
	}

	public function kuliah(Request $request, $kuliah)
	{
		$user = $request->input('user');
		$student = Mahasiswa::find($user['id']);
		$list_semester = Tugas::getDataByKuliah($student->kelas)->get();
		$list_materi = Tugas::getDataTugas($student->kelas, $kuliah)->get();
		return response()->json(
			[
				'data' => $list_materi,
				'data_semester' => $list_semester,
			]
		);
	}
}
