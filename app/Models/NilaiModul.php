<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiModul extends Model 
{
	protected $table = 'nilai_modul';   
	protected $primaryKey = 'nomor';
	public $timestamps = false;
}
