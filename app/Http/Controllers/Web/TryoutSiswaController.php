<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Nilai;
use App\Models\Nilaito;
use App\Models\ViewNilaiFinalTerbaru;

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
        $tryouts = Nilaito::where('username', $user->email)->get();

        if($tryouts){

            $bobot_ppu = 30;
            $bobot_pu = 20;
            $bobot_pm = 20;
            $bobot_pk = 15;
            $bobot_lbi = 30;
            $bobot_lbe = 20;
            $bobot_pbm = 20;
            $bobot_total = 155;

            $mahasiswa = ViewNilaiFinalTerbaru::where('username', auth()->user()->email)->first();

            $nilaiRata = 0;

            if($mahasiswa){
                $nilaiRata = $mahasiswa->average_to;
            }

        }
        return view('app.siswa.tryoutSaya.main', compact('tryouts', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'nilaiRata', 'bobot_total'));
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
        $tryout = Nilaito::where('nama_tryout', $nama_tryout)
            ->where('username', $user->email)
            ->first();

            if($tryout){

                $bobot_ppu = 30;
                $bobot_pu = 20;
                $bobot_pm = 20;
                $bobot_pk = 15;
                $bobot_lbi = 30;
                $bobot_lbe = 20;
                $bobot_pbm = 20;
                $bobot_total = 155;
            
                return view("app.siswa.tryoutSaya.detail", compact('tryout', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'rata', 'bobot_total'));
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