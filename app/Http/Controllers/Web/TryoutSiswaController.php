<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Nilai;
use App\Models\Nilaito;

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

        if(empty($tryouts)){
            $errorMessage = "Belum ada nilai tryout";
            return response()->json(['error' => $errorMessage], 422);
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

        return view('app.siswa.tryoutSaya.main', compact('tryouts', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm'));
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
            
                return view("app.siswa.tryoutSaya.detail", compact('tryout', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'rata'));
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