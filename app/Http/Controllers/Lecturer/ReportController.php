<?php namespace App\Http\Controllers\Lecturer;

use App\Models\Tugas;
use App\Http\Requests;
use App\Models\Kuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller {

	public function nilaiPermodul(Request $request)
	{
		$user = $request->input('user');
		$list_semester = Tugas::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();
		return response()->json(
			[
				'code' => 200,
				'data_semester' => $list_semester,
			]
		);
	}
	
	public function kuliah(Request $request)
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
			->groupBy('nilai_master_modul.nomor')
			->get();
		$list_tugas = $list_tugas->map(function ($item) use($kuliah, $user ){
			$item['total_tugas'] = Tugas::where('nilai_master_modul', $item->nomor_nilai_master_modul)->where('kuliah', $kuliah)->where('pegawai', $user['id'])->count();
			return $item;
		});
		return response()->json(
			[
				'code' => 200,
				'data' => $list_tugas,
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
