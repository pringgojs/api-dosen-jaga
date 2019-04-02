<?php namespace App\Http\Controllers\Lecturer;

use Carbon\Carbon;
use App\Models\Tugas;
use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Mahasiswa;
use App\Models\NilaiModul;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiMasterModul;
use App\Models\NilaiModulDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller {

	public function nilaiPermodul(Request $request)
	{
		$user = $request->input('user');
		$user = $request->input('user');
		$list_semester = Kuliah::semester()->get();
		$kuliah = Tugas::where('pegawai', $user['id'])->orderBy('kuliah', 'DESC')->groupBy('kuliah')->max('kuliah');
		if (!$kuliah) return [];
		
		$list_tugas = Tugas::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->where('nilai_master_modul.kuliah', $kuliah)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->where('nilai_master_modul.pengasuh', $user['id'])
			->get();
		$list_tugas = $list_tugas->map(function ($item) use($kuliah, $user ){
			$item['total_tugas'] = Tugas::where('nilai_master_modul', $item->nomor_nilai_master_modul)->where('kuliah', $kuliah)->where('pegawai', $user['id'])->count();
			return $item;
		});
		
		return response()->json(
			[
				'list_semester' => $list_semester,
				'list_tugas' => $list_tugas,
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

		$list_tugas = Tugas::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->where('nilai_master_modul.kuliah', $kuliah->nomor)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->where('nilai_master_modul.pengasuh', $user['id'])
			->groupBy('nilai_master_modul.nomor')
			->get();

		$list_tugas = $list_tugas->map(function ($item) use($kuliah, $user ){
			$item['total_tugas'] = Tugas::where('nilai_master_modul', $item->nomor_nilai_master_modul)->where('kuliah', $kuliah->nomor)->where('pegawai', $user['id'])->count();
			return $item;
		});
		
		return $list_tugas;
	}

	public function detail(Request $request)
	{
		$user = $request->input('user');
		$master_modul_id = $request->input('id_modul');
		$table_headers = ['NIM', 'Nama'];
		$nilai_master_modul = NilaiMasterModul::find($master_modul_id);
		
		$list_etugas_by_master_modul = Tugas::where('nilai_master_modul', $master_modul_id)->where('pegawai', $user['id'])->get();
		foreach ($list_etugas_by_master_modul as $row =>  $etugas) {
			array_push($table_headers, 'Tugas '.++$row);
		}
		array_push($table_headers, 'Rata-rata');
		
		$list_mahasiswa = NilaiModul::joinMahasiswa()->where('nilai_modul.kuliah', $nilai_master_modul->kuliah)->select('nilai_modul.mahasiswa')->select(['mahasiswa.*'])->get();
		$kelas_mahasiswa = $list_mahasiswa->map(function ($item) use ($list_etugas_by_master_modul) {
			$rata = 0;
			foreach ($list_etugas_by_master_modul as $row => $etugas) {
				$nilai = NilaiMahasiswa::where('tugas_id', $etugas->id)->where('nrp', $item->nrp)->first();
				$item['tugas_'.++$row] =  $nilai ? $nilai->nilai : 0 ;
				$rata += $nilai ? $nilai->nilai : 0;
			}

			$item['rata_rata'] = $rata / $list_etugas_by_master_modul->count();
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

	public function sync(Request $request)
	{
		$user = $request->input('user');
		$master_modul_id = $request->input('id_modul');

		$nilai_master_modul = NilaiMasterModul::find($master_modul_id);
		$list_nilai_modul = NilaiModul::where('kuliah', $nilai_master_modul->kuliah)->get();
		$list_etugas_by_master_modul = Tugas::where('nilai_master_modul', $master_modul_id)->where('pegawai', $user['id'])->get();
		
		// header table response
		$table_headers = ['NIM', 'Nama'];
		foreach ($list_etugas_by_master_modul as $row =>  $etugas) {
			array_push($table_headers, 'Tugas '.++$row);
		}
		array_push($table_headers, 'Rata-rata');


		// insert nilai to nilai_modul_detil
		foreach ($list_nilai_modul as $nilai_modul) {
			$mahasiswa = Mahasiswa::find($nilai_modul->mahasiswa);
			$rata = 0;

			foreach ($list_etugas_by_master_modul as $etugas) {
				$nilai = NilaiMahasiswa::where('tugas_id', $etugas->id)->where('nrp', $mahasiswa->nrp)->first();
				$nilai = $nilai ? $nilai->nilai : 0 ;
				$rata += $nilai;
			}

			$nilai_modul_detail = NilaiModulDetail::where('nilai_modul', $nilai_modul->nomor)->where('nilai_master_modul', $nilai_master_modul->nomor)->first();
			if ($nilai_modul_detail) {
				$nilai_modul_detail->n = $rata;
				$nilai_modul_detail->save();
			}

		};
		
		// update last save in nilai master modul
		$nilai_master_modul->last_save = Carbon::now()->toDateTimeString();
		$nilai_master_modul->save();

		// data table for response
		$list_mahasiswa = NilaiModul::joinMahasiswa()->where('nilai_modul.kuliah', $nilai_master_modul->kuliah)->select('nilai_modul.mahasiswa')->select(['mahasiswa.*'])->get();
		$kelas_mahasiswa = $list_mahasiswa->map(function ($item) use ($list_etugas_by_master_modul) {
			$rata = 0;
			foreach ($list_etugas_by_master_modul as $row => $etugas) {
				$nilai = NilaiMahasiswa::where('tugas_id', $etugas->id)->where('nrp', $item->nrp)->first();
				$item['tugas_'.++$row] =  $nilai ? $nilai->nilai : 0 ;
				$rata += $nilai ? $nilai->nilai : 0;
			}

			$item['rata_rata'] = $rata / $list_etugas_by_master_modul->count();
			return $item;
		});

		return response()->json([
				'table_headers' => $table_headers,
				'nilai_master_modul' => $nilai_master_modul,
				'list_etugas_by_master_modul' => $list_etugas_by_master_modul,
				'kelas_mahasiswa' => $kelas_mahasiswa,
				'mata_kuliah' => $nilai_master_modul->toKuliah->mataKuliah
		]);
	}
}
