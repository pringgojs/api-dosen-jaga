<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEMateriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasColumn('etugas_materi', 'program')) {
            Schema::table('etugas_materi', function($table) {
                $table->integer('program')->nullable();
                $table->integer('jurusan')->nullable();
                $table->integer('matakuliah')->nullable();
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
		Schema::table('etugas_materi', function($table) {
            $table->dropColumn('program');
            $table->dropColumn('jurusan');
            $table->dropColumn('matakuliah');
        });
	}

}
