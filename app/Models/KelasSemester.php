<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasSemester extends Model {

	protected $table = 'kelas_semester';   
	protected $primaryKey = 'nomor';
	public $timestamps = false;
}
