<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Tugas;
use App\Models\Kuliah;
use App\Models\NilaiMasterModul;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtugasController extends Controller {

	public function index()
	{
		//
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
		$tugas->due_date = $request['tanggal'] . ' '. $request['waktu'];
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

}
