<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\TSiswa;
use App\Models\Sekolah;
use App\Models\Tryout;
use App\Models\TNilaito;
use App\Models\Nilai;
use App\Models\TTo;
use App\Models\Kelulusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class TryoutController extends Controller
{
    public function add($username)
    {
        $siswa = TSiswa::where('username', $username)->first();
        $testTryout = TNilaito::where('username', $username)->latest()->first();

        $tahunSekarang = $siswa->active;

        $lastTryoutCount = TNilaiTo::where('active', $tahunSekarang)->where('username', $username)->count();

        $dataTryout = TTo::where('active', $tahunSekarang)->get();

        if ($lastTryoutCount == 0) {
            $nama_tryout = 'Tryout-1';
        } else {
            $nama_tryout = 'Tryout-' . ($lastTryoutCount + 1);
        }

        $existingTryout = TTo::where('active', $tahunSekarang)
                            ->where('nama_to', $nama_tryout)
                            ->first();

        $tanggal_tryout = null;

        if (!$existingTryout) {
            $nama_tryout = null;
        }else{
            $tanggal_tryout = $existingTryout->tanggal;
        }

        return view("app.admin.siswa.add", compact('siswa', 'nama_tryout', 'dataTryout', 'tanggal_tryout'));
    }

    public function Store(Request $request, $username)
    {
        $siswa = TSiswa::where('username', $username)->first();  

        $tahunSekarang = $siswa->active;

        $latestTryout = TTo::where('active', $tahunSekarang)
            ->where('nama_to', $request->input('nama_to'))
            ->first();

        $request->validate([
            'nama_to' => [
                'required',
                function ($attribute, $value, $fail) use ($username, $latestTryout) {
                    $exists = DB::table('t_nilai_to')
                        ->join('t_to', 't_nilai_to.id_to', '=', 't_to.id_to')
                        ->where('t_to.nama_to', $value)
                        ->where('t_nilai_to.username', $username)
                        ->where('t_nilai_to.id_to', '=', $latestTryout->id_to)
                        ->exists();
                    if ($exists) {
                        $fail('Sudah ada nilai tryout dengan nama ' . $value . ' untuk pengguna ini.');
                    }
                }
            ],
            't_ppu' => 'integer',
            't_pu' => 'integer',
            't_pm' => 'integer',
            't_pk' => 'integer',
            't_lbi' => 'integer',
            't_lbe' => 'integer',
            't_pbm' => 'integer',
            'total' => 'integer',
            'tanggal' => 'date',
            'tahun' => 'integer',
        ], [
            'nama_to.required' => 'Nama tryout harus diisi',
            't_ppu.integer' => 'Masukkan angka yang valid untuk PPU',
            't_pu.integer' => 'Masukkan angka yang valid untuk PU',
            't_pm.integer' => 'Masukkan angka yang valid untuk PM',
            't_pk.integer' => 'Masukkan angka yang valid untuk PK',
            't_lbi.integer' => 'Masukkan angka yang valid untuk LBI',
            't_lbe.integer' => 'Masukkan angka yang valid untuk LBE',
            't_pbm.integer' => 'Masukkan angka yang valid untuk PBM',
            'total.integer' => 'Masukkan angka yang valid untuk Total',
            'tanggal.date' => 'Masukkan tanggal yang valid',
            'tahun.integer' => 'Masukkan tahun yang valid',
        ]);

        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan');
        }

        // Simpan nilai tryout baru
        $tryout = new TNilaito;
        $tryout->username = $username;
        $tryout->id_to = $latestTryout->id_to;
        $tryout->tanggal = $request->tanggal;
        $tryout->ppu = $request->ppu;
        $tryout->pu = $request->pu;
        $tryout->pm = $request->pm;
        $tryout->pk = $request->pk;
        $tryout->lbi = $request->lbi;
        $tryout->lbe = $request->lbe;
        $tryout->pbm = $request->pbm;
        $tryout->active = $siswa->active;
        $tryout->save();

        $finalTo = new Nilai;
        $finalTo->id_to = $latestTryout->id_to;
        $finalTo->id_nilai_to = $tryout->id;
        $finalTo->username = $username;
        $finalTo->first_name = $siswa->first_name;
        $finalTo->asal_sekolah = $siswa->asal_sekolah;

        $finalTo->pu_benar = $tryout->pu;
        $finalTo->nilai_pu = $tryout->pu/$latestTryout->t_pu * 100 ;

        $finalTo->ppu_benar = $tryout->ppu;
        $finalTo->nilai_ppu = $tryout->ppu/$latestTryout->t_ppu * 100;

        $finalTo->pm_benar = $tryout->pm;
        $finalTo->nilai_pm = $tryout->pm/$latestTryout->t_pm * 100;

        $finalTo->pk_benar = $tryout->pk;
        $finalTo->nilai_pk = $tryout->pk/$latestTryout->t_pk * 100;

        $finalTo->lbi_benar = $tryout->lbi;
        $finalTo->nilai_lbi = $tryout->lbi/$latestTryout->t_lbi * 100;

        $finalTo->lbe_benar = $tryout->lbe;
        $finalTo->nilai_lbe = $tryout->lbe/$latestTryout->t_lbe * 100;

        $finalTo->pbm_benar = $tryout->pbm;
        $finalTo->nilai_pbm = $tryout->pbm/$latestTryout->t_pbm * 100;

        $finalTo->total_benar = $tryout->pu + $tryout->ppu + $tryout->pm + $tryout->pk + $tryout->lbi + $tryout->lbe + $tryout->pbm;
        $finalTo->total_nilai = $finalTo->total_benar/$latestTryout->total * 100;
        $finalTo->save(); 

        $nilaiSiswa = Nilai::where('username', $username)->get();

        return redirect()->route("admin.siswa.tryout", $username)->with('success', 'Nilai Tryout berhasil ditambahkan');
    }   
       
    public function update(Request $request, $username, $id_to)
    {
        $tryout = TNilaito::where('id_to', $id_to)
                 ->where('username', $username)
                 ->first();
        
        $siswa = TSiswa::where('username', $username)->first(); 
        $tahunSekarang = $siswa->active;

        $latestTryout = TTo::where('active', $tahunSekarang)
            ->where('id_to', $id_to)
            ->first();

        // dd($latestTryout);

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
                $mvto->nilai_pu = $tryout->pu/$latestTryout->t_pu * 100 ;
        
                $mvto->ppu_benar = $tryout->ppu;
                $mvto->nilai_ppu = $tryout->ppu/$latestTryout->t_ppu * 100;
        
                $mvto->pm_benar = $tryout->pm;
                $mvto->nilai_pm = $tryout->pm/$latestTryout->t_pm * 100;
        
                $mvto->pk_benar = $tryout->pk;
                $mvto->nilai_pk = $tryout->pk/$latestTryout->t_pk * 100;
        
                $mvto->lbi_benar = $tryout->lbi;
                $mvto->nilai_lbi = $tryout->lbi/$latestTryout->t_lbi * 100;
        
                $mvto->lbe_benar = $tryout->lbe;
                $mvto->nilai_lbe = $tryout->lbe/$latestTryout->t_lbe * 100;
        
                $mvto->pbm_benar = $tryout->pbm;
                $mvto->nilai_pbm = $tryout->pbm/$latestTryout->t_pbm * 100;
        
                $mvto->total_benar = $tryout->pu + $tryout->ppu + $tryout->pm + $tryout->pk + $tryout->lbi + $tryout->lbe + $tryout->pbm;
                $mvto->total_nilai = $mvto->total_benar/$latestTryout->total * 100;

                $mvto->save(); 
            }

            $tryout->save();
        }
        return redirect()->route("admin.siswa.tryout", $username)->with('success', 'Nilai Tryout berhasil diubah');   
    }

    public function detail_tryout($username, $id_to, $rata)
    {
        $siswa = TSiswa::where('username', $username)->first();

        $tryout = TNilaito::where('id_to', $id_to)
            ->where('username', $username)
            ->first(); 
        
        $total_benar = Nilai::where('id_to', $id_to)
        ->where('username', $username)
        ->first();

        $tahunSekarang = $siswa->active;

        $datatryout = TTo::where('active', $tahunSekarang)
            ->where('id_to', $id_to)
            ->first();

        if($siswa){

        $bobot_ppu = $datatryout->t_ppu;
        $bobot_pu = $datatryout->t_pu;
        $bobot_pm = $datatryout->t_pm;
        $bobot_pk = $datatryout->t_pk;
        $bobot_lbi = $datatryout->t_lbi;
        $bobot_lbe = $datatryout->t_lbe;
        $bobot_pbm = $datatryout->t_pbm;
        $bobot_total = $datatryout->total;

        return view("app.admin.siswa.tryoutdetail", compact('siswa', 'tryout', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'rata', 'bobot_total', 'total_benar'));
        }

        else{
            return redirect()->route("admin.siswa.tryout", $username)->with('error', 'Tidak ada data siswa');
        }
    }

    public function edit($username, $id_to)
    {
        $siswa = TSiswa::where('username', $username)->first();
        $tryout = TNilaito::where('id_to', $id_to)
            ->where('username', $username)
            ->first();
    
        return view("app.admin.siswa.edit", compact('siswa', 'tryout'));
    }


    public function destroy($username, $id_to)
    {
        $tryout = TNilaito::where('id_to', $id_to)
                 ->where('username', $username)
                 ->first();

        if ($tryout) {
            $mvto = Nilai::where('id_nilai_to', $tryout->id)->first();

            if ($mvto) {
                $mvto->delete();
            }

            $tryout->delete();
        } 
        return redirect()->route("admin.siswa.tryout", $username)->with('success', 'Nilai Tryout berhasil dihapus');    
    }
}
