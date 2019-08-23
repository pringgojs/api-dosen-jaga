<?php namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Pegawai;

class LoginController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function login(Request $request)
	{
		if ($request->input('type') == 'dosen') {
			$lecturer = AuthHelper::lecturerSignin($request);
			return response()->json(
                [
					'status' => $lecturer ? 1 : 0,
					'type' => 'lecturer',
                    'data' => $lecturer ? : 0,
                ]
            );
		}
		if ($request->input('type') == 'mahasiswa') {
			$student = AuthHelper::studentSignin($request);

			return response()->json(
                [
					'status' => $student ? 1 : 0,
					'type' => 'student',
                    'data' => $student ? : 0,
                ]
            );
		}
	}

	public function resetPassword(Request $request)
	{
		$req = $request->input('request');
		$user = $request->input('user');
		$password = crypt($req['password'], 1234567890);

		if ($user['type'] == 'student') {
			$student = Mahasiswa::find($user['id']);
			$student->password = $password;
			$student->save();
		}

		if ($user['type'] == 'lecturer') {
			$lecturer = Pegawai::find($user['id']);
			$lecturer->password = $password;
			$lecturer->save();
		}

		return response()->json(
			[
				'code' => 200,
				'data' => 'success'
			]
		);
		
	}
}
