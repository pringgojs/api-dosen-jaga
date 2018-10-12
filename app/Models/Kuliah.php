<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuliah extends Model 
{
	protected $table = 'kuliah';   
	protected $primaryKey = 'nomor';
	public $timestamps = true;

	public static function listDosen()
	{
		return ['dosen', 'dosen2', 'dosen3', 'dosen4', 'dosen5', 'dosen6', 'dosen7', 'dosen8', 'dosen9', 'dosen10', 'dosen11'];
	}

	public function scopeSemester($q)
    {
        $q->select(\DB::raw("CONCAT(tahun,'/',semester ) AS semester"))->groupBy('tahun')->groupBy('semester')->orderBy('semester', 'DESC');
	}

	public function mataKuliah()
    {
        return $this->belongsTo('App\Models\MataKuliah', 'matakuliah');
	}

	public function toKelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'kelas');
	}

	public function scopeJoinKelas($q)
    {
        $q->join('kelas', 'kelas.nomor', '=', 'kuliah.kelas');
	}

	public function scopeJoinMatakuliah($q)
    {
        $q->join('matakuliah', 'matakuliah.nomor', '=', 'kuliah.matakuliah');
	}
}
