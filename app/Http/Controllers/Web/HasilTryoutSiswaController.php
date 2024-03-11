<?php

namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;

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
        $totalPendaftar = Siswa::where('pilihan1_utbk_aktual', $siswa->pilihan1_utbk_aktual)
            ->orWhere('pilihan2_utbk_aktual', $siswa->pilihan1_utbk_aktual)
            ->orWhere('pilihan1_utbk_aktual', $siswa->pilihan2_utbk_aktual)
            ->orWhere('pilihan2_utbk_aktual', $siswa->pilihan2_utbk_aktual)
            ->join('mv_rekapitulasi_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_siswa.username')
            ->pluck('mv_rekapitulasi_nilai_to.username')
            ->toArray();

        $totalSekolah = Siswa::distinct()
            ->select('pilihan1_utbk_aktual')
            ->orWhere('pilihan2_utbk_aktual')
            ->count();

        return view('app.siswa.hasilTryout.main', compact('user', 'siswa', 'totalPendaftar', 'totalSekolah'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
