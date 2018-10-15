<?php namespace App\Http\Controllers\Student;

use Carbon\Carbon;
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
		$list_semester = Kuliah::semester()->get();
		$kuliah = NilaiModul::where('mahasiswa', $student->nomor)->max('kuliah');
		$kuliah = Kuliah::find($kuliah);
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
		$student = Mahasiswa::find($user['id']);
		
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
