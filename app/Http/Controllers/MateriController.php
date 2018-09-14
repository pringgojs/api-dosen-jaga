<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MateriController extends Controller {

	public function store(Request $request)
	{
		\Log::info($request->file('file'));
		if ($request->hasFile('file')) {
		}
	}

}
