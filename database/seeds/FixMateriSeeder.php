<?php

use App\Models\Materi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class FixMateriSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$list_materi = Materi::all();
		foreach ($list_materi as $materi) {
			$materi->program = $materi->toKelas->program;
			$materi->jurusan = $materi->toKelas->jurusan;
			$materi->matakuliah = $materi->nilaiMasterModul->toKuliah->matakuliah;
			$materi->kuliah = $materi->nilaiMasterModul->kuliah;
			$materi->pegawai = $materi->nilaiMasterModul->pengasuh;
			$materi->save();
		}

		echo "1";
	}

}
