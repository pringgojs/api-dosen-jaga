<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuliah extends Model 
{
	protected $table = 'kuliah';   
	protected $primaryKey = 'nomor';
	public $timestamps = true;

	public function mataKuliah()
    {
        return $this->belongsTo('App\Models\MataKuliah', 'matakuliah');
	}
}
