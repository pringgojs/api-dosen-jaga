<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model 
{
	protected $table = 'program';   
	protected $primaryKey = 'nomor';
	public $timestamps = true;
}
