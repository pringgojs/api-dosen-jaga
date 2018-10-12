<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class ResetDatabase extends Command
{
	protected $name = 'reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset database and seeding data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // drop sequence
        
        if (\App::environment('local')) {
            self::mysql();
            return true;
        }

        self::oracle();
        
    }

    public function oracle()
    {
        DB::beginTransaction();
        $sequences = DB::select('select * from user_sequences');
        foreach($sequences as $sequence) {
            $sequence_array = get_object_vars($sequence);
            DB::statement('DROP SEQUENCE '.$this->argument('db_name').'.'.$sequence_array['sequence_name']);
        }
        
        $array = array('JURUSAN', 'KELAS', 'KULIAH', 'MAHASISWA', 'MATAKULIAH', 'NILAI_MASTER_MODUL', 'PEGAWAI', 'PROGRAM', 'PROGRAM_STUDY', 
            'NILAI_MODUL', 'NILAI_MODUL_DETAIL');
        foreach(DB::select('select * from tab') as $table) {
            $table_array = get_object_vars($table);
            if (!in_array($table_array['tname'], $array)) {
                DB::statement('DROP TABLE '.$table_array['tname'].' CASCADE CONSTRAINTS');
            }
        }
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true, '--class' => 'DatabaseSeeder']);
        DB::commit();
    }

    public function mysql()
    {
        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $array = array('jurusan', 'kelas', 'kuliah', 'mahasiswa', 'matakuliah', 'nilai_master_modul', 'pegawai', 'program', 'program_studi', 'nilai_modul', 'nilai_modul_detail');
        
        foreach(DB::select('SHOW TABLES') as $table) {
            $table_array = get_object_vars($table);
            if (!in_array($table_array[key($table_array)], $array)) {
                \Schema::drop($table_array[key($table_array)]);
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true, '--class' => 'DatabaseSeeder']);
        DB::commit();
    }
}
