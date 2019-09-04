<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnETugasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasColumn('etugas_tugas', 'program')) {
            Schema::table('etugas_tugas', function($table) {
                $table->integer('program')->nullable();
                $table->integer('jurusan')->nullable();
                $table->integer('matakuliah')->nullable();
                $table->integer('dosen')->nullable();
            });
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('etugas_tugas', function($table) {
            $table->dropColumn('program');
            $table->dropColumn('jurusan');
            $table->dropColumn('matakuliah');
            $table->dropColumn('dosen');
        });
	}

}
