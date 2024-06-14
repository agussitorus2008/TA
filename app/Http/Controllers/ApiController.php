<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Prodi;
use Illuminate\Support\Str;

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

    public function getProdiFromPTN($idPtn)
{
    try {
        $prodi = Prodi::where('id_ptn', $idPtn)->get();
        if ($prodi->isEmpty()) {
            return response()->json(['prodi' => []]);
        } else {
            return response()->json(['prodi' => $prodi]);
        }
    } catch (\Exception $e) {
        \Log::error('Error fetching prodi for PTN ' . $idPtn . ': ' . $e->getMessage());
        return response()->json(['prodi' => 'Error: ' . $e->getMessage()], 500);
    }
}




    public function addSekolah(Request $request)
    {
        try {
            do {
                $randomId = Str::random(10);
            } while (Sekolah::where('id', $randomId)->exists());
            
            $sekolah = new Sekolah();
            $sekolah->id = $randomId;
            $sekolah->sekolah = $request->sekolah;
            $sekolah->propinsi = $request->propinsi;
            $sekolah->save();

            return response()->json(['message' => 'Data sekolah berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function search(Request $request)
    {
        $query = $request->input('query');
        $siswaList = Siswa::where('first_name', 'like', "%$query%")->paginate(100);

        return response()->json(['siswaList' => $siswaList]);
    }

    public function filter(Request $request)
    {
        $query = $request->input('active');
        $siswaList = Siswa::where('active', $query)
            ->get();

        return response()->json(['siswaList' => $siswaList]);
    }


}