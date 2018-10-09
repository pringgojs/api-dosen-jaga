<?php namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Tugas;
use App\Http\Requests;
use App\Models\Kuliah;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiMasterModul;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller {

	public function index(Request $request)
	{
		$user = $request->input('user');
		$student = Mahasiswa::find($user['id']);
		$list_tugas = Tugas::getDataTugas($student->kelas)->get();
		$list_semester = Tugas::getDataByKuliah($student->kelas)->get();
		$list_tugas = $list_tugas->map(function ($item) use($user) {
			$item['nilai_mahasiswa'] = NilaiMahasiswa::where('tugas_id', $item->id)->where('nrp', $user['username'])->first() ? : null;
			if (!$item['nilai_mahasiswa']) return $item;
		});
		\Log::info($list_tugas );
		return response()->json(
			[
				'data' => $list_tugas,
				'data_semester' => $list_semester,
			]
		);
	}
}
