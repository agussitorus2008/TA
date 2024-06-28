<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\TSiswa;
use App\Models\Sekolah;
use App\Models\Tryout;
use App\Models\TNilaito;
use App\Models\Nilai;
use App\Models\Kelulusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TryoutController extends Controller
{
    public function add($username)
    {
        $siswa = TSiswa::where('username', $username)->first();
        $nama_tryout = TNilaito::where('username', $username)->count();
        return view("app.admin.siswa.add", compact('siswa', 'nama_tryout'));
    }

    public function Store(Request $request, $username)
    {
        $request->validate([
            'hitungan_tryout' => 'unique:nilai_to,nama_tryout,NULL,id,username,' . $username
        ], [
            'hitungan_tryout.unique' => 'Sudah ada nilai tryout',
        ]);

        $siswa = TSiswa::where('username', $username)->first();  

        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan');
        }

        // Simpan nilai tryout baru
        $tryout = new TNilaito;
        $tryout->username = $username;
        $tryout->id_to = 28;
        $tryout->nama_tryout = $request->nama_tryout;
        $tryout->tanggal = $request->tanggal;
        $tryout->ppu = $request->ppu;
        $tryout->pu = $request->pu;
        $tryout->pm = $request->pm;
        $tryout->pk = $request->pk;
        $tryout->lbi = $request->lbi;
        $tryout->lbe = $request->lbe;
        $tryout->pbm = $request->pbm;

        $tryout->save();

        $finalTo = new Nilai;
        $finalTo->id_to = 28;
        $finalTo->id_nilai_to = $tryout->id;
        $finalTo->username = $username;
        $finalTo->first_name = $siswa->first_name;
        $finalTo->asal_sekolah = $siswa->asal_sekolah;

        $finalTo->pu_benar = $tryout->pu;
        $finalTo->nilai_pu = $tryout->pu/30 * 100 ;

        $finalTo->ppu_benar = $tryout->ppu;
        $finalTo->nilai_ppu = $tryout->ppu/20 * 100;

        $finalTo->pm_benar = $tryout->pm;
        $finalTo->nilai_pm = $tryout->pm/20 * 100;

        $finalTo->pk_benar = $tryout->pk;
        $finalTo->nilai_pk = $tryout->pk/15 * 100;

        $finalTo->lbi_benar = $tryout->lbi;
        $finalTo->nilai_lbi = $tryout->lbi/30 * 100;

        $finalTo->lbe_benar = $tryout->lbe;
        $finalTo->nilai_lbe = $tryout->lbe/20 * 100;

        $finalTo->pbm_benar = $tryout->pbm;
        $finalTo->nilai_pbm = $tryout->pbm/20 * 100;

        $finalTo->total_benar = $tryout->pu + $tryout->ppu + $tryout->pm + $tryout->pk + $tryout->lbi + $tryout->lbe + $tryout->pbm;
        $finalTo->total_nilai = $finalTo->total_benar/155 * 100;
        $finalTo->save(); 

        $nilaiSiswa = Nilai::where('username', $username)->get();

        // $total = 0;
        // foreach($nilaiSiswa as $nilai){
        //     $total += $nilai->total_nilai;
        // }        

        // // Calculate the average score
        // if($nilaiSiswa->count() > 0) {
        //     $total = $total / $nilaiSiswa->count();
        // } else {
        //     $total = 0;
        // }

        // $nilaiFinal = ViewNilaiFinal::where('username', $username)->first();
        // if (!$nilaiFinal) {
        //     $nilaiFinal = new ViewNilaiFinal;
        //     $nilaiFinal->username = $username;
        //     $nilaiFinal->pilihan1_utbk = $siswa->pilihan1_utbk_aktual;
        //     $nilaiFinal->pilihan2_utbk = $siswa->pilihan2_utbk_aktual;
        // }

        // $nilaiFinal->average_to = $total;
        // $nilaiFinal->save();

        return redirect()->route("admin.siswa.tryout", $username)->with('success', 'Nilai Tryout berhasil ditambahkan');
    }   
       
    public function update(Request $request, $username, $nama_tryout)
    {
        $tryout = TNilaito::where('nama_tryout', $nama_tryout)
                 ->where('username', $username)
                 ->first();

        if ($tryout) {
            $tryout->tanggal = $request->tanggal;
            $tryout->ppu = $request->ppu;
            $tryout->pu = $request->pu;
            $tryout->pm = $request->pm;
            $tryout->pk = $request->pk;
            $tryout->lbi = $request->lbi;
            $tryout->lbe = $request->lbe;
            $tryout->pbm = $request->pbm;

            $mvto = Nilai::where('id_nilai_to', $tryout->id)->first();

            if ($mvto) {
                $mvto->pu_benar = $tryout->pu;
                $mvto->nilai_pu = $tryout->pu/30 * 100 ;
        
                $mvto->ppu_benar = $tryout->ppu;
                $mvto->nilai_ppu = $tryout->ppu/20 * 100;
        
                $mvto->pm_benar = $tryout->pm;
                $mvto->nilai_pm = $tryout->pm/20 * 100;
        
                $mvto->pk_benar = $tryout->pk;
                $mvto->nilai_pk = $tryout->pk/15 * 100;
        
                $mvto->lbi_benar = $tryout->lbi;
                $mvto->nilai_lbi = $tryout->lbi/30 * 100;
        
                $mvto->lbe_benar = $tryout->lbe;
                $mvto->nilai_lbe = $tryout->lbe/20 * 100;
        
                $mvto->pbm_benar = $tryout->pbm;
                $mvto->nilai_pbm = $tryout->pbm/20 * 100;
        
                $mvto->total_benar = $tryout->pu + $tryout->ppu + $tryout->pm + $tryout->pk + $tryout->lbi + $tryout->lbe + $tryout->pbm;
                $mvto->total_nilai = $mvto->total_benar/155 * 100;

                $mvto->save(); 
            }

            $tryout->save();
        }
        // $nilaiSiswa = Nilaito::where('username', $username)->get();
        // $finalTo = Nilai::where('username', $username)->first();

        // $total = 0;
        // foreach($nilaiSiswa as $nilai){
        //     $total += ($nilai->ppu * $bobot_ppu + $nilai->pu * $bobot_pu + $nilai->pk * $bobot_pk + $nilai->lbi * $bobot_lbi + $nilai->lbe * $bobot_lbe + $nilai->pbm * $bobot_pbm + $nilai->pm * $bobot_pm) / 7;
        // }        

        // // Calculate the average score
        // if($nilaiSiswa->count() > 0) {
        //     $total = $total / $nilaiSiswa->count();
        // } else {
        //     $total = 0;
        // }

        // // Update the total_nilai
        // $finalTo->total_nilai = $total;
        // $finalTo->save();

        return redirect()->route("admin.siswa.tryout", $username)->with('success', 'Nilai Tryout berhasil diubah');   
    }

    public function detail_tryout($username, $nama_tryout, $rata)
    {
        $siswa = TSiswa::where('username', $username)->first();
        $tryout = TNilaito::where('nama_tryout', $nama_tryout)
            ->where('username', $username)
            ->first();

        if($siswa){

        $bobot_ppu = 30;
        $bobot_pu = 20;
        $bobot_pm = 20;
        $bobot_pk = 15;
        $bobot_lbi = 30;
        $bobot_lbe = 20;
        $bobot_pbm = 20;
        $bobot_total = 155;
    
    
        return view("app.admin.siswa.tryoutdetail", compact('siswa', 'tryout', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'rata'));
        }

        else{
            return redirect()->route("admin.siswa.tryout", $username)->with('error', 'Tidak ada data siswa');
        }
    }

    public function edit($username, $nama_tryout)
    {
        $siswa = TSiswa::where('username', $username)->first();
        $tryout = TNilaito::where('nama_tryout', $nama_tryout)
            ->where('username', $username)
            ->first();
    
        return view("app.admin.siswa.edit", compact('siswa', 'tryout'));
    }


    public function destroy($username, $nama_tryout)
    {
        $tryout = TNilaito::where('nama_tryout', $nama_tryout)
                 ->where('username', $username)
                 ->first();

        if ($tryout) {
            $mvto = Nilai::where('id_nilai_to', $tryout->id)->first();

            if ($mvto) {
                $mvto->delete();
            }

            $tryout->delete();
        }
        

        // $bobot = Nilai::whereNotNull('nilai_ppu')
        // ->whereNotNull('nilai_pu')
        // ->whereNotNull('nilai_pm')
        // ->whereNotNull('nilai_pk')
        // ->whereNotNull('nilai_lbi')
        // ->whereNotNull('nilai_lbe')
        // ->whereNotNull('nilai_pbm')
        // ->first();

        // $bobot_ppu = $bobot->nilai_ppu / $bobot->ppu_benar;
        // $bobot_pu = $bobot->nilai_pu / $bobot->pu_benar;
        // $bobot_pm = $bobot->nilai_pm / $bobot->pm_benar;
        // $bobot_pk = $bobot->nilai_pk / $bobot->pk_benar;
        // $bobot_lbi = $bobot->nilai_lbi / $bobot->lbi_benar;
        // $bobot_lbe = $bobot->nilai_lbe / $bobot->lbe_benar;
        // $bobot_pbm = $bobot->nilai_pbm / $bobot->pbm_benar;

        // $nilaiSiswa = Nilaito::where('username', $username)->get();
        // $finalTo = Nilai::where('username', $username)->first();

        // $total = 0;
        // foreach($nilaiSiswa as $nilai){
        //     $total += ($nilai->ppu * $bobot_ppu + $nilai->pu * $bobot_pu + $nilai->pk * $bobot_pk + $nilai->lbi * $bobot_lbi + $nilai->lbe * $bobot_lbe + $nilai->pbm * $bobot_pbm + $nilai->pm * $bobot_pm) / 7;
        // }        

        // // Calculate the average score
        // if($nilaiSiswa->count() > 0) {
        //     $total = $total / $nilaiSiswa->count();
        // } else {
        //     $total = 0;
        // }

        // // Update the total_nilai
        // $finalTo->total_nilai = $total;
        // $finalTo->save();
          
        return redirect()->route("admin.siswa.tryout", $username)->with('success', 'Nilai Tryout berhasil dihapus');    
    }
}
