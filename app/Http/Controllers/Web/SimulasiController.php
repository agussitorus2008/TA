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
use App\Models\Nilai;
use Illuminate\Support\Facades\DB;

class SimulasiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::where('active', 2024)->get();

        return view('app.siswa.simulasi.main', compact('prodi'));
    }

    public function index_ptn()
    {
        $ptn = PTN::where('active', 10)->get();

        return view('app.siswa.simulasi.ptn', compact('ptn'));
    }

    public function index_prodi()
    {
        $prodi = PTN::where('active', 10)->get();

        return view('app.siswa.simulasi.prodi', compact('prodi'));
    }

    public function prediksi(Request $request)
    {
        try {
        $kategori = "";

        $prodi = $request->prodi;

        $daya_tampung = DayaTampung::where('id_prodi', $prodi)
        ->where('tahun', 2024)
        ->first();

        $ratarataNilai = Kelulusan::where('id_prodi', $prodi)
            ->where('active', 2023)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->pluck('total_nilai')
            ->toArray();
        
        $listNilai = SiswaOld::where('pilihan1_utbk_aktual', $prodi)
            ->orWhere('pilihan2_utbk_aktual', $prodi)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->pluck('total_nilai')
            ->toArray();

        if ($listNilai == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $bobot = Kelulusan::where('id_prodi', $prodi)
        ->where('active', 2023)
        ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
        ->whereNotNull('nilai_ppu')
        ->whereNotNull('nilai_pu')
        ->whereNotNull('nilai_pm')
        ->whereNotNull('nilai_pk')
        ->whereNotNull('nilai_lbi')
        ->whereNotNull('nilai_lbe')
        ->whereNotNull('nilai_pbm')
        ->first();

        if ($bobot == null) {
            $errorMessage = "Bobot nilai belum diatur";
            return response()->json(['error' => $errorMessage], 422);
        }

        $bobot_ppu = $bobot->nilai_ppu / $bobot->ppu_benar;
        $bobot_pu = $bobot->nilai_pu / $bobot->pu_benar;
        $bobot_pm = $bobot->nilai_pm / $bobot->pm_benar;
        $bobot_pk = $bobot->nilai_pk / $bobot->pk_benar;
        $bobot_lbi = $bobot->nilai_lbi / $bobot->lbi_benar;
        $bobot_lbe = $bobot->nilai_lbe / $bobot->lbe_benar;
        $bobot_pbm = $bobot->nilai_pbm / $bobot->pbm_benar;

        $nilai = (($request->ppu * $bobot_ppu) + ($request->pu * $bobot_pu) + ($request->pm * $bobot_pm) + ($request->pk * $bobot_pk) + ($request->lbi * $bobot_lbi) + ($request->lbe * $bobot_lbe) + ($request->pbm * $bobot_pbm)) / 6;

        $listNilai[] = $nilai;

        arsort($listNilai);

        $peringkat = array_search($nilai, array_values($listNilai), true);
        $peringkat += 1;

        $hasil = ($peringkat / count($listNilai));

        $rekomendasi = DB::table('mv_rekapitulasi_nilai_to')
            ->join('kelulusan', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->where('mv_rekapitulasi_nilai_to.total_nilai', '<=', $nilai)
            ->select('kelulusan.id_prodi')
            ->orderByDesc('mv_rekapitulasi_nilai_to.total_nilai')
            ->get();

        if (empty($rekomendasi)) {
            $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
            return response()->json(['error' => $errorMessage], 422);
        }
    
        $prodiRekomendasi = Prodi::whereIn('id_prodi', $rekomendasi->pluck('id_prodi'))
            ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
            ->select('t_prodi.*', 't_ptn.nama_ptn', 't_ptn.nama_singkat')
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

    public function prediksi_ptn(Request $request)
    {
        try {
            // Ambil id PTN dari request
            $ptnId = $request->ptn;

            // Cari nama singkat PTN berdasarkan id
            // $namaPTN = PTN::where('id_ptn', $ptnId)->select('nama_singkat');

            // Cari prodi-prodi yang terkait dengan PTN
            $prodi = Prodi::where('id_ptn', $ptnId)->pluck('id_prodi');

            // Ambil bobot berdasarkan prodi yang ditemukan
            $bobot = Kelulusan::whereIn('id_prodi', $prodi)
                ->where('active', 2023)
                ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
                ->whereNotNull('nilai_ppu')
                ->whereNotNull('nilai_pu')
                ->whereNotNull('nilai_pm')
                ->whereNotNull('nilai_pk')
                ->whereNotNull('nilai_lbi')
                ->whereNotNull('nilai_lbe')
                ->whereNotNull('nilai_pbm')
                ->firstOrFail();

            // Hitung nilai
            $nilai = (($request->ppu * $bobot->nilai_ppu / $bobot->ppu_benar) + 
                    ($request->pu * $bobot->nilai_pu / $bobot->pu_benar) + 
                    ($request->pm * $bobot->nilai_pm / $bobot->pm_benar) + 
                    ($request->pk * $bobot->nilai_pk / $bobot->pk_benar) + 
                    ($request->lbi * $bobot->nilai_lbi / $bobot->lbi_benar) + 
                    ($request->lbe * $bobot->nilai_lbe / $bobot->lbe_benar) + 
                    ($request->pbm * $bobot->nilai_pbm / $bobot->pbm_benar)) / 6;

            // Cari kelulusan berdasarkan prodi dan nilai
            $kelulusan = DB::table('mv_rekapitulasi_nilai_to')
                ->join('kelulusan', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
                ->whereIn('kelulusan.id_prodi', $prodi)
                ->where('mv_rekapitulasi_nilai_to.total_nilai', '<=', $nilai)
                ->select('kelulusan.id_prodi')
                ->orderByDesc('mv_rekapitulasi_nilai_to.total_nilai')
                ->get();

            // Jika tidak ada kelulusan, kembalikan pesan kesalahan
            if ($kelulusan->isEmpty()) {
                $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
                return response()->json(['error' => $errorMessage], 422);
            }

            // Ambil rekomendasi prodi berdasarkan kelulusan
            $prodiRekomendasi = Prodi::whereIn('id_prodi', $kelulusan->pluck('id_prodi'))
                ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
                ->select('nama_prodi', 't_ptn.nama_singkat')
                ->paginate(300);


            return response()->json([
                'rekomendasi' => $prodiRekomendasi,
            ]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }


    public function prediksi_prodi(Request $request)
    {
        try {
            // Ambil nama prodi dari request
            $namaProdi = $request->prodi;

            // Cari ID Prodi berdasarkan nama prodi
            $prodiIds = Prodi::where('nama_prodi', 'like', "%$namaProdi%")->pluck('id_prodi');

            // Jika prodi tidak ditemukan, kembalikan pesan kesalahan
            if ($prodiIds->isEmpty()) {
                $errorMessage = "Nama Prodi tidak ada";
                return response()->json(['error' => $errorMessage], 422);
            }

            // Ambil bobot berdasarkan ID Prodi yang ditemukan
            $bobot = Kelulusan::whereIn('id_prodi', $prodiIds)
                ->where('active', 2023)
                ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
                ->whereNotNull('nilai_ppu')
                ->whereNotNull('nilai_pu')
                ->whereNotNull('nilai_pm')
                ->whereNotNull('nilai_pk')
                ->whereNotNull('nilai_lbi')
                ->whereNotNull('nilai_lbe')
                ->whereNotNull('nilai_pbm')
                ->firstOrFail();

            // Hitung nilai
            $nilai = (($request->ppu * $bobot->nilai_ppu / $bobot->ppu_benar) +
                    ($request->pu * $bobot->nilai_pu / $bobot->pu_benar) +  
                    ($request->pm * $bobot->nilai_pm / $bobot->pm_benar) + 
                    ($request->pk * $bobot->nilai_pk / $bobot->pk_benar) + 
                    ($request->lbi * $bobot->nilai_lbi / $bobot->lbi_benar) + 
                    ($request->lbe * $bobot->nilai_lbe / $bobot->lbe_benar) + 
                    ($request->pbm * $bobot->nilai_pbm / $bobot->pbm_benar)) / 6;

            // Cari kelulusan berdasarkan prodi dan nilai
            $kelulusan = DB::table('mv_rekapitulasi_nilai_to')
                ->join('kelulusan', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
                ->whereIn('kelulusan.id_prodi', $prodiIds)
                ->where('mv_rekapitulasi_nilai_to.total_nilai', '<=', $nilai)
                ->select('kelulusan.id_prodi')
                ->orderByDesc('mv_rekapitulasi_nilai_to.total_nilai')
                ->get();

            // Jika tidak ada kelulusan, kembalikan pesan kesalahan
            if ($kelulusan->isEmpty()) {
                $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
                return response()->json(['error' => $errorMessage], 422);
            }

            // Ambil rekomendasi prodi berdasarkan kelulusan
            $prodiRekomendasi = Prodi::whereIn('id_prodi', $kelulusan->pluck('id_prodi'))
                ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
                ->select('t_prodi.nama_prodi', 't_ptn.nama_singkat')
                ->paginate(300);

            return response()->json([
                'rekomendasi' => $prodiRekomendasi,
            ]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }


    
}
