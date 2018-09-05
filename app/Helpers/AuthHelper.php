<?php

namespace App\Helpers;

use App\Models\Pegawai;
use App\Models\Mahasiswa;
use Ixudra\Curl\Facades\Curl;
use App\Helpers\ResponseHelper;

class AuthHelper
{
    public static function lecturerSignin($request)
    {
        $password = crypt($request->input('password'), 1234567890);

        $lecturers = Pegawai::where('username', $request->input('username'))
            ->where('password', $password)
            ->where('staff', 4)
            ->first();

        if ($lecturers) return $lecturers;

        return 0;
    }

    public static function studentSignin($request)
    {
        $password = crypt($request->input('password'), 1234567890);

        $student = Mahasiswa::where('nrp', $request->input('username'))
            ->where('password', $password)
            ->where('status', 'A')
            ->first();
        
        if ($student) return $student;

        return 0;
    }
}