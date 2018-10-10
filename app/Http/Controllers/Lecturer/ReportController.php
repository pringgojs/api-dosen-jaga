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

	public function detail(Request $request)
	{
		$user = $request->input('user');
		$master_modul_id = $request->input('id_modul');
		/**
		 * 1. Data tugas (rata-rata)
		 */
		$table_headers = ['NIM', 'Nama'];
		$nilai_master_modul = NilaiMasterModul::find($master_modul_id);
		
		$list_etugas_by_master_modul = Tugas::where('nilai_master_modul', $master_modul_id)->where('pegawai', $user['id'])->get();
		foreach ($list_etugas_by_master_modul as $row =>  $etugas) {
			array_push($table_headers, 'Tugas '.++$row);
		}
		array_push($table_headers, 'Rata-rata');

		$list_mahasiswa = $nilai_master_modul->toKuliah->toKelas->mahasiswa;
		
		$kelas_mahasiswa = $list_mahasiswa->map(function ($item) use ($list_etugas_by_master_modul) {
			$rata = 0;
			foreach ($list_etugas_by_master_modul as $row => $etugas) {
				$nilai = NilaiMahasiswa::where('tugas_id', $etugas->id)->where('nrp', $item->nrp)->first();
				$item['tugas_'.++$row] =  $nilai ? $nilai->nilai : 0 ;
				$rata += $nilai ? $nilai->nilai : 0;
			}

			$item['rata_rata'] = $rata/$list_etugas_by_master_modul->count();
			return $item;
		});
		
		
		return response()->json(
			[
				'table_headers' => $table_headers,
				'nilai_master_modul' => $nilai_master_modul,
				'list_etugas_by_master_modul' => $list_etugas_by_master_modul,
				'kelas_mahasiswa' => $kelas_mahasiswa,
				'mata_kuliah' => $nilai_master_modul->toKuliah->mataKuliah
			]
		);
	}
}
