<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiModulDetail extends Model 
{
	protected $table = 'nilai_modul_detil';   
	protected $primaryKey = 'nomor';
	public $timestamps = false;
}
