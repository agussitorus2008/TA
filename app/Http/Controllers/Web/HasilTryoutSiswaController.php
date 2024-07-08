<?php

namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TSiswa;
use App\Models\TNilaiTo;
use App\Models\Nilai;
use App\Models\Prodi;
use App\Models\DayaTampung;
use App\Models\ViewNilaiFinal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $siswa = TSiswa::where('username', $user->email)->first();
        
        $errorMessage = 1;

        $totalPendaftar = 0;
        $totalSekolah = 0;
        $nilaito = 0;
        $peringkat1 = 0;
        $peringkat2 = 0;
        $totalpendaftar11 = 0;
        $totalpendaftar22 = 0;
        $totalpendaftar1 = 0;
        $totalpendaftar2 = 0;
        $peringkat11 = 0;
        $peringkat22 = 0;
        $dayatampung1 = 0;
        $dayatampung2 = 0;
        $totalData = 0;
        $rataData = 0;
        $tryoutCount = 0;
        $nilaiRata = 0;

        // if ($siswa == null) {
        //     $errorMessage = "Belum ada data nilai";
        // }

        $nilaito = Nilai::where('username', $user->email)
            ->join('t_to', 't_to.id_to', '=', 'mv_rekapitulasi_nilai_to.id_to')
            ->select('mv_rekapitulasi_nilai_to.*', 't_to.tanggal') // Adjust the select clause to retrieve specific columns if needed
            ->get()
            ->map(function ($item) {
            $item->tanggal = Carbon::parse($item->tanggal)->format('d-m-y');
            return $item;
        });
        $tryoutCount = $nilaito->count();
        $siswacheck = ViewNilaiFinal::where('username', auth()->user()->email)->first();

        if(empty($siswa)){
            $errorMessage = 2;
        }
        else if(empty($siswacheck) || $nilaito == null) {
            $errorMessage = 0;
        }
        else{
            $totalNilai = $siswacheck->average_to;
            $allnilai = Nilai::where('username', $user->email)->get();
        
            $totalSekolah = TSiswa::distinct()
                ->select('asal_sekolah')
                ->orWhere('pilihan2_utbk', $siswa->pilihan1_utbk)
                ->orWhere('pilihan1_utbk', $siswa->pilihan2_utbk)
                ->orWhere('pilihan2_utbk', $siswa->pilihan2_utbk)
                ->count();

            $dayatampung1 = DayaTampung::where('id_prodi', $siswa->pilihan1_utbk)
            ->where('tahun', 2024)
            ->first();

            $dayatampung2 = DayaTampung::where('id_prodi', $siswa->pilihan2_utbk)
            ->where('tahun', 2024)
            ->first();

            $listNilai1 = TSiswa::join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
                ->where(function($query) use ($siswa) {
                    $query->where('t_siswa.pilihan1_utbk', $siswa->pilihan1_utbk)
                            ->orWhere('t_siswa.pilihan2_utbk', $siswa->pilihan1_utbk);
                })
                ->where('t_siswa.username', '!=', $siswa->username)
                ->pluck('view_rekapitulasi_nilai_to.average_to')
                ->toArray();


            $listNilai2 = TSiswa::join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
                ->where(function($query) use ($siswa) {
                    $query->where('t_siswa.pilihan1_utbk', $siswa->pilihan2_utbk)
                            ->orWhere('t_siswa.pilihan2_utbk', $siswa->pilihan2_utbk);
                })
                ->where('t_siswa.username', '!=', $siswa->username)
                ->pluck('view_rekapitulasi_nilai_to.average_to')
                ->toArray();
            

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

            $totalPendaftar = $totalpendaftar1 + $totalpendaftar2;

            // untuk pilihan 1
            $listNilaipilihan1 = TSiswa::where('t_siswa.pilihan1_utbk', $siswa->pilihan1_utbk)
                ->where('t_siswa.username', '!=', $siswa->username)
                ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
                ->pluck('view_rekapitulasi_nilai_to.average_to')
                ->toArray();
        
            $listNilaipilihan2 = TSiswa::where('t_siswa.pilihan1_utbk', $siswa->pilihan2_utbk)
                ->where('t_siswa.username', '!=', $siswa->username)
                ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
                ->pluck('view_rekapitulasi_nilai_to.average_to')
                ->toArray();

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

            $nilaiRata = ViewNilaiFinal::where('username', auth()->user()->email)->first()->average_to;

            if ($nilaiRata == null) {
                $errorMessage = "Belum ada nilai diatur";
                return response()->json(['error' => $errorMessage], 422);
            }
        }
        return view('app.siswa.hasilTryout.main', compact('errorMessage', 'user', 'siswa', 'totalPendaftar', 'totalSekolah', 'nilaito', 'peringkat1', 'peringkat2', 'totalpendaftar11', 'totalpendaftar22', 'totalpendaftar1', 'totalpendaftar2', 'peringkat11', 'peringkat22', 'dayatampung1', 'dayatampung2', 'totalData', 'rataData', 'tryoutCount', 'nilaiRata'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pilihan1($nama_prodi)
    {
        $prodi = Prodi::where('nama_prodi_ptn', $nama_prodi)->first();
        if (!$prodi) {
            abort(404, 'Program not found');
        }

        $user = auth()->user();
        $userTotalNilai = ViewNilaiFinal::where('username', $user->email)->first()->average_to;
        
        $nama = TSiswa::where('username',$user->email)->first();

        $listNilaipilihan1 = TSiswa::where('t_siswa.pilihan1_utbk', $prodi->id_prodi)
            ->where('t_siswa.username', '!=', $user->email)
            ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->select('t_siswa.username', 'view_rekapitulasi_nilai_to.average_to', 't_siswa.first_name')
            ->get()
            ->map(function ($item) {
                return $item->getAttributes();
            })
            ->toArray();

        $listNilaipilihan1[] = [
            'username' => $user->email,
            'average_to' => $userTotalNilai,
            'first_name' => $nama->first_name,
            // 'asal_sekolah' => $nama->asal_sekolah
        ];

        usort($listNilaipilihan1, function($a, $b) {
            return $b['average_to'] <=> $a['average_to'];
        });

        $userRank = array_search($user->email, array_column($listNilaipilihan1, 'username')) + 1;

        return view('app.siswa.hasilTryout.pilihan1', [
            'userRank' => $userRank,
            'nama_prodi' => $nama_prodi,
            'userTotalNilai' => $userTotalNilai,
            'listNilaipilihan1' => $listNilaipilihan1
        ]);
    }

    public function pilihanTotal($nama_prodi)
    {
        $prodi = Prodi::where('nama_prodi_ptn', $nama_prodi)->first();
        if (!$prodi) {
            abort(404, 'Program not found');
        }

        $user = auth()->user();
        $userTotalNilai = ViewNilaiFinal::where('username', $user->email)->first()->average_to;
        $nama = TSiswa::where('username',$user->email)->first();

        $listNilaipilihan1 = TSiswa::where(function($query) use ($prodi, $user) {
            $query->where('t_siswa.pilihan1_utbk', $prodi->id_prodi)
                  ->orWhere('t_siswa.pilihan2_utbk', $prodi->id_prodi);
        })
        ->where('t_siswa.username', '!=', $user->email)
        ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
        ->select('t_siswa.username', 'view_rekapitulasi_nilai_to.average_to', 't_siswa.first_name')
        ->get()
        ->map(function ($item) {
            return $item->getAttributes();
        })
        ->toArray();        

        $listNilaipilihan1[] = [
            'username' => $user->email,
            'average_to' => $userTotalNilai,
            'first_name' => $nama->first_name,
            // 'asal_sekolah' => $nama->asal_sekolah
        ];

        usort($listNilaipilihan1, function($a, $b) {
            return $b['average_to'] <=> $a['average_to'];
        });

        $userRank = array_search($user->email, array_column($listNilaipilihan1, 'username')) + 1;

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
        $prodiRekomendasi = [];
        $errorMessage = 1;

        $siswa = TSiswa::where('username', $user->email)->first();
        $siswacheck = ViewNilaiFinal::where('username', auth()->user()->email)->first();
        $nilai = 0;
        $prodiRekomendasi = [];

        if ($siswa == null) {
            $errorMessage = 2;
        }
        elseif (empty($siswacheck)) {
            $errorMessage = 0;
        }
        else{
            $nilai = $siswacheck->average_to;

            $rekomendasi = DB::table('t_prodi')
            ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
            ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
            ->join('t_daya_tampung_prodi', 't_daya_tampung_prodi.id_prodi', '=', 't_prodi.id_prodi')
            ->select('t_prodi.id_prodi', 't_daya_tampung_prodi.daya_tampung', 'view_rekapitulasi_nilai_to.average_to')
            ->groupBy('t_prodi.id_prodi', 't_daya_tampung_prodi.daya_tampung', 'view_rekapitulasi_nilai_to.average_to')
            ->having('view_rekapitulasi_nilai_to.average_to', '<=', $nilai)
            ->orderBy('view_rekapitulasi_nilai_to.average_to', 'desc')
            ->get();
        

            $listNilai2 = TSiswa::whereIn('pilihan1_utbk', $rekomendasi->pluck('id_prodi'))
                ->orWhereIn('pilihan2_utbk', $rekomendasi->pluck('id_prodi'))
                ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
                ->select('t_siswa.pilihan1_utbk', 't_siswa.pilihan2_utbk', 'view_rekapitulasi_nilai_to.average_to')
                ->get();

            $debugInfo = [];
            $rekomendasiFinal = [];
            $userScores = [];

            foreach ($listNilai2 as $item) {
                $id_prodi1 = $item->pilihan1_utbk;
                $id_prodi2 = $item->pilihan2_utbk;
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

            $processedProdi = [];

            foreach ($rekomendasi as $check) {
                $id_prodi = $check->id_prodi;

                if (in_array($id_prodi, $processedProdi)) {
                    continue;
                }

                $processedProdi[] = $id_prodi;

                if (!isset($userScores[$id_prodi])) {
                    $userScores[$id_prodi] = [];
                }

                $scores = $userScores[$id_prodi];
                $scores[] = $nilai;

                rsort($scores);

                $peringkat = array_search($nilai, $scores) + 1;

                if ($peringkat <= $check->daya_tampung && count($rekomendasiFinal) < 2) {
                    $rekomendasiFinal[] = ['id_prodi' => $id_prodi, 'peringkat' => $peringkat];
                }

                if (count($rekomendasiFinal) >= 2) {
                    break;
                }
            }

            if (empty($rekomendasiFinal) || count($rekomendasiFinal) == 1) {
                $rekomendasiFinal = null;
            }
            else
            {
                foreach ($rekomendasiFinal as $rek) {
                    $prodiDetail = DB::table('t_prodi')
                        ->where('t_prodi.id_prodi', $rek['id_prodi'])
                        ->join('t_daya_tampung_prodi', 't_daya_tampung_prodi.id_prodi', '=', 't_prodi.id_prodi')
                        ->leftJoin('t_siswa', function ($join) use ($rek) {
                            $join->on('t_siswa.pilihan1_utbk', '=', DB::raw($rek['id_prodi']))
                                ->orOn('t_siswa.pilihan2_utbk', '=', DB::raw($rek['id_prodi']));
                        })
                        ->select('t_prodi.nama_prodi_ptn', DB::raw($rek['peringkat'] . ' as peringkat'), 't_daya_tampung_prodi.daya_tampung', DB::raw('count(t_siswa.username) as total_applicants'))
                        ->groupBy('t_prodi.id_prodi', 't_prodi.nama_prodi_ptn', 't_daya_tampung_prodi.daya_tampung')
                        ->first();
    
                    $prodiRekomendasi[] = $prodiDetail;
                }
            }
        }
        return view('app.siswa.hasilTryout.rekomendasi', ['rekomendasi' => $prodiRekomendasi, 'nilai' => $nilai, 'errorMessage' => $errorMessage]);
    }
    
}
