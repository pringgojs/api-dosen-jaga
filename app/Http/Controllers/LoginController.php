<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function login(Request $request)
	{
		if ($request->input('type') == 'dosen') {
			
			
			return response()->json(
                [
                    'status' => 200,
                    'message' => 'dosen',
                ]
            );
		}
	}
}
