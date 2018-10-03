<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model 
{
	protected $table = 'etugas_tugas';   
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

	public function nilaiMahasiswa()
    {
        return $this->belongsTo('App\Models\NilaiMahasiswa', 'tugas_id');
	}

	public function toPegawai()
    {
        return $this->belongsTo('App\Models\Pegawai', 'pegawai');
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
				'etugas_tugas.judul',
				'etugas_tugas.keterangan',
				'etugas_tugas.file_url',
				'etugas_tugas.file_type',
				'etugas_tugas.file_size',
				'etugas_tugas.due_date',
				'etugas_tugas.id',
			]);
    }
    
	public function scopeJoinNilaiMasterModul($q)
    {
        $q->join('nilai_master_modul', 'nilai_master_modul.nomor', '=', $this->table.'.nilai_master_modul');
	}
	
	public function scopeJoinNilaiMahasiswa($q)
    {
        $q->join('etugas_nilai_mahasiswa', 'etugas_nilai_mahasiswa.tugas', '=', $this->table.'.id');
	}
	
	public function scopeJoinDosen($q)
    {
        $q->join('pegawai', 'pegawai.nomor', '=', 'nilai_master_modul.pengasuh');
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

	public function scopeGetDataTugas($q, $kelas, $kuliah="")
	{
		$q->joinNilaiMasterModul()
			->joinKuliah()
			->joinKelas()
			->joinJurusan()
			->joinProgram()
			->joinMatakuliah()
			->joinDosen()
			->where('etugas_tugas.kelas', $kelas)
			->whereRaw('kuliah.tahun = (SELECT max(tahun) from kuliah where kelas = '.$kelas.') and kuliah.semester = (SELECT max(semester) from kuliah where kelas = '.$kelas.')')
			->where(function($query) use ($kuliah) {
				if ($kuliah) {
					$query->where('etugas_tugas.kuliah', $kuliah);
				}
			})
			->select([
				'kuliah.tahun',
				'kuliah.semester',
				'kelas.pararel',
				'kelas.kelas',
				'program.program',
				'etugas_tugas.judul',
				'etugas_tugas.keterangan',
				'etugas_tugas.file_url',
				'etugas_tugas.due_date',
				'etugas_tugas.id',
				'pegawai.nama',
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

	public function scopeGetDataByKuliah($q, $kelas)
	{
		$q->joinNilaiMasterModul()
			->joinKuliah()
			->joinMatakuliah()
			->where('etugas_tugas.kelas', $kelas)
			->select([
				'matakuliah.matakuliah',
				'kuliah.tahun',
				'kuliah.semester',
				'etugas_tugas.kuliah'
			])
			->groupBy('etugas_tugas.kuliah');
	}
}
