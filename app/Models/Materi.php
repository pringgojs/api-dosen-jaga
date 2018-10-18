<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model 
{
	protected $table = 'etugas_materi';   
	public $timestamps = false;

	public function toKelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'kelas');
	}

	public function toKuliah()
    {
        return $this->belongsTo('App\Models\Kuliah', 'kuliah');
	}

	public function nilaiMasterModul()
    {
        return $this->belongsTo('App\Models\NilaiMasterModul', 'nilai_master_modul');
	}
	
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
	
	public function scopeJoinMahasiswa($q)
    {
        $q->join('mahasiswa', 'mahasiswa.kelas', '=', 'kuliah.kelas');
	}

	public function scopeJoinNilai($q)
    {
        $q->join('nilai', 'nilai.kuliah', '=', 'materi.kuliah');
	}

	public function scopeGetDataByKuliah($q, $kelas)
	{
		$q->joinNilaiMasterModul()
			->joinKuliah()
			->joinMatakuliah()
			->where('etugas_materi.kelas', $kelas)
			->select([
				'matakuliah.matakuliah',
				'kuliah.tahun',
				'kuliah.semester',
				'etugas_materi.kuliah'
			])
			->groupBy('etugas_materi.kuliah');
	}

	public function scopeGetDataMateri($q, $kelas, $kuliah="")
	{
		$q->joinNilaiMasterModul()
			->joinKuliah()
			->joinKelas()
			->joinJurusan()
			->joinProgram()
			->joinMatakuliah()
			->where('etugas_materi.kelas', $kelas)
			// ->whereRaw('kuliah.tahun = (SELECT max(tahun) from kuliah where kelas = '.$kelas.') and kuliah.semester = (SELECT max(semester) from kuliah where kelas = '.$kelas.')')
			->where(function($query) use ($kuliah) {
				if ($kuliah) {
					$query->where('etugas_materi.kuliah', $kuliah);
				}
			})
			->select([
				'kuliah.tahun',
				'kuliah.semester',
				'kelas.pararel',
				'kelas.kelas',
				'program.program',
				'etugas_materi.judul',
				'etugas_materi.keterangan',
				'etugas_materi.file_url',
				'jurusan.jurusan',
				'matakuliah.matakuliah',
				'nilai_master_modul.kuliah',
				'nilai_master_modul.modul',
				'nilai_master_modul.nomor as nomor_nilai_master_modul'
			])
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC');
	}

	public function scopeGetListMateri($q, $kuliah)
	{
		$q->joinNilaiMasterModul()
			->joinKuliah()
			->joinKelas()
			->joinJurusan()
			->joinProgram()
			->joinMatakuliah()
			// ->whereRaw('kuliah.tahun = (SELECT max(tahun) from kuliah where kelas = '.$kelas.') and kuliah.semester = (SELECT max(semester) from kuliah where kelas = '.$kelas.')')
			->where('etugas_materi.kuliah', $kuliah)
			->select([
				'kuliah.tahun',
				'kuliah.semester',
				'kelas.pararel',
				'kelas.kelas',
				'program.program',
				'etugas_materi.judul',
				'etugas_materi.keterangan',
				'etugas_materi.file_url',
				'jurusan.jurusan',
				'matakuliah.matakuliah',
				'nilai_master_modul.kuliah',
				'nilai_master_modul.modul',
				'nilai_master_modul.nomor as nomor_nilai_master_modul'
			])
			->orderBy('kuliah.tahun', 'DESC')
			->orderBy('kuliah.semester', 'DESC')
			->orderBy('nilai_master_modul.nomor', 'DESC');
	}
	
}
