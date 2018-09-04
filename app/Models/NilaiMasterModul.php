<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMasterModul extends Model 
{
	protected $table = 'nilai_master_modul';   
	protected $primaryKey = 'nomor';
	public $timestamps = true;
}
