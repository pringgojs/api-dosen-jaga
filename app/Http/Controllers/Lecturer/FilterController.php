<?php namespace App\Http\Controllers\Lecturer;

use App\Models\Kelas;
use App\Models\Nilai;

use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\KelasSemester;
use App\Models\NilaiMasterModul;
use App\Http\Controllers\Controller;

class FilterController extends Controller {

	public function getSemester()
	{
		return Kuliah::semester()->get();
	}

	public function getKelas(Request $request)
	{
		$user = $request->input('user');
		$semester = $request->input('semester');
		$tahun = $request->input('tahun');
		return Kuliah::joinKelas()
			->select('kelas.nomor', 'kelas.kode')
			->where('kuliah.tahun', $tahun)
			->where('kuliah.semester', $semester)
			->where(function ($q) use ($user) {
				foreach (Kuliah::listDosen() as $dosen) {
					$q->orWhere($dosen, $user['id']);
				}
			})
			->groupBy('kelas.nomor')
			->groupBy('kelas.kode')
			->get();
	}

	public function getJurusan(Request $request)
	{
		$user = $request->input('user');
		$semester = $request->input('semester');
		$tahun = $request->input('tahun');
		$list_jurusan = Kuliah::joinKelas()
			->where('kuliah.tahun', $tahun)
			->where('kuliah.semester', $semester)
			->where(function ($q) use ($user) {
				foreach (Kuliah::listDosen() as $dosen) {
					$q->orWhere($dosen, $user['id']);
				}
			})
			->select('kelas.jurusan')
			->groupBy('kelas.jurusan')
			->get()->toArray();
		return Jurusan::whereIn('nomor', $list_jurusan)->get();
	}

	public function getMatakuliah(Request $request)
	{
		$user = $request->input('user');
		$request = $request->input('request');
		$semester = explode('/', $request['semester']);
		$program = $request['program'];
		$jurusan = $request['jurusan'];
		$pararel = $request['pararel'];
		$semester_tempuh = $request['semester_tempuh'];
		$kelas_semester = KelasSemester::where('semester', $semester_tempuh)->first();
		$kelas_semester = $kelas_semester ? $kelas_semester->kelas : 0;
		$kelas = Kelas::where('program', $program)
			->where('jurusan', $jurusan)
			->where('kelas', $kelas_semester)
			->where('pararel', $pararel)
			->first();
		$kelas = $kelas ? $kelas->nomor : 0;

		$list_matakuliah = Kuliah::joinMatakuliah()
			->select('matakuliah.nomor', 'matakuliah.matakuliah')
			->where('kuliah.tahun', $semester[0])
			->where('kuliah.semester', $semester[1])
			->where('kuliah.kelas', $kelas)
			->where(function ($q) use ($user) {
				foreach (Kuliah::listDosen() as $dosen) {
					$q->orWhere($dosen, $user['id']);
				}
			})
			->groupBy('matakuliah.nomor')
			->groupBy('matakuliah.matakuliah')
			->get();
		
		return [
			'list_matakuliah' => $list_matakuliah,
			'kelas' => $kelas,
		];
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
