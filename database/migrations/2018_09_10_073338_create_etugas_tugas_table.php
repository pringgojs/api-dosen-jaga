<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtugasTugasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('etugas_tugas', function (Blueprint $table) {
			$table->increments('id');
			$table->string('judul');
			$table->text('keterangan')->nullable();
            $table->integer('nilai_master_modul')->unsigned()->index('et_tugas_index')->nullable();
            $table->foreign('nilai_master_modul', 'et_tugas_foreign')
                ->references('id')->on('nilai_master_modul')
                ->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('kelas')->unsigned()->index('etugas_tkelas_index')->nullable();
            $table->foreign('kelas', 'etugas_tkelas_foreign')
                ->references('id')->on('kelas')
                ->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('pegawai')->unsigned()->index('etugas_tdosen_index')->nullable();
            $table->foreign('pegawai', 'etugas_tdosen_foreign')
                ->references('id')->on('pegawai')
                ->onUpdate('cascade')
				->onDelete('cascade');
            $table->string('file_url')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_size')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('due_date');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('etugas_tugas');
	}

}
