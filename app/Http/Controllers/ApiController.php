<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\TSiswa;
use App\Models\TNilaiTo;
use App\Models\Prodi;
use App\Models\TTo;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function getProvinces($asalSekolah)
    {
        try {
            $provinces = Sekolah::where('id', $asalSekolah)->get('propinsi');
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
        $active = $request->input('active');

        $siswaList = TSiswa::with('sekolah_siswa')
            ->where('first_name', 'like', "%$query%")
            ->where('active', '=', $active)
            ->paginate(20);

        // Initialize an empty array to store status
        $statusList = [];

        // Loop through each siswa in the paginated results
        foreach ($siswaList as $siswa) {
            // Count the number of TNilaito entries for this username
            $nilaitoCount = TNilaito::where('username', $siswa->username)->count();

            // Determine status based on the count
            if ($nilaitoCount > 0) {
                $statusList[$siswa->username] = "Sudah Ada Nilai Tryout";
            } else {
                $statusList[$siswa->username] = "Belum Ada Nilai Tryout";
            }

            // Add status to the siswa object (optional, if needed in frontend)
            $siswa->status = $statusList[$siswa->username];
        }

        // Return response as JSON
        return response()->json(['siswaList' => $siswaList]);
    }


    public function filter(Request $request)
    {
        $query = $request->input('active');
        $siswaList = TSiswa::with('sekolah_siswa')->where('active', $query)->paginate(20);
        $statusList = [];

        // Loop through each siswa in the paginated results
        foreach ($siswaList as $siswa) {
            $nilaitoCount = TNilaito::where('username', $siswa->username)->count();

            // Determine status based on the count
            if ($nilaitoCount > 0) {
                $statusList[$siswa->username] = "Sudah Ada Nilai Tryout";
            } else {
                $statusList[$siswa->username] = "Belum Ada Nilai Tryout";
            }

            // Add status to the siswa object (optional, if needed in frontend)
            $siswa->status = $statusList[$siswa->username];
        }

        return response()->json(['siswaList' => $siswaList]);
    }

    public function search_tryout(Request $request)
    {
        $query = $request->input('query');
        $active = $request->input('active');

        $siswaList = TTo::where('nama_to', 'like', "%$query%")
        ->where('active', '=', $active)
        ->paginate(20);

        $statusList = [];

        foreach ($siswaList as $to) {
            $nilai_to_count = TNilaiTo::where('id_to', $to->id_to)->count();
            if ($nilai_to_count > 0) {
                $statusList[$to->id_to] = true;
            } else {
                $statusList[$to->id_to] = false;
            }

            $to->status = $statusList[$to->id_to];
        }

        return response()->json(['siswaList' => $siswaList]);
    }

    public function filter_tryout(Request $request)
    {
        $query = $request->input('active');
        $siswaList = TTo::where('active', $query)->paginate(20);

        $statusList = [];

        foreach ($siswaList as $to) {
            $nilai_to_count = TNilaiTo::where('id_to', $to->id_to)->count();

            $statusList[$to->id_to] = $nilai_to_count > 0;

            $to->status = $statusList[$to->id_to];
        }

        return response()->json(['siswaList' => $siswaList]);
    }
  
    public function getTanggalTryout(Request $request)
    {
        $query = $request->input('nama_to');
        $active = $request->input('active');

        $tryout = TTo::where('nama_to', $query)
            ->where('active', $active)
            ->first();
    
        return response()->json(['tanggal' => $tryout->tanggal]);
    }    

}