<?php

namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Nilaito;
use App\Models\Nilai;
use App\Models\Prodi;
use App\Models\SiswaOld;
use App\Models\DayaTampung;

class HasilTryoutSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::where('username', $user->email)->first();

        if(empty($siswa)){
            $siswa = "Belum ada data siswa";
        }

        $nilaito = Nilaito::where('username', $user->email)->get();

        $nilai = Nilaito::where('username', $user->email)->latest()->first();

        if(empty($nilaito)){
            $siswa = "Belum ada nilai tryout";
        }

        $bobot = Nilai::whereNotNull('nilai_ppu')
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

        $totalNilai = ($nilai->ppu * $bobot_ppu + $nilai->pu * $bobot_pu + $nilai->pm * $bobot_pm + $nilai->pk * $bobot_pk + $nilai->lbi * $bobot_lbi + $nilai->lbe * $bobot_lbe + $nilai->pbm * $bobot_pbm) / 7;

        $totalPendaftar = SiswaOld::where('pilihan1_utbk_aktual', $siswa->pilihan1_utbk_aktual)
            ->orWhere('pilihan2_utbk_aktual', $siswa->pilihan1_utbk_aktual)
            ->orWhere('pilihan1_utbk_aktual', $siswa->pilihan2_utbk_aktual)
            ->orWhere('pilihan2_utbk_aktual', $siswa->pilihan2_utbk_aktual)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->pluck('mv_rekapitulasi_nilai_to.username')
            ->toArray();

        $totalSekolah = SiswaOld::distinct()
            ->select('pilihan1_utbk_aktual')
            ->orWhere('pilihan2_utbk_aktual')
            ->count();

        // $prodipilihan1 = Prodi::where('id_prodi', $siswa->pilihan1_utbk_aktual)
        //     ->first();

        // $prodipilihan2 = Prodi::whereIn('id_prodi', $siswa->pilihan2_utbk_aktual)
        //     ->first();

        $dayatampung1 = DayaTampung::where('id_prodi', $siswa->pilihan1_utbk_aktual)
        ->where('tahun', 2024)
        ->first();

        $dayatampung2 = DayaTampung::where('id_prodi', $siswa->pilihan2_utbk_aktual)
        ->where('tahun', 2024)
        ->first();

        $listNilai1 = SiswaOld::where('pilihan1_utbk_aktual', $siswa->pilihan1_utbk_aktual)
            ->orWhere('pilihan2_utbk_aktual', $siswa->pilihan1_utbk_aktual)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->pluck('total_nilai')
            ->toArray();
        
        $listNilai2 = SiswaOld::where('pilihan1_utbk_aktual', $siswa->pilihan2_utbk_aktual)
            ->orWhere('pilihan2_utbk_aktual', $siswa->pilihan2_utbk_aktual)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->pluck('total_nilai')
            ->toArray();

        if ($listNilai1 == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        if ($listNilai2 == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $listNilai1[] = $totalNilai;
        $listNilai2[] = $totalNilai;

        arsort($listNilai1);
        arsort($listNilai2);

        $peringkat1 = array_search($totalNilai, array_values($listNilai1), true);
        $peringkat1 += 1;

        $peringkat2 = array_search($totalNilai, array_values($listNilai2), true);
        $peringkat2 += 1;

        $totalpendaftar1 = count($listNilai1);
        $totalpendaftar2 = count($listNilai2);

        $listNilaipilihan1 = SiswaOld::where('pilihan1_utbk_aktual', $siswa->pilihan1_utbk_aktual)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->pluck('total_nilai')
            ->toArray();
        
        $listNilaipilihan2 = SiswaOld::where('pilihan1_utbk_aktual', $siswa->pilihan2_utbk_aktual)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->pluck('total_nilai')
            ->toArray();

        if ($listNilaipilihan1 == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        if ($listNilaipilihan2 == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $listNilaipilihan1[] = $totalNilai;
        $listNilaipilihan2[] = $totalNilai;

        arsort($listNilaipilihan1);
        arsort($listNilaipilihan2);

        $peringkat11 = array_search($totalNilai, array_values($listNilaipilihan1), true);
        $peringkat11 += 1;

        $peringkat22 = array_search($totalNilai, array_values($listNilaipilihan2), true);
        $peringkat22 += 1;

        $totalpendaftar11 = count($listNilaipilihan1);
        $totalpendaftar22 = count($listNilaipilihan2);

        return view('app.siswa.hasilTryout.main', compact('user', 'siswa', 'totalPendaftar', 'totalSekolah', 'nilaito', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'peringkat1', 'peringkat2', 'totalpendaftar11', 'totalpendaftar22', 'totalpendaftar1', 'totalpendaftar2', 'peringkat11', 'peringkat22', 'dayatampung1', 'dayatampung2'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate()
    {
        $user = Auth::user();
        $totalNilai = 0;
        $bobot = NIlai::whereNotNull('nilai_ppu')
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
     
        $nilaito = NilaiTO::where('username', $user->email)->first();

        if(empty($nilaito)){
            $nilaito = "Belum ada data nilai";
        }

        if(count($nilaito) > 0){
            foreach($nilaito as $nilai){
                $totalNilai += (($nilai->ppu * $bobot_ppu) + ($nilai->pu * $bobot_pu) + ($nilai->pbm * $bobot_pbm) + ($nilai->pk * $bobot_pk) + ($nilai->lbi * $bobot_lbi) + ($nilai->lbe * $bobot_lbe) + ($nilai->pm * $bobot_pm)) / 7;
            }
        }

        $totalNilai = $totalNilai / count($nilaito);

        $rekomendasi = DB::table('mv_rekapitulasi_nilai_to')
            ->join('kelulusan', 'mv_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->where('mv_rekapitulasi_nilai_to.total_nilai', '<=', $totalNilai)
            ->select('kelulusan.id_prodi')
            ->orderBy('mv_rekapitulasi_nilai_to.total_nilai', 'DESC')
            ->get();

        $prodiRekomendasi1 = Prodi::whereIn('id_prodi', $rekomendasi->pluck('id_prodi'))
            ->select('nama_prodi_ptn')
            ->first();

        $prodiRekomendasi2 = Prodi::whereIn('id_prodi', $rekomendasi->pluck('id_prodi'))
            ->select('nama_prodi_ptn')
            ->skip(1)
            ->first();

        $listNilai1 = SiswaOld::where('pilihan1_utbk_aktual', $prodiRekomendasi1)
            ->orWhere('pilihan2_utbk_aktual', $prodiRekomendasi1)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->pluck('total_nilai')
            ->toArray();
        
        $listNilai2 = SiswaOld::where('pilihan1_utbk_aktual', $prodiRekomendasi2)
            ->orWhere('pilihan2_utbk_aktual', $prodiRekomendasi2)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->pluck('total_nilai')
            ->toArray();

        if ($listNilai1 == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        if ($listNilai2 == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $listNilai1[] = $totalNilai;
        $listNilai2[] = $totalNilai;

        arsort($listNilai1);
        arsort($listNilai2);

        $peringkat1 = array_search($totalNilai, array_values($listNilai1), true);
        $peringkat1 += 1;

        $peringkat2 = array_search($totalNilai, array_values($listNilai2), true);
        $peringkat2 += 1;

        // $hasil1 = ($peringkat1 / count($listNilai1));
        // $hasil2 = ($peringkat1 / count($listNilai2));

        // $kategori1 = $this->cekKategori($hasil1);
        // $kategori2 = $this->cekKategori($hasil2);


        return response()->json([
            'prodiRekomendasi1' => $prodiRekomendasi1,
            'prodiRekomendasi2' => $prodiRekomendasi2,
            'peringkat1' => $peringkat1,
            'peringkat2' => $peringkat2,
            'pendaftar1' => count($listNilai1),
            'pendaftar2' => count($listNilai2)
        ]);
    }

    // private function cekKategori($hasil)
    // {
    //     if ($hasil >= 1) {
    //         $kategori = "Macet Total";
    //     } elseif ($hasil >= 0.8) {
    //         $kategori = "Macet";
    //     } elseif ($hasil >= 0.5) {
    //         $kategori = "Dipertimbangkan";
    //     } else {
    //         $kategori = "Gas Pool";
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
