<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMahasiswa extends Model 
{
	protected $table = 'etugas_nilai_mahasiswa';   
	public $timestamps = true;

	public function tugas()
    {
        return $this->belongsTo('App\Models\Tugas', 'tugas_id');
	}
}
