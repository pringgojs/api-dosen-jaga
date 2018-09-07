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
				'nilai_master_modul.kuliah',
				'nilai_master_modul.modul',
				'nilai_master_modul.nomor as nomor_nilai_master_modul'
			]);
	}

	public function scopeJoinKuliah($q)
    {
        $q->join('kuliah', 'kuliah.nomor', '=', $this->table.'.kuliah');
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
