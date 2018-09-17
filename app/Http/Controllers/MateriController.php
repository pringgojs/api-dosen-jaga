<?php namespace App\Http\Controllers;

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
		$list_materi = Materi::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->where('nilai_master_modul.pengasuh', $user['id'])
			->get();
			
		$list_semester = Materi::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();

		return response()->json(
			[
				'code' => 200,
				'data' => $list_materi,
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
		$materi = new Materi;
		$materi->judul = $request['judul'];
		$materi->keterangan = $request['keterangan'];
		$materi->kuliah = $nilai_master_modul->kuliah;
		$materi->kelas = $kuliah->kelas;
		$materi->nilai_master_modul = $request['nilai_master_modul'];
		$materi->file_url = $file ? $file['original_path'] : null;
		$materi->file_size = $file ? $file['original_size'] : null;
		$materi->file_type = $file ? $file['original_extension'] : null;
		$materi->created_at = date('Y-m-d h:i:s');
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

	public function getByKuliah(Request $request)
	{
		$user = $request->input('user');
		$kuliah = $request->input('kuliah');
		$list_materi = Materi::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->where('nilai_master_modul.kuliah', $kuliah)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->where('nilai_master_modul.pengasuh', $user['id'])
			->get();
			
		$list_semester = Materi::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->where('nilai_master_modul.kuliah', $kuliah)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->get();
		\Log::info($list_materi);

		return response()->json(
			[
				'code' => 200,
				'data' => $list_materi,
				'data_semester' => $list_semester,
			]
		);
	}

}
