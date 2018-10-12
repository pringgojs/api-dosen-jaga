<?php

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UpdatePasswordSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        \DB::table('mahasiswa')->update(['password' => '121PRpnQMYV3k']);
        \DB::table('pegawai')->update(['password' => '121PRpnQMYV3k']);
	}

}
