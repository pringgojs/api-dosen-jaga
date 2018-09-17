<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtugasTugasMahasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('etugas_nilai_mahasiswa', function (Blueprint $table) {
			$table->increments('id');
			$table->string('nrp');
			$table->integer('nilai')->nullable()->unsigned();
			$table->text('keterangan')->nullable();
            $table->integer('tugas_id')->unsigned()->index('et_tugas_t_index')->nullable();
            $table->foreign('tugas_id', 'et_tugas_t_foreign')
                ->references('id')->on('etugas_tugas')
                ->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('kelas')->unsigned()->index('etugas_tkelasd_index')->nullable();
            $table->foreign('kelas', 'etugas_tkelasd_foreign')
                ->references('id')->on('kelas')
                ->onUpdate('cascade')
				->onDelete('cascade');
            $table->string('file_url')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_size')->nullable();
            $table->timestamp('created_at');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('etugas_nilai_mahasiswa');
	}

}
