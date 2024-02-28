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

    public function test(Request $request)
    {
        dd($request->all());
    }

    // public function prediksi(Request $request)
    // {
    //     $kategori = "";

    //     $ppu = $request->ppu;
    //     $pm = $request->pm;
    //     $pk = $request->pk;
    //     $lbi = $request->lbi;
    //     $lbe = $request->lbe;
    //     $pbm = $request->pbm;
    //     $prodi = $request->prodi;

    //     $total_pendaftar = Siswa::where('pilihan1_utbk', $prodi)
    //         ->orWhere('pilihan2_utbk', $prodi)
    //         ->count();

    //     $dayaTampung = Nilai::where('pilihan1_utbk', $prodi)->value('daya_tampung');

    //     $lulus = Kelulusan::where('id_prodi', $prodi)->where('active', 2023)
    //         ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
    //         ->get();
        
    //     $listNilai = $lulus->pluck('total_nilai')->toArray();
    //     $bobot = Nilai::where('pilihan1_utbk', $prodi)
    //         ->whereNotNull('nilai_ppu')
    //         ->whereNotNull('nilai_pm')
    //         ->whereNotNull('nilai_pk')
    //         ->whereNotNull('nilai_lbi')
    //         ->whereNotNull('nilai_lbe')
    //         ->whereNotNull('nilai_pbm')
    //         ->first();

    //     if ($bobot == null) {
    //         return "Bobot nilai belum diatur";
    //     } else {
    //         $bobot_ppu = $bobot->nilai_ppu / $bobot->ppu_benar;
    //         $bobot_pm = $bobot->nilai_pm / $bobot->pm_benar;
    //         $bobot_pk = $bobot->nilai_pk / $bobot->pk_benar;
    //         $bobot_lbi = $bobot->nilai_lbi / $bobot->lbi_benar;
    //         $bobot_lbe = $bobot->nilai_lbe / $bobot->lbe_benar;
    //         $bobot_pbm = $bobot->nilai_pbm / $bobot->pbm_benar;

    //         $nilai = (($ppu * $bobot_ppu) + ($pm * $bobot_pm) + ($pk * $bobot_pk) + ($lbi * $bobot_lbi) + ($lbe * $bobot_lbe) + ($pbm * $bobot_pbm)) / 6;

    //         function array_sort($array) {
    //             $originalKeys = array_keys($array);
    //             arsort($array);
    //             $sortedArray = [];
    //             foreach ($originalKeys as $key) {
    //                 $sortedArray[$key] = $array[$key];
    //             }
            
    //             return $sortedArray;
    //         }

    //         $sortedListNilai = array_sort($listNilai);
    //         $peringkat = count($sortedListNilai) - array_search($nilai, $sortedListNilai);

    //         $hasil = ($peringkat / $total_pendaftar) * 10;

    //         $rekomendasi = Nilai::where('total_nilai', '<=', $nilai)->get();
    //         $programRekomendasi = Kelulusan::whereIn('username', $rekomendasi->pluck('username'))->pluck('id_prodi')->toArray();

    //         if($programRekomendasi == null){
    //             $programRekomendasi = "Tidak ada rekomendasi";
    //         }

    //         if ($hasil >= 1) {
    //             $kategori = "Macet Total";
    //         } elseif ($hasil >= 0.8) {
    //             $kategori = "Macet";
    //         } elseif ($hasil >= 0.5) {
    //             $kategori = "Dipertimbangkan";
    //         } else {
    //             $kategori = "Gas Pool";
    //         }

    //         return [
    //             "response" => [
    //                 "hasil" => $hasil,
    //                 "kategori" => $kategori,
    //                 "peringkat" => $peringkat,
    //                 "total_pendaftar" => $total_pendaftar,
    //                 "nilai_rata" => count($listNilai) > 0 ? array_sum($listNilai) / count($listNilai) : 0,
    //                 "nilai_saya" => $nilai,
    //                 "rekomendasi" => $programRekomendasi
    //             ]
    //         ];            
            
    //         }
    // }

    public function prediksi(Request $request)
    {
        $kategori = "";

        $prodi = $request->prodi;

        $total_pendaftar = Siswa::where('pilihan1_utbk', $prodi)
            ->orWhere('pilihan2_utbk', $prodi)
            ->count();

        $dayaTampung = Nilai::where('pilihan1_utbk', $prodi)->value('daya_tampung');

        $listNilai = Kelulusan::where('id_prodi', $prodi)
            ->where('active', 2023)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->pluck('total_nilai')
            ->toArray();

        // $bobot = Nilai::where('pilihan1_utbk', $prodi)
        //     ->whereNotNull('nilai_ppu')
        //     ->whereNotNull('nilai_pm')
        //     ->whereNotNull('nilai_pk')
        //     ->whereNotNull('nilai_lbi')
        //     ->whereNotNull('nilai_lbe')
        //     ->whereNotNull('nilai_pbm')
        //     ->first();

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
            return "Bobot nilai belum diatur";
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

        $hasil = ($peringkat / $total_pendaftar) * 10;

        $rekomendasi = DB::table('mv_rekapitulasi_nilai_to')
        ->join('kelulusan', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
        ->where('mv_rekapitulasi_nilai_to.total_nilai', '<=', $nilai)
        ->pluck('kelulusan.id_prodi')
        ->toArray();


        if (empty($rekomendasi)) {
            $rekomendasi = "Tidak ada rekomendasi";
        }

        if ($hasil >= 1) {
            $kategori = "Macet Total";
        } elseif ($hasil >= 0.8) {
            $kategori = "Macet";
        } elseif ($hasil >= 0.5) {
            $kategori = "Dipertimbangkan";
        } else {
            $kategori = "Gas Pool";
        }

        return [
            "response" => [
                "hasil" => $hasil,
                "kategori" => $kategori,
                "peringkat" => $peringkat,
                "total_pendaftar" => $total_pendaftar,
                "nilai_rata" => count($listNilai) > 0 ? array_sum($listNilai) / count($listNilai) : 0,
                "nilai_saya" => $nilai,
                "rekomendasi" => $rekomendasi
            ]
        ];
    }


}
