<?php namespace App\Http\Controllers\Lecturer;

use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Materi;
use App\Models\Jurusan;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MateriController extends Controller {

	public function index(Request $request)
	{
		$user = $request->input('user');
		$list_semester = Kuliah::semester()->get();
		$list_program = Program::orderBy('program')->get();
		$list_jurusan = Jurusan::all();
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
				'list_jurusan' => $list_jurusan,
				'list_program' => $list_program,
			]
		);
	}

	public function create(Request $request)
	{
		$user = $request->input('user');
		$list_semester = Kuliah::semester()->get();
		$list_program = Program::orderBy('program')->get();
		$list_jurusan = Jurusan::all();
		
		return response()->json(
			[
				'list_semester' => $list_semester,
				'list_jurusan' => $list_jurusan,
				'list_program' => $list_program,
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
		$materi->program = $request['program'];
		$materi->jurusan = $request['jurusan'];
		$materi->matakuliah = $request['matakuliah'];
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
		$materi = Materi::where('id', $id)->with(['toKelas', 'toKuliah', 'toProgram', 'toProgram', 'toJurusan', 'toMatakuliah', 'toPegawai'])->first();
		$list_program = Program::orderBy('program')->get();
		$list_jurusan = Jurusan::all();
		$list_semester = Kuliah::semester()->get();
		
		// cek semester tempuh
		$semester_tempuh = ($materi->toKelas->kelas * 2 ) - 1;
		if (($materi->toKuliah->semester % 2) == 0) {
			$semester_tempuh = $materi->toKelas->kelas * 2;
		}

		$list_matakuliah = Kuliah::joinMatakuliah()
			->select('matakuliah.nomor', 'matakuliah.matakuliah')
			->where('kuliah.tahun', $materi->toKuliah->tahun)
			->where('kuliah.semester', $materi->toKuliah->semester)
			->where('kuliah.kelas', $materi->toKuliah->kelas)
			->where(function ($q) use ($user) {
				foreach (Kuliah::listDosen() as $dosen) {
					$q->orWhere($dosen, $user['id']);
				}
			})
			->groupBy('matakuliah.matakuliah')
			->groupBy('matakuliah.nomor')
			->get();
		
		$list_modul = NilaiMasterModul::where('kuliah', $materi->kuliah)->where('pengasuh', $materi->pegawai)->get();
		return response()->json(
			[
				'list_semester' => $list_semester,
				'list_matakuliah' => $list_matakuliah,
				'list_modul' => $list_modul,
				'list_program' => $list_program,
				'list_jurusan' => $list_jurusan,
				'kuliah' =>  $materi->toKuliah,
				'semester_tempuh' => $semester_tempuh,
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
		$materi->program = $request['program'];
		$materi->jurusan = $request['jurusan'];
		$materi->matakuliah = $request['matakuliah'];
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
