<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtugasMateriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('etugas_materi', function (Blueprint $table) {
			$table->increments('id');
			$table->string('judul');
			$table->text('keterangan')->nullable();
            $table->integer('nilai_master_modul')->unsigned()->index('etugas_nilai_master_modl_index')->nullable();
            $table->foreign('nilai_master_modul', 'etugas_nilai_mter_modl_foreign')
                ->references('nomor')->on('nilai_master_modul')
                ->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('kelas')->unsigned()->index('etugas_kelas_index')->nullable();
            $table->foreign('kelas', 'etugas_kelas_foreign')
                ->references('nomor')->on('kelas')
                ->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('kuliah')->unsigned()->index('etugas_tkuliahx_index')->nullable();
            $table->foreign('kuliah', 'etugas_tkuliahx_foreign')
                ->references('nomor')->on('kuliah')
                ->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('pegawai')->unsigned()->index('materi_dosen_index')->nullable();
            $table->foreign('pegawai', 'materi_dosen_foreign')
                ->references('nomor')->on('pegawai')
                ->onUpdate('cascade')
				->onDelete('cascade');
            $table->string('file_url')->nullable();
            $table->string('file_type')->nullable();
			$table->string('file_size')->nullable();
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('etugas_materi');
	}

}
