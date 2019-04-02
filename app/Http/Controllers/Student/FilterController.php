<?php namespace App\Http\Controllers\Student;

use App\Models\Nilai;
use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Materi;
use App\Models\NilaiModul;
use Illuminate\Http\Request;
use App\Models\NilaiMasterModul;
use App\Http\Controllers\Controller;

class FilterController extends Controller {

	public function getSemester($type)
	{
		if ($type == 'materi') return Nilai::joinMateri()->semester()->get();
		if ($type == 'tugas') return Nilai::joinTugas()->semester()->get();

		return Kuliah::semester()->get();
	}

	public function getKelas(Request $request)
	{
		$semester = $request->input('semester');
		$tahun = $request->input('tahun');
		$type = $request->input('type');
		$user = $request->input('user');
		if ($type == 'materi') {
			return NilaiModul::joinKuliah()
				->joinMateri()
				->joinKelas()
				->select('kelas.nomor', 'kelas.kode')
				->where('nilai_modul.mahasiswa', $user['id'])
				->groupBy('kelas.nomor')
				->groupBy('kelas.kode')
				->get();
		}

		if ($type == 'tugas') {
			return NilaiModul::joinKuliah()
				->joinTugas()
				->joinKelas()
				->select('kelas.nomor', 'kelas.kode')
				->where('nilai_modul.mahasiswa', $user['id'])
				->groupBy('kelas.nomor')
				->groupBy('kelas.kode')
				->get();
		}

		return [];
	}

	public function getMatakuliah(Request $request)
	{
		$user = $request->input('user');
		$request = $request->input('request');
		$semester = explode('/', $request['semester']);
		$kelas = $request['kelas'];
		return Kuliah::joinMatakuliah()
			->select('matakuliah.nomor', 'matakuliah.matakuliah')
			->where('kuliah.tahun', $semester[0])
			->where('kuliah.semester', $semester[1])
			->where('kuliah.kelas', $kelas)
			->groupBy('matakuliah.nomor')
			->groupBy('matakuliah.matakuliah')
			->get();
	}

	public function getModul(Request $request)
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
		return NilaiMasterModul::where('kuliah', $kuliah->nomor)->where('pengasuh', $user['id'])->get();
	}
}
