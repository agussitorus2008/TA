<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getProvinces($asalSekolah)
    {
        $provinces = Sekolah::where('sekolah', $asalSekolah)->get('propinsi');
        return response()->json(['provinces' => $provinces]);
    }
}
