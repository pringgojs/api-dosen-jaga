<?php namespace App\Http\Controllers\Lecturer;

use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Materi;
use Illuminate\Http\Request;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MateriController extends Controller {

	public function index(Request $request)
	{
		$user = $request->input('user');
		$list_semester = Kuliah::semester()->get();
		$list_materi = Materi::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->where('nilai_master_modul.pengasuh', $user['id'])
			->get();

		return response()->json(
			[
				'list_materi' => $list_materi,
				'list_semester' => $list_semester,
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
		$materi = new Materi;
		$materi->judul = $request['judul'];
		$materi->keterangan = $request['keterangan'];
		$materi->kuliah = $nilai_master_modul->kuliah;
		$materi->pegawai = $nilai_master_modul->pengasuh;
		$materi->kelas = $kuliah->kelas;
		$materi->nilai_master_modul = $request['nilai_master_modul'];
		$materi->file_url = $file ? $file['original_path'] : null;
		$materi->file_size = $file ? $file['original_size'] : null;
		$materi->file_type = $file ? $file['original_extension'] : null;
		$materi->created_at = date('Y-m-d h:i:s');
		$materi->updated_at = date('Y-m-d h:i:s');
		$materi->save();

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
		
		$materi = Materi::find($id);
		$list_semester = Kuliah::semester()->get();
		$list_kelas = Kuliah::joinKelas()
			->select('kelas.nomor, kelas.kode')
			->where('kuliah.tahun', $materi->toKuliah->tahun)
			->where('kuliah.semester', $materi->toKuliah->semester)
			->groupBy('kelas.kode')
			->groupBy('kelas.nomor')
			->get();
		$list_matakuliah = Kuliah::joinMatakuliah()
			->select('matakuliah.nomor, matakuliah.matakuliah')
			->where('kuliah.tahun', $materi->toKuliah->tahun)
			->where('kuliah.semester', $materi->toKuliah->semester)
			->where('kuliah.kelas', $materi->toKuliah->kelas)
			->groupBy('kuliah.matakuliah')
			->groupBy('kuliah.nomor')
			->get();
		$list_modul = NilaiMasterModul::where('kuliah', $materi->kuliah)->where('pengasuh', $materi->pegawai)->get();
		$data =  NilaiMasterModul::getDataBySemester($user['id'], $materi->toKuliah)->get();
		return response()->json(
			[
				'list_semester' => $list_semester,
				'list_kelas' => $list_kelas,
				'list_matakuliah' => $list_matakuliah,
				'list_modul' => $list_modul,
				'kuliah' =>  $materi->toKuliah,
				'materi' =>  $materi,
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
		$materi = Materi::find($id);
		$materi->judul = $request['judul'];
		$materi->keterangan = $request['keterangan'];
		$materi->kuliah = $nilai_master_modul->kuliah;
		$materi->kelas = $kuliah->kelas;
		$materi->nilai_master_modul = $request['nilai_master_modul'];
		$materi->pegawai = $nilai_master_modul->pengasuh;
		if ($file) {
			$materi->file_url = $file ? $file['original_path'] : null;
			$materi->file_size = $file ? $file['original_size'] : null;
			$materi->file_type = $file ? $file['original_extension'] : null;
		}
		$materi->updated_at = date('Y-m-d h:i:s');
		$materi->save();

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
		$materi = Materi::find($id);
		$url_file = $materi->file_url;
		$materi->delete();

		return response()->json(
			[
				'code' => 200,
				'url' => $url_file,
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
		
		$list_materi = Materi::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->where('nilai_master_modul.kuliah', $kuliah->nomor)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->where('nilai_master_modul.pengasuh', $user['id'])
			->get();

		return $list_materi;
	}
}
