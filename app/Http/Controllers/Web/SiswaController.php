<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


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
