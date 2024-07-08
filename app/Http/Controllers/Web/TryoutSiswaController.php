<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Nilai;
use App\Models\TNilaito;
use App\Models\ViewNilaiFinal;
use Illuminate\Support\Facades\DB;


class TryoutSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $nilaiRata = 0;
        $tryouts = DB::table('mv_rekapitulasi_nilai_to')
            ->select(
                'mv_rekapitulasi_nilai_to.*',
                't_to.*'
            )
            ->join('t_to', 'mv_rekapitulasi_nilai_to.id_to', '=', 't_to.id_to')
            ->where('mv_rekapitulasi_nilai_to.username', $user->email)
            ->get();

        if($tryouts){
            $mahasiswa = ViewNilaiFinal::where('username', auth()->user()->email)->first();

            if($mahasiswa){
                $nilaiRata = $mahasiswa->average_to;
            }

        }
        return view('app.siswa.tryoutSaya.main', compact('tryouts', 'nilaiRata'));
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
    public function show($nama_tryout, $rata)
    {
        $user = Auth::user();

        $tryout = DB::table('mv_rekapitulasi_nilai_to')
            ->select(
                'mv_rekapitulasi_nilai_to.*',
                't_to.*'
            )
            ->join('t_to', 'mv_rekapitulasi_nilai_to.id_to', '=', 't_to.id_to')
            ->where('mv_rekapitulasi_nilai_to.username', $user->email)
            ->where('mv_rekapitulasi_nilai_to.id_to', $nama_tryout)
            ->first();


            if($tryout){
            
                return view("app.siswa.tryoutSaya.detail", compact('tryout', 'rata'));
                }
        
                else{
                    return redirect()->route("admin.siswa.tryout", $username)->with('error', 'Tidak ada data siswa');
                }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('app.siswa.tryoutSaya.detail');
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