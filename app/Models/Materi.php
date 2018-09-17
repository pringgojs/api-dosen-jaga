<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model 
{
	protected $table = 'etugas_materi';   
	public $timestamps = false;

    public function scopeJoinDependence($q, $dosen_id)
    {
		$q->joinKuliah()->joinMatakuliah()->joinKelas()->joinJurusan()->joinProgram()
			->where('nilai_master_modul.pengasuh', $dosen_id)
			->select([
				'kuliah.tahun',
				'kuliah.semester',
				'matakuliah.matakuliah',
				'jurusan.jurusan',
				'program.program',
				'kelas.pararel',
				'kelas.kelas',
				'nilai_master_modul.kuliah',
				'nilai_master_modul.modul',
                'nilai_master_modul.nomor as nomor_nilai_master_modul',
				'etugas_materi.judul',
				'etugas_materi.keterangan',
				'etugas_materi.file_url',
				'etugas_materi.file_type',
				'etugas_materi.file_size',
				'etugas_materi.id',
			]);
    }
    
	public function scopeJoinNilaiMasterModul($q)
    {
        $q->join('nilai_master_modul', 'nilai_master_modul.nomor', '=', $this->table.'.nilai_master_modul');
    }
    
    public function scopeJoinKuliah($q)
    {
        $q->join('kuliah', 'kuliah.nomor', '=', 'nilai_master_modul.kuliah');
	}

	public function scopeJoinMataKuliah($q)
    {
        $q->join('matakuliah', 'matakuliah.nomor', '=', 'kuliah.matakuliah');
	}
	
	public function scopeJoinKelas($q)
    {
        $q->join('kelas', 'kelas.nomor', '=', 'kuliah.kelas');
	}
	
	public function scopeJoinJurusan($q)
    {
        $q->join('jurusan', 'jurusan.nomor', '=', 'kelas.jurusan');
	}
	
	public function scopeJoinProgram($q)
    {
        $q->join('program', 'program.nomor', '=', 'kelas.program');
	}
}
