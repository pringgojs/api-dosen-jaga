<?php namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Nilai;
use App\Models\Tugas;
use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Mahasiswa;
use App\Models\NilaiModul;
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
		$list_semester = NilaiModul::joinKuliah()->joinTugas()->semester()->where('nilai_modul.mahasiswa', $student->nomor)->get();
		$kuliah = NilaiModul::joinKuliah()->joinTugas()->select(['kuliah.*'])->where('nilai_modul.mahasiswa', $student->nomor)->groupBy('kuliah.nomor')->first();
		$list_tugas = Tugas::getDataTugas($kuliah->kelas, $kuliah->nomor)->get();
		$list_tugas = $list_tugas->map(function ($item) use($user) {
			$item['nilai_mahasiswa'] = NilaiMahasiswa::where('tugas_id', $item->id)->where('nrp', $user['username'])->first() ? : null;
    		return $item;
		});
		return response()->json(
			[
				'list_tugas' => $list_tugas,
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
		$kuliah = Kuliah::joinTugas()->select(['kuliah.*'])
			->where('kuliah.tahun', $semester[0])->where('kuliah.semester', $semester[1])->where('kuliah.kelas', $kelas)
			->where('kuliah.matakuliah', $matakuliah)
			->first();
		
		if (!$kuliah) return [];

		$list_tugas = Tugas::getDataTugas($kuliah->kelas, $kuliah->nomor)->get();
		$list_tugas = $list_tugas->map(function ($item) use($user) {
			$item['nilai_mahasiswa'] = NilaiMahasiswa::where('tugas_id', $item->id)->where('nrp', $user['username'])->first() ? : null;
    		return $item;
		});
		
		return $list_tugas;
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
		$nilai_mahasiswa = NilaiMahasiswa::where('tugas_id', $request['tugas_id'])->where('nrp', $user['username'])->first();
		$nilai_mahasiswa = $nilai_mahasiswa ?  :  new NilaiMahasiswa;
		$nilai_mahasiswa->nrp = $user['username'];
		$nilai_mahasiswa->keterangan = $request['keterangan'];
		$nilai_mahasiswa->tugas_id = $request['tugas_id'];
		$nilai_mahasiswa->kelas = $request['kelas_id'];
		if ($file) {
			$nilai_mahasiswa->file_url = $file ? $file['original_path'] : null;
			$nilai_mahasiswa->file_size = $file ? $file['original_size'] : null;
			$nilai_mahasiswa->file_type = $file ? $file['original_extension'] : null;
		}
		$nilai_mahasiswa->created_at = Carbon::now();
		$nilai_mahasiswa->updated_at = Carbon::now();
		$nilai_mahasiswa->save();
		DB::commit();
		
		$tugas = Tugas::find($request['tugas_id']);
		$list_tugas = Tugas::getDataTugas($tugas->toKuliah->kelas, $tugas->toKuliah->nomor)->get();
		$list_tugas = $list_tugas->map(function ($item) use($user) {
			$item['nilai_mahasiswa'] = NilaiMahasiswa::where('tugas_id', $item->id)->where('nrp', $user['username'])->first() ? : null;
    		return $item;
		});

		return response()->json(
			[
				'list_tugas' => $list_tugas
			]
		);
	}
}
