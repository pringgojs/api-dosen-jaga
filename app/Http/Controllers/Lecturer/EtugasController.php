<?php namespace App\Http\Controllers\Lecturer;

use App\Models\Tugas;
use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\NilaiModul;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EtugasController extends Controller {

	public function index(Request $request)
	{
		$user = $request->input('user');
		$list_semester = Kuliah::semester()->get();
		$list_tugas = Tugas::joinNilaiMasterModul()
			->joinDependence($user['id'])
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC')
			->where('nilai_master_modul.pengasuh', $user['id'])
			->get();
		
		return response()->json(
			[
				'list_semester' => $list_semester,
				'list_tugas' => $list_tugas,
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
		
		$tugas = Tugas::find($id);
		$list_semester = Kuliah::semester()->get();
		$list_kelas = Kuliah::joinKelas()->select(['kelas.*'])->where('kuliah.tahun', $tugas->toKuliah->tahun)->where('kuliah.semester', $tugas->toKuliah->semester)->groupBy('kuliah.kelas')->get();
		$list_matakuliah = Kuliah::joinMatakuliah()->select(['matakuliah.*'])->where('kuliah.tahun', $tugas->toKuliah->tahun)->where('kuliah.semester', $tugas->toKuliah->semester)->where('kuliah.kelas', $tugas->toKuliah->kelas)->groupBy('kuliah.matakuliah')->get();
		$list_modul = NilaiMasterModul::where('kuliah', $tugas->kuliah)->where('pengasuh', $tugas->pegawai)->get();
		$data =  NilaiMasterModul::getDataBySemester($user['id'], $tugas->toKuliah)->get();
		return response()->json(
			[
				'list_semester' => $list_semester,
				'list_kelas' => $list_kelas,
				'list_matakuliah' => $list_matakuliah,
				'list_modul' => $list_modul,
				'kuliah' =>  $tugas->toKuliah,
				'tugas' =>  $tugas,
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
			->get();

		return $list_tugas;
	}

	public function detail(Request $request, $id)
	{
		$user = $request->input('user');
		$etugas = Tugas::find($id);
		$list_mahasiswa = NilaiModul::joinMahasiswa()->where('nilai_modul.kuliah', $etugas->kuliah)->select('nilai_modul.mahasiswa')->select(['mahasiswa.*'])->get();
		$kelas_mahasiswa = $list_mahasiswa->map(function ($item) use ($etugas) {
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
		$nilai_mahasiswa->save();

		DB::commit();
		$tugas = $nilai_mahasiswa->tugas;
		$list_mahasiswa = NilaiModul::joinMahasiswa()->where('nilai_modul.kuliah', $tugas->kuliah)->select('nilai_modul.mahasiswa')->select(['mahasiswa.*'])->get();
		$kelas_mahasiswa = $list_mahasiswa->map(function ($item) use ($tugas) {
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
