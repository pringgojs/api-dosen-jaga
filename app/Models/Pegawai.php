<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model 
{
	protected $table = 'pegawai';   
	protected $primaryKey = 'nomor';
	public $timestamps = true;
}