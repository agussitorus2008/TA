<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function profile()
    {
        return view('app.siswa.profile.profile');
    }

    public function index()
    {
        return view('app.siswa.main');
    }
}
