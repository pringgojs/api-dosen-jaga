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
					'status' => 1,
					'type' => 'lecturer',
                    'data' => $lecturer ? : 0,
                ]
            );
		}

		if ($request->input('type') == 'mahasiswa') {
			$student = AuthHelper::studentSignin($request);
			return response()->json(
                [
					'status' => 1,
					'type' => 'student',
                    'data' => $student ? : 0,
                ]
            );
		}

		return response()->json(
			[
				'status' => 0,
			]
		);
	}
}
