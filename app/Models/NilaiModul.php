<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiModul extends Model 
{
	protected $table = 'nilai_modul';   
	protected $primaryKey = 'nomor';
	public $timestamps = false;

	public function scopeJoinNilaiModulDetail($q)
    {
        $q->join('nilai_modul_detil', 'nilai_modul_detil.nilai_modul', '=', 'nilai_modul.nomor');
	}

	public function scopeJoinMahasiswa($q)
    {
        $q->join('mahasiswa', 'mahasiswa.nomor', '=', 'nilai_modul.mahasiswa');
	}
}
