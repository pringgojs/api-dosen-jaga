<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLastSaveNilaiMasterModulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        if (!Schema::hasColumn('nilai_master_modul', 'last_save')) {
            Schema::table('nilai_master_modul', function($table) {
                $table->timestamp('last_save')->nullable();
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
        Schema::table('nilai_master_modul', function($table) {
            $table->dropColumn('last_save');
        });
    }

}
