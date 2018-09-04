<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model 
{
	protected $table = 'matakuliah';   
	protected $primaryKey = 'nomor';
	public $timestamps = true;
}
