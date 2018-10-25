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
		// oracle env
		if (env('production')) {
			$q->select(\DB::raw("(tahun || '/' || semester ) AS semester"), 'tahun')
				->groupBy(\DB::raw("(tahun || '/' || semester )"))
				->groupBy('tahun')
				->orderBy('semester', 'DESC');
		} else {
			$q->select(\DB::raw("CONCAT(tahun,'/',semester ) AS semester"), 'tahun')
				->groupBy('tahun')
				->groupBy('semester')
				->orderBy('semester', 'DESC');
		}
	}

	public function scopeMatakuliah($q)
    {
		if (env('production')) {
			$q->select(\DB::raw("(tahun || '/' || semester ) AS semester"), 'tahun')
				->groupBy(\DB::raw("(tahun || '/' || semester )"))
				->groupBy('tahun')
				->orderBy('semester', 'DESC');
		} else {
			$q->select(\DB::raw("CONCAT(tahun,'/',semester ) AS semester"), 'tahun')
				->groupBy('tahun')
				->groupBy('semester')
				->orderBy('semester', 'DESC');
		}
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

	public function scopeJoinMateri($q)
    {
        $q->join('etugas_materi', 'etugas_materi.kuliah', '=', 'kuliah.nomor');
	}

	public function scopeJoinTugas($q)
    {
        $q->join('etugas_tugas', 'etugas_tugas.kuliah', '=', 'kuliah.nomor');
	}
}
