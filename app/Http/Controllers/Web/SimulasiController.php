<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SimulasiController extends Controller
{
    public function index()
    {
        return view('app.siswa.simulasi.main');
    }
}
