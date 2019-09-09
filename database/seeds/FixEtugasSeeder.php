<?php

use App\Models\Tugas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class FixEtugasSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$list_tugas = Tugas::all();
		foreach ($list_tugas as $tugas) {
			$tugas->program = $tugas->toKelas->program;
			$tugas->jurusan = $tugas->toKelas->jurusan;
			$tugas->matakuliah = $tugas->nilaiMasterModul->toKuliah->matakuliah;
			$tugas->save();
			echo $tugas->id;
		}
	}

}
