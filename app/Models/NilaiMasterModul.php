<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMasterModul extends Model 
{
	protected $table = 'nilai_master_modul';   
	protected $primaryKey = 'nomor';
	public $timestamps = true;

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
				'nilai_master_modul.nomor as nomor_nilai_master_modul'
			]);
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
	
	public function scopeJoinMahasiswa($q)
    {
        $q->join('mahasiswa', 'mahasiswa.kelas', '=', 'kelas.nomor');
	}
	

	public function scopeGetDataBySemester($q, $dosen_id, $kuliah_id)
    {
        $q->joinDependence($dosen_id)
			->where('nilai_master_modul.kuliah', $kuliah_id)
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC');
    }

}
