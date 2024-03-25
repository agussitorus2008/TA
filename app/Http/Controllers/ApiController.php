<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\Siswa;
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


    public function addSekolah(Request $request)
    {
        try {
            $sekolah = new Sekolah();
            do {
                $randomId = Str::random(10);
            } while (Sekolah::where('id', $randomId)->exists());

            $sekolah->id = $randomId;
            $sekolah->sekolah = $request->asal_sekolah;
            $sekolah->propinsi = $request->provinsi_sekolah;
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