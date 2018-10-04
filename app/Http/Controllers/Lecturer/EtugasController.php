<?php namespace App\Http\Controllers\Lecturer;

use App\Models\Tugas;
use App\Http\Requests;
use App\Models\Kuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EtugasController extends Controller {

	public function index(Request $request)
	{
		$user = $request->input('user');

		$list_tugas = Tugas::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->where('nilai_master_modul.pengasuh', $user['id'])
			->get();
		$list_semester = Tugas::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();
		return response()->json(
			[
				'code' => 200,
				'data' => $list_tugas,
				'data_semester' => $list_semester,
			]
		);
	}

	public function store(Request $request)
	{
		$user = $request->input('user');
		$file = $request->input('file');
		$request = $request->input('request');
		$nilai_master_modul = NilaiMasterModul::find($request['nilai_master_modul']);
		$kuliah = Kuliah::find($nilai_master_modul->kuliah);

		DB::beginTransaction();
		$tugas = new Tugas;
		$tugas->judul = $request['judul'];
		$tugas->keterangan = $request['keterangan'];
		$tugas->kuliah = $nilai_master_modul->kuliah;
		$tugas->kelas = $kuliah->kelas;
		$tugas->nilai_master_modul = $request['nilai_master_modul'];
		$tugas->pegawai = $nilai_master_modul->pengasuh;
		$tugas->file_url = $file ? $file['original_path'] : null;
		$tugas->file_size = $file ? $file['original_size'] : null;
		$tugas->file_type = $file ? $file['original_extension'] : null;
		$tugas->created_at = date('Y-m-d h:i:s');
		$tugas->due_date = $request['tanggal'] . ' '. $request['waktu'].':00';
		$tugas->save();

		DB::commit();

		return response()->json(
			[
				'code' => 200,
				'status' => 'Data saved',
			]
		);
	}

	public function edit(Request $request, $id)
	{
		$user = $request->input('user');
		$last_kuliah_user = NilaiMasterModul::where('nilai_master_modul.pengasuh', $user['id'])->max('nilai_master_modul.kuliah');
		$last_kuliah = Kuliah::max('nomor');
		$kuliah = Kuliah::find($last_kuliah_user);
		$list_semester = NilaiMasterModul::joinDependence($user['id'])
			->groupBy('nilai_master_modul.kuliah')
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();
		$tugas = Tugas::find($id);
		$data =  NilaiMasterModul::getDataBySemester($user['id'], $tugas->kuliah)->get();
		\Log::info($data);
		return response()->json(
			[
				'data_modul' => $data,
				'tugas' => $tugas,
				'data_semester' => $list_semester,
				'keterangan' => $last_kuliah == $last_kuliah_user ? 'saat ini' : 'semester lalu',
			]
		);
	}

	public function update(Request $request, $id)
	{
		$user = $request->input('user');
		$file = $request->input('file');
		$request = $request->input('request');
		$nilai_master_modul = NilaiMasterModul::find($request['nilai_master_modul']);
		$kuliah = Kuliah::find($nilai_master_modul->kuliah);

		DB::beginTransaction();
		$tugas = Tugas::find($id);
		$tugas->judul = $request['judul'];
		$tugas->keterangan = $request['keterangan'];
		$tugas->kuliah = $nilai_master_modul->kuliah;
		$tugas->kelas = $kuliah->kelas;
		$tugas->nilai_master_modul = $request['nilai_master_modul'];
		$tugas->pegawai = $nilai_master_modul->pengasuh;
		if ($file) {
			$tugas->file_url = $file ? $file['original_path'] : null;
			$tugas->file_size = $file ? $file['original_size'] : null;
			$tugas->file_type = $file ? $file['original_extension'] : null;
		}
		$tugas->created_at = date('Y-m-d h:i:s');
		$tugas->due_date = $request['tanggal'] . ' '. $request['waktu'].':00';
		$tugas->save();

		DB::commit();

		return response()->json(
			[
				'code' => 200,
				'status' => 'Data saved',
			]
		);
	}

	public function delete(Request $request, $id)
	{
		$tugas = Tugas::find($id);
		$url_file = $tugas->file_url;
		$tugas->delete();

		return response()->json(
			[
				'code' => 200,
				'url' => $url_file,
			]
		);
	}

	public function getByKuliah(Request $request)
	{
		$user = $request->input('user');
		$kuliah = $request->input('kuliah');
		$list_tugas = Tugas::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->where('nilai_master_modul.kuliah', $kuliah)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->where('nilai_master_modul.pengasuh', $user['id'])
			->get();
			
		$list_semester = Tugas::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->where('nilai_master_modul.kuliah', $kuliah)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();

		return response()->json(
			[
				'code' => 200,
				'data' => $list_tugas,
				'data_semester' => $list_semester,
			]
		);
	}

	public function detail(Request $request, $id)
	{
		$user = $request->input('user');
		$etugas = Tugas::find($id);
		$kelas_mahasiswa = $etugas->toKelas->mahasiswa->map(function ($item) use ($etugas) {
			$item['nilai'] = NilaiMahasiswa::where('tugas_id', $etugas->id)->where('nrp', $item['nrp'])->first();
			return $item;
		});
		return response()->json(
			[
				'etugas' => $etugas,
				'etugas_kelas' => $etugas->toKelas,
				'etugas_kelas_mahasiswa' => $kelas_mahasiswa,
				'etugas_kuliah' => $etugas->toKuliah,
				'etugas_pegawai' => $etugas->toPegawai,
				'etugas_matakuliah' => $etugas->toKuliah->mataKuliah,
				'etugas_modul' => $etugas->nilaiMasterModul,
				'etugas_nilai_mahasiswa' => $etugas->nilaiMahasiswa,
			]
		);
	}

	public function setNilai(Request $request)
	{
		$user = $request->input('user');
		$request = $request->input('request');
		DB::beginTransaction();

		$nilai_mahasiswa = NilaiMahasiswa::find($request['tugas_id']);

		$updated_at = $nilai_mahasiswa->updated_at;
		$nilai_mahasiswa->nilai = $request['nilai'];
		$nilai_mahasiswa->updated_at = $updated_at;
		$nilai_mahasiswa->save();

		DB::commit();
		$tugas = $nilai_mahasiswa->tugas;
		$kelas_mahasiswa = $tugas->toKelas->mahasiswa->map(function ($item) use ($tugas) {
			$item['nilai'] = NilaiMahasiswa::where('tugas_id', $tugas->id)->where('nrp', $item['nrp'])->first();
			return $item;
		});
		return response()->json(
			[
				'etugas' => $tugas,
				'etugas_kelas' => $tugas->toKelas,
				'etugas_kelas_mahasiswa' => $kelas_mahasiswa,
				'etugas_kuliah' => $tugas->toKuliah,
				'etugas_pegawai' => $tugas->toPegawai,
				'etugas_matakuliah' => $tugas->toKuliah->mataKuliah,
				'etugas_modul' => $tugas->nilaiMasterModul,
				'etugas_nilai_mahasiswa' => $tugas->nilaiMahasiswa,
			]
		);
	}
}
