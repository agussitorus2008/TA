<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Siswa;
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

    public function prediksi(Request $request)
    {
        $kategori = "";

        $prodi = $request->prodi;

        $dayaTampung = Nilai::where('pilihan1_utbk', $prodi)->value('daya_tampung');

        $ratarataNilai = Kelulusan::where('id_prodi', $prodi)
            ->where('active', 2023)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->pluck('total_nilai')
            ->toArray();
        
        $listNilai = Siswa::where('pilihan1_utbk_aktual', $prodi)
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
        $bobot_pm = $bobot->nilai_pm / $bobot->pm_benar;
        $bobot_pk = $bobot->nilai_pk / $bobot->pk_benar;
        $bobot_lbi = $bobot->nilai_lbi / $bobot->lbi_benar;
        $bobot_lbe = $bobot->nilai_lbe / $bobot->lbe_benar;
        $bobot_pbm = $bobot->nilai_pbm / $bobot->pbm_benar;

        $nilai = (($request->ppu * $bobot_ppu) + ($request->pm * $bobot_pm) + ($request->pk * $bobot_pk) + ($request->lbi * $bobot_lbi) + ($request->lbe * $bobot_lbe) + ($request->pbm * $bobot_pbm)) / 6;

        $listNilai[] = $nilai;

        arsort($listNilai);

        $peringkat = array_search($nilai, array_values($listNilai), true);
        $peringkat += 1;

        $hasil = ($peringkat / count($listNilai));

        $rekomendasi = DB::table('mv_rekapitulasi_nilai_to')
        ->join('kelulusan', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
        ->where('mv_rekapitulasi_nilai_to.total_nilai', '<=', $nilai)
        ->select('kelulusan.id_prodi')
        ->orderByDesc('mv_rekapitulasi_nilai_to.total_nilai');
        

        if (empty($rekomendasi)) {
            $errorMessage = "Tidak ada rekomendasi yang cocok untuk kamu";
            return response()->json(['error' => $errorMessage], 422);
        }
    
        $prodiRekomendasi = Prodi::whereIn('id_prodi', $rekomendasi->pluck('id_prodi'))
        ->select('nama_prodi_ptn')
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
            'rekomendasi' => $prodiRekomendasi
        ]);  
    
    }


}
