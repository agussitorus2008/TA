<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;

class ApiController extends Controller
{
    public function getProvinces($asalSekolah)
{
    try {
        $provinces = Sekolah::where('sekolah', $asalSekolah)->get('propinsi');
        if (empty($provinces)) {
            return response()->json(['propinsi' => "Error: No data found"]);
        } else {
            return response()->json(['propinsi' => $provinces]);
        }
    } catch (\Exception $e) {
        return response()->json(['provinces' => "Error: " . $e->getMessage()]);
    }
}

}
