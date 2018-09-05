<?php namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
