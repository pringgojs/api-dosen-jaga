<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model 
{
	protected $table = 'nilai';   
	protected $primaryKey = 'nomor';
    public $timestamps = false;
    
    public function toKuliah()
    {
        return $this->belongsTo('App\Models\Kuliah', 'kuliah');
    }
    
    public function toMahasiswa()
    {
        return $this->belongsTo('App\Models\Mahasiswa', 'mahasiswa');
    }
    
    public function scopeJoinKuliah($q)
    {
        $q->join('kuliah', 'kuliah.nomor', '=', 'nilai.kuliah');
    }
    
    public function scopeJoinKelas($q)
    {
        $q->join('kelas', 'kelas.nomor', '=', 'kuliah.kelas');
    }

    public function scopeJoinMateri($q)
    {
        $q->join('etugas_materi', 'etugas_materi.kuliah', '=', 'nilai.kuliah');
    }
    
    public function scopeJoinTugas($q)
    {
        $q->join('etugas_tugas', 'etugas_tugas.kuliah', '=', 'nilai.kuliah');
    }
    
    public function scopeSemester($q)
    {
        $q->select(\DB::raw("CONCAT(kuliah.tahun,'/',kuliah.semester ) AS semester"))->groupBy('kuliah.tahun')->groupBy('kuliah.semester')->orderBy('kuliah.semester', 'DESC');
	}
}
