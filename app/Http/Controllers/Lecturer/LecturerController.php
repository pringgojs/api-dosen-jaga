<?php namespace App\Http\Controllers\Lecturer;

use App\Http\Requests;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LecturerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$user = $request->input('user');
		$lecturer = Pegawai::find($user['id']);
		return response()->json(
			[
				'code' => 200,
				'user' => $user,
				'lecturer' => $lecturer,
				'program_study' => $lecturer->programStudy,
			]
		);
	}
}
