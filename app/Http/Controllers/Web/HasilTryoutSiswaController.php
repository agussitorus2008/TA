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
use App\Models\ViewNilaiFinalTerbaru;
use App\Models\ViewNilaiFinal;
use Illuminate\Support\Facades\DB;

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

        if ($siswa == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $nilaito = Nilai::where('username', $user->email)->get();

        $tryoutCount = $nilaito->count();

        $siswacheck = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first();

        if (empty($siswacheck)) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $totalNilai = $siswacheck->average_to;

        if (empty($totalNilai)) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $allnilai = Nilai::where('username', $user->email)->get();
        
        if ($nilaito == null) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $bobot_ppu = 30;
        $bobot_pu = 20;
        $bobot_pm = 20;
        $bobot_pk = 15;
        $bobot_lbi = 30;
        $bobot_lbe = 20;
        $bobot_pbm = 20;
        $bobot_total = 155;


        $totalPendaftar = SiswaOld::where('t_siswa.pilihan1_utbk', $siswa->pilihan1_utbk_aktual)
            ->orWhere('t_siswa.pilihan2_utbk', $siswa->pilihan1_utbk_aktual)
            ->orWhere('t_siswa.pilihan1_utbk', $siswa->pilihan2_utbk_aktual)
            ->orWhere('t_siswa.pilihan2_utbk', $siswa->pilihan2_utbk_aktual)
            ->join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 't_siswa.username')
            ->pluck('view_rekapitulasi_nilai_to_sebelum2.username')
            ->toArray();


        // $totalPendaftar = ViewNilaiFinal::where('pilihan1_utbk', $siswa->pilihan1_utbk_aktual)
        //     ->orWhere('pilihan2_utbk', $siswa->pilihan1_utbk_aktual)
        //     ->orWhere('pilihan1_utbk', $siswa->pilihan2_utbk_aktual)
        //     ->orWhere('pilihan2_utbk', $siswa->pilihan2_utbk_aktual)
        //     ->pluck('username')
        //     ->toArray();

        // try {
        //     $totalPendaftar = ViewNilaiFinal::groupBy('username', 'pilihan1_utbk', 'pilihan2_utbk', 'average_to')->get();
        //     dd($totalPendaftar);
        // } catch (\Exception $e) {
        //     dd($e->getMessage());
        // }  
        
        // $totalPendaftar = ViewNilaiFinal::where('username', '004606046')->first();
        // dd($totalPendaftar);

        
        $totalSekolah = SiswaOld::distinct()
            ->select('asal_sekolah')
            ->orWhere('pilihan2_utbk', $siswa->pilihan1_utbk_aktual)
            ->orWhere('pilihan1_utbk', $siswa->pilihan2_utbk_aktual)
            ->orWhere('pilihan2_utbk', $siswa->pilihan2_utbk_aktual)
            ->count();

        $dayatampung1 = DayaTampung::where('id_prodi', $siswa->pilihan1_utbk_aktual)
        ->where('tahun', 2024)
        ->first();

        $dayatampung2 = DayaTampung::where('id_prodi', $siswa->pilihan2_utbk_aktual)
        ->where('tahun', 2024)
        ->first();

        $listNilai1 = SiswaOld::join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 't_siswa.username')
            ->where('t_siswa.pilihan1_utbk', $siswa->pilihan1_utbk_aktual)
            ->orWhere('t_siswa.pilihan2_utbk', $siswa->pilihan1_utbk_aktual)
            ->pluck('view_rekapitulasi_nilai_to_sebelum2.average_to')
            ->toArray();

        // $listNilai1 = ViewNilaiFinal::where('pilihan1_utbk', $siswa->pilihan1_utbk_aktual)
        //     ->orWhere('pilihan2_utbk', $siswa->pilihan1_utbk_aktual)
        //     ->pluck('average_to')
        //     ->toArray();

        $listNilai2 = SiswaOld::join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 't_siswa.username')
            ->where('t_siswa.pilihan1_utbk', $siswa->pilihan2_utbk_aktual)
            ->orWhere('t_siswa.pilihan2_utbk', $siswa->pilihan2_utbk_aktual)
            ->pluck('view_rekapitulasi_nilai_to_sebelum2.average_to')
            ->toArray();
        // $listNilai2 = ViewNilaiFinal::where('pilihan1_utbk', $siswa->pilihan2_utbk_aktual)
        //     ->orWhere('pilihan2_utbk', $siswa->pilihan2_utbk_aktual)
        //     ->pluck('average_to')
        //     ->toArray();


        if ($listNilai1 == null) {
            $peringkat1 = 0;
            $listNilai1 = [];
        }

        if ($listNilai2 == null) {
            $peringkat2 = 0;
            $listNilai2 = [];
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

        // untuk pilihan 1
        $listNilaipilihan1 = SiswaOld::where('t_siswa.pilihan1_utbk', $siswa->pilihan1_utbk_aktual)
            ->join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 't_siswa.username')
            ->pluck('view_rekapitulasi_nilai_to_sebelum2.average_to')
            ->toArray();
        // $listNilaipilihan1 = ViewNilaiFinal::where('pilihan1_utbk', $siswa->pilihan1_utbk_aktual)
        //     ->pluck('average_to')
        //     ->toArray();
    
        $listNilaipilihan2 = SiswaOld::where('t_siswa.pilihan1_utbk', $siswa->pilihan2_utbk_aktual)
            ->join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 't_siswa.username')
            ->pluck('view_rekapitulasi_nilai_to_sebelum2.average_to')
            ->toArray();
        // $listNilaipilihan2 = ViewNilaiFinal::where('pilihan1_utbk', $siswa->pilihan2_utbk_aktual)
        // ->pluck('average_to')
        // ->toArray();
    

        if ($listNilaipilihan1 == null) {
            $peringkat11 = 0;
            $listNilaipilihan1 = [];
        }

        if ($listNilaipilihan2 == null) {
            $peringkat22 = 0;
            $listNilaipilihan2 = [];
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

        $totalData = $nilaito->map(function ($item) {
            return $item->ppu_benar + $item->pu_benar + $item->pm_benar + $item->pk_benar + $item->lbi_benar + $item->lbe_benar + $item->pbm_benar;
                });
        
        $rataData = $nilaito->map(function ($item) {
            return number_format($item->total_nilai * 10, 2);
        });

        $nilaiRata = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first()->average_to;

        if ($nilaiRata == null) {
            $errorMessage = "Belum ada nilai diatur";
            return response()->json(['error' => $errorMessage], 422);
        }

        return view('app.siswa.hasilTryout.main', compact('user', 'siswa', 'totalPendaftar', 'totalSekolah', 'nilaito', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'peringkat1', 'peringkat2', 'totalpendaftar11', 'totalpendaftar22', 'totalpendaftar1', 'totalpendaftar2', 'peringkat11', 'peringkat22', 'dayatampung1', 'dayatampung2', 'totalData', 'rataData', 'tryoutCount', 'nilaiRata'));
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
     
        $nilaito = Nilai::where('username', $user->email)->first();

        if(empty($nilaito)){
            $nilaito = "Belum ada data nilai";
        }else{
            $totalNilai += (($nilaito->ppu * $bobot_ppu) + ($nilaito->pu * $bobot_pu) + ($nilaito->pbm * $bobot_pbm) + ($nilaito->pk * $bobot_pk) + ($nilaito->lbi * $bobot_lbi) + ($nilaito->lbe * $bobot_lbe) + ($nilaito->pm * $bobot_pm)) / 7;
        }

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


        $pendaftar1 = count($listNilai1);
        $pendaftar2 = count($listNilai2);

        return [
            'prodiRekomendasi1' => $prodiRekomendasi1,
            'prodiRekomendasi2' => $prodiRekomendasi2,
            'peringkatRekomendasi1' => $peringkat1,
            'peringkatRekomendasi2' => $peringkat2,
            'pendaftarRekomendasi1' => $pendaftar1,
            'pendaftarRekomendasi2' => $pendaftar2
        ];        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pilihan1($nama_prodi)
    {
        // Find the program by its name
        $prodi = Prodi::where('nama_prodi_ptn', $nama_prodi)->first();
        if (!$prodi) {
            // Handle the case where the program is not found
            abort(404, 'Program not found');
        }

        // Get the user's total score
        $user = auth()->user();
        $userTotalNilai = ViewNilaiFinalTerbaru::where('username', $user->email)->first()->average_to;
        $nama = Siswa::where('username',$user->email)->first();

        // Get the scores of all other applicants for the same program
        $listNilaipilihan1 = SiswaOld::where('t_siswa.pilihan1_utbk', $prodi->id_prodi)
            ->join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 't_siswa.username')
            ->select('t_siswa.username', 'view_rekapitulasi_nilai_to_sebelum2.average_to', 't_siswa.first_name')
            ->get()
            ->map(function ($item) {
                return $item->getAttributes();
            })
            ->toArray();


        // Append the user's score to the list
        $listNilaipilihan1[] = [
            'username' => $user->email,
            'average_to' => $userTotalNilai,
            'first_name' => $nama->first_name,
            // 'asal_sekolah' => $nama->asal_sekolah
        ];

        // Sort the list in descending order (higher scores first)
        usort($listNilaipilihan1, function($a, $b) {
            return $b['average_to'] <=> $a['average_to'];
        });

        // Determine the user's rank
        $userRank = array_search($user->email, array_column($listNilaipilihan1, 'username')) + 1;

        // Pass the necessary data to the view
        return view('app.siswa.hasilTryout.pilihan1', [
            'userRank' => $userRank,
            'nama_prodi' => $nama_prodi,
            'userTotalNilai' => $userTotalNilai,
            'listNilaipilihan1' => $listNilaipilihan1
        ]);
    }

    public function pilihanTotal($nama_prodi)
    {
        // Find the program by its name
        $prodi = Prodi::where('nama_prodi_ptn', $nama_prodi)->first();
        if (!$prodi) {
            // Handle the case where the program is not found
            abort(404, 'Program not found');
        }

        // Get the user's total score
        $user = auth()->user();
        $userTotalNilai = ViewNilaiFinalTerbaru::where('username', $user->email)->first()->average_to;
        $nama = Siswa::where('username',$user->email)->first();

        // Get the scores of all other applicants for the same program
        $listNilaipilihan1 = SiswaOld::where('t_siswa.pilihan1_utbk', $prodi->id_prodi)
            ->orWhere('t_siswa.pilihan2_utbk', $prodi->id_prodi)
            ->join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 't_siswa.username')
            ->select('t_siswa.username', 'view_rekapitulasi_nilai_to_sebelum2.average_to', 't_siswa.first_name')
            ->get()
            ->map(function ($item) {
                return $item->getAttributes();
            })
            ->toArray();

        // Append the user's score to the list
        $listNilaipilihan1[] = [
            'username' => $user->email,
            'average_to' => $userTotalNilai,
            'first_name' => $nama->first_name,
            // 'asal_sekolah' => $nama->asal_sekolah
        ];

        // Sort the list in descending order (higher scores first)
        usort($listNilaipilihan1, function($a, $b) {
            return $b['average_to'] <=> $a['average_to'];
        });

        // Determine the user's rank
        $userRank = array_search($user->email, array_column($listNilaipilihan1, 'username')) + 1;

        // Pass the necessary data to the view
        return view('app.siswa.hasilTryout.pilihanTotal', [
            'userRank' => $userRank,
            'nama_prodi' => $nama_prodi,
            'userTotalNilai' => $userTotalNilai,
            'listNilaipilihan1' => $listNilaipilihan1
        ]);
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

    public function rekomendasi()
    {
        $user = Auth::user();
        $siswa = Siswa::where('username', $user->email)->first();

        if ($siswa == null) {
            return response()->json(['error' => "Belum ada data nilai"], 422);
        }

        $siswacheck = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first();
        
        if (empty($siswacheck)) {
            $errorMessage = "Belum ada data nilai";
            return response()->json(['error' => $errorMessage], 422);
        }

        $nilai = $siswacheck->average_to;
        // $nilai = 49.3750; 

        $rekomendasi = DB::table('t_prodi')
            ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
            ->join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 'kelulusan.username')
            ->join('t_daya_tampung_prodi', 't_daya_tampung_prodi.id_prodi', '=', 't_prodi.id_prodi')
            ->select('t_prodi.id_prodi', 't_daya_tampung_prodi.daya_tampung', 'view_rekapitulasi_nilai_to_sebelum2.average_to')
            ->groupBy('t_prodi.id_prodi', 't_daya_tampung_prodi.daya_tampung', 'view_rekapitulasi_nilai_to_sebelum2.average_to')
            ->having('view_rekapitulasi_nilai_to_sebelum2.average_to', '<=', $nilai)
            ->orderBy('view_rekapitulasi_nilai_to_sebelum2.average_to', 'desc')
            ->get();
        

        $listNilai2 = SiswaOld::whereIn('pilihan1_utbk_aktual', $rekomendasi->pluck('id_prodi'))
            ->orWhereIn('pilihan2_utbk_aktual', $rekomendasi->pluck('id_prodi'))
            ->join('view_rekapitulasi_nilai_to_sebelum2', 'view_rekapitulasi_nilai_to_sebelum2.username', '=', 't_siswa.username')
            ->select('t_siswa.pilihan1_utbk_aktual', 't_siswa.pilihan2_utbk_aktual', 'view_rekapitulasi_nilai_to_sebelum2.average_to')
            ->get();

        $debugInfo = [];
        $rekomendasiFinal = [];
        $userScores = [];

        // Group the scores by `id_prodi`
        foreach ($listNilai2 as $item) {
            $id_prodi1 = $item->pilihan1_utbk_aktual;
            $id_prodi2 = $item->pilihan2_utbk_aktual;
            $total_nilai = $item->average_to;

            if (!isset($userScores[$id_prodi1])) {
                $userScores[$id_prodi1] = [];
            }
            if (!isset($userScores[$id_prodi2])) {
                $userScores[$id_prodi2] = [];
            }

            $userScores[$id_prodi1][] = $total_nilai;
            $userScores[$id_prodi2][] = $total_nilai;
        }

        // Track processed id_prodi to avoid duplicates
        $processedProdi = [];

        foreach ($rekomendasi as $check) {
            $id_prodi = $check->id_prodi;

            // Skip if already processed
            if (in_array($id_prodi, $processedProdi)) {
                continue;
            }

            $processedProdi[] = $id_prodi;

            if (!isset($userScores[$id_prodi])) {
                $userScores[$id_prodi] = [];
            }

            // Append the user's score to the list
            $scores = $userScores[$id_prodi];
            $scores[] = $nilai;

            // Sort the scores in descending order
            rsort($scores);

            // Determine the user's rank
            $peringkat = array_search($nilai, $scores) + 1;

            // Calculate the average score for this `id_prodi`

            // Collect debugging information
            $debugInfo[] = ['id_prodi' => $id_prodi, 'peringkat' => $peringkat];

            // Check if the user's rank fits within the program's capacity
            if ($peringkat <= $check->daya_tampung && count($rekomendasiFinal) < 2) {
                $rekomendasiFinal[] = ['id_prodi' => $id_prodi, 'peringkat' => $peringkat];
            }

            // Stop processing if we already have 2 recommendations
            if (count($rekomendasiFinal) >= 2) {
                break;
            }
        }

        // dd($rekomendasiFinal);   
        $prodiRekomendasi = [];
        foreach ($rekomendasiFinal as $rek) {
            $prodiDetail = DB::table('t_prodi')
                ->where('t_prodi.id_prodi', $rek['id_prodi'])
                ->join('t_daya_tampung_prodi', 't_daya_tampung_prodi.id_prodi', '=', 't_prodi.id_prodi')
                ->leftJoin('t_siswa', function ($join) use ($rek) {
                    $join->on('t_siswa.pilihan1_utbk_aktual', '=', DB::raw($rek['id_prodi']))
                        ->orOn('t_siswa.pilihan2_utbk_aktual', '=', DB::raw($rek['id_prodi']));
                })
                ->select('t_prodi.nama_prodi_ptn', DB::raw($rek['peringkat'] . ' as peringkat'), 't_daya_tampung_prodi.daya_tampung', DB::raw('count(t_siswa.username) as total_applicants'))
                ->groupBy('t_prodi.id_prodi', 't_prodi.nama_prodi_ptn', 't_daya_tampung_prodi.daya_tampung')
                ->first();

            $prodiRekomendasi[] = $prodiDetail;
        }


        if (empty($rekomendasiFinal)) {
            return response()->json(['error' => "Tidak ada rekomendasi yang cocok untuk kamu"], 422);
        }

        // dd($prodiRekomendasi);

        return view('app.siswa.hasilTryout.rekomendasi', ['rekomendasi' => $prodiRekomendasi, 'nilai' => $nilai]);
    }
    
}
