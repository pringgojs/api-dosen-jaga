<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model 
{
	protected $table = 'kelas';   
	protected $primaryKey = 'nomor';
	public $timestamps = true;

	public function toJurusan()
    {
        return $this->belongsTo('App\Models\Jurusan', 'jurusan');
	}

	public function toProgram()
    {
        return $this->belongsTo('App\Models\Program', 'program');
	}

	public function mahasiswa()
    {
        return $this->hasMany('App\Models\Mahasiswa', 'kelas');
	}
}
