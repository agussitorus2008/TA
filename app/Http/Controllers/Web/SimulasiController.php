<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\PTN;
use App\Models\DayaTampung;
use App\Models\Siswa;
use App\Models\SiswaOld;
use App\Models\Kelulusan;
use App\Models\Nilaito;
use App\Models\ViewNilaiFinalTerbaru;
use Illuminate\Support\Facades\DB;

class SimulasiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::where('active', 2024)->get();

        $nilai = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first();
        $nilaiCount = Nilaito::where('username', auth()->user()->email)->count();
        if($nilai){
            $nilai = $nilai->average_to;
        }
        return view('app.siswa.simulasi.main', compact('prodi', 'nilai', 'nilaiCount'));
    }

    public function index_ptn()
    {
        $ptn = PTN::where('active', 10)->get();
        $nilai = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first();
        $nilaiCount = Nilaito::where('username', auth()->user()->email)->count();
        if($nilai){
            $nilai = $nilai->average_to;
        }

        return view('app.siswa.simulasi.ptn', compact('ptn', 'nilai', 'nilaiCount'));
    }

    public function index_prodi()
    {
        $prodi = PTN::where('active', 10)->get();
        $nilai = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first();
        $nilaiCount = Nilaito::where('username', auth()->user()->email)->count();
        if($nilai){
            $nilai = $nilai->average_to;
        }

        return view('app.siswa.simulasi.prodi', compact('prodi', 'nilai', 'nilaiCount'));
    }

    public function prediksi(Request $request)
    {
        try {
        $kategori = "";

        $prodi = $request->prodi;
        // dd($prodi);

        $daya_tampung = DayaTampung::where('id_prodi', $prodi)
        ->where('tahun', 2024)
        ->first();

        $ratarataNilai = Kelulusan::where('id_prodi', $prodi)
            ->where('active', 2023)
            ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
            ->pluck('average_to')
            ->toArray();
        
        $listNilai = SiswaOld::join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 't_siswa.username')
        ->where('t_siswa.pilihan1_utbk', $prodi)
        ->orWhere('t_siswa.pilihan2_utbk', $prodi)
        ->pluck('view_rekapitulasi_nilai_to_sebelum.average_to')
        ->toArray();

        if ($listNilai == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $nilai = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first()->average_to;

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

        $rekomendasi = DB::table('view_rekapitulasi_nilai_to_sebelum')
            ->join('kelulusan', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
            ->where('view_rekapitulasi_nilai_to_sebelum.average_to', '<=', $nilai)
            ->select('kelulusan.id_prodi')
            ->orderByDesc('view_rekapitulasi_nilai_to_sebelum.average_to')
            ->get();
        
        $checkRekomendasi = DB::table('t_prodi')
            ->whereIn('t_prodi.id_prodi', $rekomendasi->pluck('id_prodi'))
            ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
            ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
            ->select('t_prodi.id_prodi', DB::raw('avg(view_rekapitulasi_nilai_to_sebelum.average_to) as average_total_nilai'))
            ->groupBy('t_prodi.id_prodi')
            ->get(); 

        $rekomendasiFinal = [];

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
            ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
            ->select(
                't_prodi.id_prodi',
                't_prodi.nama_prodi',
                't_ptn.nama_ptn',
                't_ptn.nama_singkat',
                DB::raw('AVG(view_rekapitulasi_nilai_to_sebelum.average_to) as average_total_nilai')
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

    // public function prediksi_ptn(Request $request)
    // {
    //     try {
    //         // Ambil id PTN dari request
    //         $ptnId = $request->ptn;

    //         $prodi = Prodi::where('id_ptn', $ptnId)
    //             ->pluck('id_prodi');

    //         $nilai = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first()->average_to;
    //         // $nilai = 50.29030000;

    //         if(empty($nilai)){
    //             $errorMessage = "Anda Belum memiliki data nilai";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         if ($nilai <= 0) {
    //             $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         // Cari kelulusan berdasarkan prodi dan nilai
    //         $kelulusan = DB::table('view_rekapitulasi_nilai_to_sebelum2')
    //             ->join('kelulusan', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 'kelulusan.username')
    //             ->whereIn('kelulusan.id_prodi', $prodi)
    //             ->where('view_rekapitulasi_nilai_to_sebelum2.average_to', '<=', $nilai)
    //             ->select('kelulusan.id_prodi')
    //             ->orderByDesc('view_rekapitulasi_nilai_to_sebelum2.average_to')
    //             ->get();
            
    //             if($kelulusan->isEmpty()) {
    //                 $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
    //                 return response()->json(['error' => $errorMessage], 422);
    //             }

    //         $checkRekomendasi = DB::table('t_prodi')
    //         ->whereIn('t_prodi.id_prodi', $kelulusan->pluck('id_prodi'))
    //         ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
    //         ->join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 'kelulusan.username')
    //         ->select('t_prodi.id_prodi', DB::raw('avg(view_rekapitulasi_nilai_to_sebelum2.average_to) as average_total_nilai'))
    //         ->groupBy('t_prodi.id_prodi')
    //         ->get(); 
    
    //         $rekomendasiFinal = [];
    
    //         foreach($checkRekomendasi as $check) {
    //             if($nilai >= $check->average_total_nilai) { 
    //                 $rekomendasiFinal[] = $check->id_prodi;
    //             }
    //         }
            
    //         if(empty($rekomendasiFinal)) { 
    //             $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }
    
    //         $prodiIds = $rekomendasiFinal; 

    //         // Ambil rekomendasi prodi berdasarkan kelulusan
    //         $prodiRekomendasi = Prodi::whereIn('t_prodi.id_prodi', $prodiIds)
    //         ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
    //         ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
    //         ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
    //         ->select(
    //             't_prodi.id_prodi',
    //             't_prodi.nama_prodi',
    //             't_ptn.nama_ptn',
    //             't_ptn.nama_singkat',
    //             DB::raw('AVG(view_rekapitulasi_nilai_to_sebelum2.average_to) as average_total_nilai')
    //         )
    //         ->groupBy(
    //             't_prodi.id_prodi',
    //             't_prodi.nama_prodi',
    //             't_ptn.nama_ptn',
    //             't_ptn.nama_singkat'
    //         )
    //         ->orderByDesc('average_total_nilai');


    //         return response()->json([
    //             'rekomendasi' => $prodiRekomendasi,
    //         ]);
    //     } catch (\Exception $exception) {
    //         return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
    //     }
    // }

    public function prediksi_ptn(Request $request)
    {
        try {
            $ptnId = $request->ptn;

            $prodiIds = Prodi::where('id_ptn', $ptnId)
                ->pluck('id_prodi')
                ->toArray();

            $nilai = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->value('average_to');
            // $nilai = 41.29030000;

            if (is_null($nilai)) {
                return response()->json(['error' => 'Anda Belum memiliki data nilai'], 422);
            }

            if ($nilai <= 0) {
                return response()->json(['error' => 'Tidak ada rekomendasi yang cocok untuk kamu'], 422);
            }

            $kelulusan = DB::table('view_rekapitulasi_nilai_to_sebelum')
                ->join('kelulusan', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
                ->whereIn('kelulusan.id_prodi', $prodiIds)
                ->where('view_rekapitulasi_nilai_to_sebelum.average_to', '<=', $nilai)
                ->select('kelulusan.id_prodi')
                ->orderByDesc('view_rekapitulasi_nilai_to_sebelum.average_to')
                ->distinct()
                ->pluck('kelulusan.id_prodi')
                ->toArray();

            if (empty($kelulusan)) {
                return response()->json(['error' => 'Tidak ada rekomendasi yang cocok untuk kamu'], 422);
            }

            $checkRekomendasi = DB::table('t_prodi')
                ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
                ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
                ->whereIn('t_prodi.id_prodi', $kelulusan)
                ->select('t_prodi.id_prodi', DB::raw('AVG(view_rekapitulasi_nilai_to_sebelum.average_to) as average_total_nilai'))
                ->groupBy('t_prodi.id_prodi')
                ->get();

            $rekomendasiFinal = $checkRekomendasi->filter(function($check) use ($nilai) {
                return $nilai >= $check->average_total_nilai;
            })->pluck('id_prodi')->toArray();

            if (empty($rekomendasiFinal)) {
                return response()->json(['error' => 'Tidak ada rekomendasi yang cocok untuk kamu'], 422);
            }

            $prodiRekomendasi = Prodi::whereIn('t_prodi.id_prodi', $rekomendasiFinal)
                ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
                ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
                ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
                ->select(
                    't_prodi.id_prodi',
                    't_prodi.nama_prodi',
                    't_ptn.nama_ptn',
                    't_ptn.nama_singkat',
                    DB::raw('AVG(view_rekapitulasi_nilai_to_sebelum.average_to) as average_total_nilai')
                )
                ->groupBy('t_prodi.id_prodi', 't_prodi.nama_prodi', 't_ptn.nama_ptn', 't_ptn.nama_singkat')
                ->orderByDesc('average_total_nilai')
                ->paginate(10);

            return response()->json(['rekomendasi' => $prodiRekomendasi]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }



    public function prediksi_prodi(Request $request)
    {
        try {
            $namaProdi = $request->prodi;

            $prodiIds = Prodi::where('nama_prodi', 'like', "%$namaProdi%")->pluck('id_prodi');

            if ($prodiIds->isEmpty()) {
                $errorMessage = "Nama Prodi tidak ada";
                return response()->json(['error' => $errorMessage], 422);
            }

            $nilai = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first()->average_to;

            if(empty($nilai)){
                $errorMessage = "Anda Belum memiliki data nilai";
                return response()->json(['error' => $errorMessage], 422);
            }

            if ($nilai <= 0) {
                $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
                return response()->json(['error' => $errorMessage], 422);
            }

            $kelulusan = DB::table('view_rekapitulasi_nilai_to_sebelum')
                ->join('kelulusan', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
                ->whereIn('kelulusan.id_prodi', $prodiIds)
                ->where('view_rekapitulasi_nilai_to_sebelum.average_to', '<=', $nilai)
                ->select('kelulusan.id_prodi')
                ->orderByDesc('view_rekapitulasi_nilai_to_sebelum.average_to')
                ->get();

            if ($kelulusan->isEmpty()) {
                $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
                return response()->json(['error' => $errorMessage], 422);
            }

            $checkRekomendasi = DB::table('t_prodi')
            ->whereIn('t_prodi.id_prodi', $kelulusan->pluck('id_prodi'))
            ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
            ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
            ->select('t_prodi.id_prodi', DB::raw('avg(view_rekapitulasi_nilai_to_sebelum.average_to) as average_total_nilai'))
            ->groupBy('t_prodi.id_prodi')
            ->get(); 
    
            $rekomendasiFinal = [];
    
            foreach($checkRekomendasi as $check) {
                if($nilai >= $check->average_total_nilai) { 
                    $rekomendasiFinal[] = $check->id_prodi;
                }
            }
            
            if(empty($rekomendasiFinal)) { 
                $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
                return response()->json(['error' => $errorMessage], 422);
            }
    
            $ptnRekomendasi = $rekomendasiFinal; 

            $prodiRekomendasi = Prodi::whereIn('t_prodi.id_prodi', $ptnRekomendasi)
            ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
            ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
            ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
            ->select(
                't_prodi.id_prodi',
                't_prodi.nama_prodi',
                't_ptn.nama_ptn',
                't_ptn.nama_singkat',
                DB::raw('AVG(view_rekapitulasi_nilai_to_sebelum.average_to) as average_total_nilai')
            )
            ->groupBy(
                't_prodi.id_prodi',
                't_prodi.nama_prodi',
                't_ptn.nama_ptn',
                't_ptn.nama_singkat'
            )
            ->orderByDesc('average_total_nilai');

            return response()->json([
                'rekomendasi' => $prodiRekomendasi,
            ]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }
    // public function prediksi_prodi(Request $request)
    // {
    //     try {
    //         // Ambil nama prodi dari request
    //         $namaProdi = $request->prodi;

    //         // Cari ID Prodi berdasarkan nama prodi
    //         $prodiIds = Prodi::where('nama_prodi', 'like', "%$namaProdi%")->pluck('id_prodi');

    //         // Jika prodi tidak ditemukan, kembalikan pesan kesalahan
    //         if ($prodiIds->isEmpty()) {
    //             $errorMessage = "Nama Prodi tidak ada";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         // Ambil nilai user
    //         $nilai = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->value('average_to');

    //         if (is_null($nilai)) {
    //             $errorMessage = "Anda Belum memiliki data nilai";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         if ($nilai <= 0) {
    //             $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         // Cari kelulusan berdasarkan prodi dan nilai
    //         $kelulusan = DB::table('view_rekapitulasi_nilai_to_sebelum')
    //             ->join('kelulusan', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
    //             ->whereIn('kelulusan.id_prodi', $prodiIds)
    //             ->where('view_rekapitulasi_nilai_to_sebelum.average_to', '<=', $nilai)
    //             ->select('kelulusan.id_prodi')
    //             ->orderByDesc('view_rekapitulasi_nilai_to_sebelum.average_to')
    //             ->pluck('kelulusan.id_prodi');

    //         // Jika tidak ada kelulusan, kembalikan pesan kesalahan
    //         if ($kelulusan->isEmpty()) {
    //             $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         $checkRekomendasi = DB::table('t_prodi')
    //             ->whereIn('t_prodi.id_prodi', $kelulusan)
    //             ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
    //             ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
    //             ->select('t_prodi.id_prodi', DB::raw('AVG(view_rekapitulasi_nilai_to_sebelum.average_to) as average_total_nilai'))
    //             ->groupBy('t_prodi.id_prodi')
    //             ->havingRaw('AVG(view_rekapitulasi_nilai_to_sebelum.average_to) <= ?', [$nilai])
    //             ->pluck('t_prodi.id_prodi');

    //         // Jika tidak ada rekomendasi, kembalikan pesan kesalahan
    //         if ($checkRekomendasi->isEmpty()) {
    //             $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
    //             return response()->json(['error' => $errorMessage], 422);
    //         }

    //         // Ambil rekomendasi prodi berdasarkan kelulusan
    //         $prodiRekomendasi = Prodi::whereIn('t_prodi.id_prodi', $checkRekomendasi)
    //             ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
    //             ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
    //             ->join('view_rekapitulasi_nilai_to_sebelum', 'view_rekapitulasi_nilai_to_sebelum.username', '=', 'kelulusan.username')
    //             ->select(
    //                 't_prodi.id_prodi',
    //                 't_prodi.nama_prodi',
    //                 't_ptn.nama_ptn',
    //                 't_ptn.nama_singkat',
    //                 DB::raw('AVG(view_rekapitulasi_nilai_to_sebelum.average_to) as average_total_nilai')
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
