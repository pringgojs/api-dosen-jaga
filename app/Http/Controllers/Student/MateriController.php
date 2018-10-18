<?php namespace App\Http\Controllers\Student;

use App\Models\Nilai;
use App\Models\Tugas;
use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Materi;
use App\Models\Mahasiswa;
use App\Models\NilaiModul;
use Illuminate\Http\Request;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MateriController extends Controller {

	public function index(Request $request)
	{
		/**
		 * Filter
		 * 1. Tampilkan semua semester yang ada ditable kuliah, materi, nilai
		 * 2. Tampilkan semua table materi yang sesuai kelas dia sebelumnya
		 */
		$user = $request->input('user');
		$student = Mahasiswa::find($user['id']);
		$list_semester = Nilai::joinKuliah()->joinMateri()->semester()->where('nilai.mahasiswa', $student->nomor)->get();
		$kuliah = Nilai::joinKuliah()->joinMateri()->select(['kuliah.*'])->where('nilai.mahasiswa', $student->nomor)->groupBy('kuliah.nomor')->first();
		$list_materi = Materi::getDataMateri($kuliah->kelas, $kuliah->nomor)->get();
		return response()->json(
			[
				'list_materi' => $list_materi,
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
		$student = Mahasiswa::find($user['id']);
		$kuliah = Kuliah::joinMateri()->select(['*'])
			->where('kuliah.tahun', $semester[0])->where('kuliah.semester', $semester[1])->where('kuliah.kelas', $kelas)
			->where('kuliah.matakuliah', $matakuliah)
			->first();
		
		if (!$kuliah) return [];
		return Materi::getListMateri($kuliah->nomor)->get();
	}
}
