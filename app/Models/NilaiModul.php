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

	public function scopeJoinKuliah($q)
    {
        $q->join('kuliah', 'kuliah.nomor', '=', 'nilai_modul.kuliah');
    }

	public function scopeJoinMahasiswa($q)
    {
        $q->join('mahasiswa', 'mahasiswa.nomor', '=', 'nilai_modul.mahasiswa');
	}

	public function scopeJoinKelas($q)
    {
        $q->join('kelas', 'kelas.nomor', '=', 'kuliah.kelas');
    }

    public function scopeJoinMateri($q)
    {
        $q->join('etugas_materi', 'etugas_materi.kuliah', '=', 'nilai_modul.kuliah');
    }
    
    public function scopeJoinTugas($q)
    {
        $q->join('etugas_tugas', 'etugas_tugas.kuliah', '=', 'nilai_modul.kuliah');
    }
    
    public function scopeSemester($q)
    {
        $q->select(\DB::raw("CONCAT(kuliah.tahun,'/',kuliah.semester ) AS semester"))->groupBy('kuliah.tahun')->groupBy('kuliah.semester')->orderBy('kuliah.semester', 'DESC');
	}
}
