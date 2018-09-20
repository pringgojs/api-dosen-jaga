<?php namespace App\Http\Controllers\Student;

use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Materi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MateriController extends Controller {

	public function index(Request $request)
	{
		$user = $request->input('user');
		$student = Mahasiswa::find($user['id']);
		$list_semester = Materi::getDataByKuliah($student->kelas)->get();
		$list_materi = Materi::getDataMateri($student->kelas)->get();
		return response()->json(
			[
				'data' => $list_materi,
				'data_semester' => $list_semester,
			]
		);
	}

	public function kuliah(Request $request, $kuliah)
	{
		$user = $request->input('user');
		$student = Mahasiswa::find($user['id']);
		$list_semester = Materi::getDataByKuliah($student->kelas)->get();
		$list_materi = Materi::getDataMateri($student->kelas, $kuliah)->get();
		return response()->json(
			[
				'data' => $list_materi,
				'data_semester' => $list_semester,
			]
		);
	}
}
