<?php namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Tugas;
use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EtugasController extends Controller {

	public function index(Request $request)
	{
		$user = $request->input('user');
		$student = Mahasiswa::find($user['id']);
		$list_semester = Tugas::getDataByKuliah($student->kelas)->get();
		$list_tugas = Tugas::getDataTugas($student->kelas)->get();
		$list_tugas = $list_tugas->map(function ($item) use($user) {
			$item['nilai_mahasiswa'] = NilaiMahasiswa::where('tugas_id', $item->id)->where('nrp', $user['username'])->first() ? : null;
    		return $item;
		});
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
		$list_tugas = Tugas::getDataTugas($student->kelas, $kuliah)->get();
		$list_tugas = $list_tugas->map(function ($item) use($user) {
			$item['nilai_mahasiswa'] = NilaiMahasiswa::where('tugas_id', $item->id)->where('nrp', $user['username'])->first() ? : null;
    		return $item;
		});
		return response()->json(
			[
				'data' => $list_tugas,
				'data_semester' => $list_semester,
			]
		);
	}

	public function detail(Request $request, $id)
	{
		$user = $request->input('user');
		$student = Mahasiswa::find($user['id']);
		$etugas = Tugas::find($id);
		$nilai_mahasiswa = NilaiMahasiswa::where('tugas_id', $etugas->id)->where('nrp', $user['username'])->first();
		return response()->json(
			[
				'etugas' => $etugas,
				'etugas_kelas' => $etugas->toKelas,
				'etugas_kuliah' => $etugas->toKuliah,
				'etugas_pegawai' => $etugas->toPegawai,
				'etugas_matakuliah' => $etugas->toKuliah->mataKuliah,
				'etugas_modul' => $etugas->nilaiMasterModul,
				'etugas_nilai_mahasiswa' => $nilai_mahasiswa,
			]
		);
	}

	public function store(Request $request)
	{
		$user = $request->input('user');
		$file = $request->input('file');
		$request = $request->input('request');

		DB::beginTransaction();
		$etugas_find = NilaiMahasiswa::where('tugas_id', $request['tugas_id'])->where('nrp', $user['username'])->first();
		$tugas = $etugas_find ?  :  new NilaiMahasiswa;
		$tugas->nrp = $user['username'];
		$tugas->keterangan = $request['keterangan'];
		$tugas->tugas_id = $request['tugas_id'];
		$tugas->kelas = $request['kelas_id'];
		if ($file) {
			$tugas->file_url = $file ? $file['original_path'] : null;
			$tugas->file_size = $file ? $file['original_size'] : null;
			$tugas->file_type = $file ? $file['original_extension'] : null;
		}
		$tugas->created_at = Carbon::now();
		$tugas->updated_at = Carbon::now();
		$tugas->save();
		DB::commit();

		$student = Mahasiswa::find($user['id']);
		$list_tugas = Tugas::getDataTugas($student->kelas)->get();
		$list_tugas = $list_tugas->map(function ($item) use($user) {
			$item['nilai_mahasiswa'] = NilaiMahasiswa::where('tugas_id', $item->id)->where('nrp', $user['username'])->first() ? : null;
    		return $item;
		});

		return response()->json(
			[
				'code' => 200,
				'status' => 'Data saved',
				'data_tugas' => $list_tugas
			]
		);
	}
}
