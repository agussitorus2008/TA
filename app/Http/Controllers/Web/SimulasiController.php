<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\PTN;
use App\Models\DayaTampung;
use App\Models\TSiswa;
use App\Models\Kelulusan;
use App\Models\TNilaito;
use App\Models\ViewNilaiFinal;
use App\Models\Recommendation;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessSimulasiProdi;
use App\Jobs\ProcessSimulasiPTN;

class SimulasiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::where('active', 2024)->get();

        $nilai = ViewNilaiFinal::where('username', auth()->user()->email)->first();
        $nilaiCount = TNilaito::where('username', auth()->user()->email)->count();
        if($nilai){
            $nilai = $nilai->average_to;
        }
        return view('app.siswa.simulasi.main', compact('prodi', 'nilai', 'nilaiCount'));
    }

    public function index_ptn()
    {
        $ptn = PTN::where('active', 10)->get();
        $nilai = ViewNilaiFinal::where('username', auth()->user()->email)->first();
        $nilaiCount = TNilaito::where('username', auth()->user()->email)->count();
        if($nilai){
            $nilai = $nilai->average_to;
        }

        return view('app.siswa.simulasi.ptn', compact('ptn', 'nilai', 'nilaiCount'));
    }

    public function index_prodi()
    {
        $prodi = PTN::where('active', 10)->get();
        $nilai = ViewNilaiFinal::where('username', auth()->user()->email)->first();
        $nilaiCount = TNilaito::where('username', auth()->user()->email)->count();
        if($nilai){
            $nilai = $nilai->average_to;
        }

        return view('app.siswa.simulasi.prodi', compact('prodi', 'nilai', 'nilaiCount'));
    }

    // simulasi ptn dan prodi
    public function prediksi(Request $request)
    {
        set_time_limit(1800);

        try {
        $kategori = "";
        $rekomendasiFinal = [];

        $prodi = $request->prodi;
        // dd($prodi);

        $daya_tampung = DayaTampung::where('id_prodi', $prodi)
        ->where('tahun', 2024)
        ->first();

        $ratarataNilai = Kelulusan::where('id_prodi', $prodi)
            ->where('active', 2023)
            ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->pluck('average_to')
            ->toArray();
        
        $listNilai = TSiswa::join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
        ->where('t_siswa.pilihan1_utbk', $prodi)
        ->orWhere('t_siswa.pilihan2_utbk', $prodi)
        ->pluck('view_rekapitulasi_nilai_to.average_to')
        ->toArray();

        if ($listNilai == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $nilai = ViewNilaiFinal::where('username', auth()->user()->email)->first()->average_to;
        // $nilai = 36.45447143;

        if(empty($nilai)){
            $errorMessage = "Anda Belum memiliki data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        if ($nilai <= 0) {
            $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
            return response()->json(['error' => $errorMessage], 422);
        }

        $listNilai[] = $nilai;

        arsort($listNilai);

        $peringkat = array_search($nilai, array_values($listNilai), true);
        $peringkat += 1;

        // dd($peringkat);

        $hasil = ($peringkat / count($listNilai));

        $rekomendasi = DB::table('view_rekapitulasi_nilai_to')
            ->join('kelulusan', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->where('view_rekapitulasi_nilai_to.average_to', '<=', $nilai)
            ->select('kelulusan.id_prodi')
            ->orderByDesc('view_rekapitulasi_nilai_to.average_to')
            ->get();
        
        $checkRekomendasi = DB::table('t_prodi')
            ->whereIn('t_prodi.id_prodi', $rekomendasi->pluck('id_prodi'))
            ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
            ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->select('t_prodi.id_prodi', DB::raw('avg(view_rekapitulasi_nilai_to.average_to) as average_total_nilai'))
            ->groupBy('t_prodi.id_prodi')
            ->get(); 

        foreach($checkRekomendasi as $check) {
            if($nilai >= $check->average_total_nilai) { 
                $rekomendasiFinal[] = $check->id_prodi;
            }
        }

        
        if(empty($rekomendasiFinal)) { 
            $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
            return response()->json(['error' => $errorMessage], 422);
        }

        $prodiIds = $rekomendasiFinal; 

        $prodiRekomendasi = Prodi::whereIn('t_prodi.id_prodi', $prodiIds)
            ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
            ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
            ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->select(
                't_prodi.id_prodi',
                't_prodi.nama_prodi',
                't_ptn.nama_ptn',
                't_ptn.nama_singkat',
                DB::raw('AVG(view_rekapitulasi_nilai_to.average_to) as average_total_nilai')
            )
            ->groupBy(
                't_prodi.id_prodi',
                't_prodi.nama_prodi',
                't_ptn.nama_ptn',
                't_ptn.nama_singkat'
            )
            ->orderByDesc('average_total_nilai')
            ->paginate(300);

        if ($hasil >= 1) {
            $kategori = "Macet Total";
        } elseif ($hasil >= 0.8) {
            $kategori = "Macet";
        } elseif ($hasil >= 0.5) {
            $kategori = "Dipertimbangkan";
        } else {
            $kategori = "Gas Pool";
        }

        return response()->json([
            'kategori' => $kategori,
            'peringkat' => $peringkat,
            'total_pendaftar' => count($listNilai),
            'nilai_rata' => count($ratarataNilai) > 0 ? array_sum($ratarataNilai) / count($ratarataNilai) : 0,
            'nilai_saya' => $nilai,
            'rekomendasi' => $prodiRekomendasi,
            'daya_tampung' => $daya_tampung->daya_tampung
        ]);  
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    
    }


    // simulasi ptn
    public function prediksi_ptn(Request $request){
        $idPTN = $request->ptn;
        $userEmail = auth()->user()->email;

        ProcessSimulasiPTN::dispatch($idPTN, $userEmail);

        return response()->json([
            'success' => true,
            'message' => 'Job has been dispatched and is processing in the background.',
        ]);
    }

    // simulasi prodi
    public function prediksi_prodi(Request $request){
        $namaProdi = $request->prodi;
        $userEmail = auth()->user()->email;

        ProcessSimulasiProdi::dispatch($namaProdi, $userEmail);

        return response()->json([
            'success' => true,
            'message' => 'Job has been dispatched and is processing in the background.',
        ]);
    }

    public function rekomendasi()
    {
        $userEmail = auth()->user()->email;
        $recommendation = Recommendation::where('email', $userEmail)->latest()->first();

        if ($recommendation) {
            return response()->json([
                'rekomendasi' => json_decode($recommendation->data),
            ]);
        }

        return response()->json([
            'error' => 'Recommendation not found.',
        ], 404);
    }



    // public function prediksi_ptn(Request $request)
    // {
    //     set_time_limit(1800);
        
    //     try {
    //         $rekomendasiFinal = [];
    //         $ptnId = $request->ptn;

    //         // Fetch prodi IDs related to the given PTN ID
    //         $prodiIds = Prodi::where('id_ptn', $ptnId)->pluck('id_prodi')->toArray();

    //         // Get the average score of the authenticated user
    //         $nilai = ViewNilaiFinal::where('username', auth()->user()->email)->value('average_to');
    //         // $nilai = 36.45447143;

    //         // If the user has no score data, return an error
    //         if (is_null($nilai)) {
    //             return response()->json(['error' => 'Anda Belum memiliki data nilai'], 422);
    //         }

    //         // If the score is zero or negative, return an error
    //         if ($nilai <= 0) {
    //             return response()->json(['error' => 'Tidak ada rekomendasi yang cocok untuk kamu'], 422);
    //         }

    //         // Get kelulusan prodi IDs where user's score is higher or equal to the average score required
    //         $kelulusan = DB::table('view_rekapitulasi_nilai_to')
    //             ->join('kelulusan', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
    //             ->whereIn('kelulusan.id_prodi', $prodiIds)
    //             ->where('view_rekapitulasi_nilai_to.average_to', '<=', $nilai)
    //             ->orderByDesc('view_rekapitulasi_nilai_to.average_to')
    //             ->pluck('kelulusan.id_prodi')
    //             ->toArray();
    
    //         if (empty($kelulusan)) {
    //             return response()->json(['error' => 'Tidak ada rekomendasi yang cocok untuk kamu'], 422);
    //         }

    //         // Check recommendation by calculating average score for each prodi
    //         $checkRekomendasi = DB::table('t_prodi')
    //             ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
    //             ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
    //             ->whereIn('t_prodi.id_prodi', $kelulusan)
    //             ->select('t_prodi.id_prodi', DB::raw('AVG(view_rekapitulasi_nilai_to.average_to) as average_total_nilai'))
    //             ->groupBy('t_prodi.id_prodi')
    //             ->get();

    //         foreach($checkRekomendasi as $check) {
    //             if($nilai >= $check->average_total_nilai) { 
    //                 $rekomendasiFinal[] = $check->id_prodi;
    //             }
    //         }

    //         if (empty($rekomendasiFinal)) {
    //             return response()->json(['error' => 'Tidak ada rekomendasi yang cocok untuk kamu'], 422);
    //         }

    //         $prodiRekomendasi = Prodi::whereIn('t_prodi.id_prodi', $rekomendasiFinal)
    //             ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
    //             ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
    //             ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
    //             ->select(
    //                 't_prodi.id_prodi',
    //                 't_prodi.nama_prodi',
    //                 't_ptn.nama_ptn',
    //                 't_ptn.nama_singkat',
    //                 DB::raw('AVG(view_rekapitulasi_nilai_to.average_to) as average_total_nilai')
    //             )
    //             ->groupBy('t_prodi.id_prodi', 't_prodi.nama_prodi', 't_ptn.nama_ptn', 't_ptn.nama_singkat')
    //             ->orderByDesc('average_total_nilai')
    //             ->get();

    //         return response()->json(['rekomendasi' => $prodiRekomendasi]);
    //     } catch (\Exception $exception) {
    //         return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
    //     }
    // }

    // public function prediksi_prodi(Request $request)
    // {
    //     set_time_limit(1800);

    //     try {
    //         $rekomendasiFinal = [];
    //         $namaProdi = $request->prodi;

    //         // Ambil id_prodi berdasarkan nama_prodi
    //         $prodiIds = Prodi::where('nama_prodi', 'like', "%$namaProdi%")->pluck('id_prodi')->toArray();

    //         if (empty($prodiIds)) {
    //             $errorMessage = "Nama Prodi tidak ada";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         $nilai = ViewNilaiFinal::where('username', auth()->user()->email)->value('average_to');
    //         // $nilai = 36.45447143;

    //         if(empty($nilai)){
    //             $errorMessage = "Anda Belum memiliki data nilai";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         if ($nilai <= 0) {
    //             $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         // Ambil kelulusan berdasarkan prodi dan nilai
    //         $kelulusan = DB::table('view_rekapitulasi_nilai_to')
    //             ->join('kelulusan', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
    //             ->whereIn('kelulusan.id_prodi', $prodiIds)
    //             ->where('view_rekapitulasi_nilai_to.average_to', '<=', $nilai)
    //             ->orderByDesc('view_rekapitulasi_nilai_to.average_to')
    //             ->pluck('kelulusan.id_prodi')
    //             ->toArray();

    //         if (empty($kelulusan)) {
    //             $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         // Cek rekomendasi berdasarkan nilai
    //         $checkRekomendasi = DB::table('t_prodi')
    //             ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
    //             ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
    //             ->whereIn('t_prodi.id_prodi', $kelulusan)
    //             ->select('t_prodi.id_prodi', DB::raw('avg(view_rekapitulasi_nilai_to.average_to) as average_total_nilai'))
    //             ->groupBy('t_prodi.id_prodi')
    //             ->get();

    //         foreach($checkRekomendasi as $check) {
    //             if($nilai >= $check->average_total_nilai) { 
    //                 $rekomendasiFinal[] = $check->id_prodi;
    //             }
    //         }

    //         if(empty($rekomendasiFinal)) {
    //             $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         // Ambil rekomendasi prodi berdasarkan id_prodi yang sudah difilter
    //         $prodiRekomendasi = Prodi::whereIn('t_prodi.id_prodi', $rekomendasiFinal)
    //             ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
    //             ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
    //             ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
    //             ->select(
    //                 't_prodi.id_prodi',
    //                 't_prodi.nama_prodi',
    //                 't_ptn.nama_ptn',
    //                 't_ptn.nama_singkat',
    //                 DB::raw('AVG(view_rekapitulasi_nilai_to.average_to) as average_total_nilai')
    //             )
    //             ->groupBy(
    //                 't_prodi.id_prodi',
    //                 't_prodi.nama_prodi',
    //                 't_ptn.nama_ptn',
    //                 't_ptn.nama_singkat'
    //             )
    //             ->orderByDesc('average_total_nilai')
    //             ->get();

    //         return response()->json([
    //             'rekomendasi' => $prodiRekomendasi,
    //         ]);
    //     } catch (\Exception $exception) {
    //         return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
    //     }
    // }
}
