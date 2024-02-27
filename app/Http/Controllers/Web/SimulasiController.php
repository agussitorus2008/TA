<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prodi;

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

    public function prediksi1(Request $request)
    {
        $kategori = "";

        $ppu = $request->ppu;
        $pm = $request->pm;
        $pk = $request->pk;
        $lbi = $request->lbi;
        $lbe = $request->lbe;
        $pbm = $request->pbm;
        $prodi = $request->prodi;

        $total_pendaftar = Siswa::where('pilihan_1_utbk', $prodi)
            ->orWhere('pilihan_2_utbk', $prodi)
            ->count();

        $dayaTampung = Nilai::where('pilihan1_utbk', $prodi)->value('daya_tampung');

        $lulus = Kelulusan::where('id_prodi', $prodi)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->get();

        $listNilai = Nilai::whereIn('total_nilai', $lulus->pluck('total_nilai')->toArray())->pluck('total_nilai')->toArray();

        $bobot = Nilai::where('pilihan_1_utbk', $prodi)->first();

        $bobot_ppu = $bobot->nilai_ppu / $bobot->ppu_benar;
        $bobot_pm = $bobot->nilai_pm / $bobot->pm_benar;
        $bobot_pk = $bobot->nilai_pk / $bobot->pk_benar;
        $bobot_lbi = $bobot->nilai_lbi / $bobot->lbi_benar;
        $bobot_lbe = $bobot->nilai_lbe / $bobot->lbe_benar;
        $bobot_pbm = $bobot->nilai_pbm / $bobot->pbm_benar;

        $nilai = (($ppu * $bobot_ppu) + ($pm * $bobot_pm) + ($pk * $bobot_pk) + ($lbi * $bobot_lbi) + ($lbe * $bobot_lbe) + ($pbm * $bobot_pbm)) / 6;

        $peringkat = array_search($nilai, $listNilai) + 1;

        $hasil = ($peringkat / $total_pendaftar) * ($dayaTampung / $total_pendaftar);

        $rekomendasi = Nilai::where('total_nilai', $nilai)
            ->orderBy('total_nilai', 'desc')
            ->pluck('id_prodi')
            ->toArray();

        if ($hasil >= 1) {
            $kategori = "Macet Total";
        } elseif ($hasil >= 0.8) {
            $kategori = "Macet";
        } elseif ($hasil >= 0.5) {
            $kategori = "Dipertimbangkan";
        } else {
            $kategori = "Gas Pool";
        }

        return [$kategori, $rekomendasi];
    }


}
