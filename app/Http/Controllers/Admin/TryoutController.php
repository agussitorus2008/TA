<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Tryout;
use App\Models\Nilaito;
use App\Models\Nilai;
use App\Models\Kelulusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TryoutController extends Controller
{
    public function add($username)
    {
        $siswa = Siswa::where('username', $username)->first();
        $nama_tryout = Nilaito::where('username', $username)->count();
        return view("app.admin.siswa.add", compact('siswa', 'nama_tryout'));
    }

    public function Store(Request $request, $username)
    {
        $request->validate([
            'nama_tryout' => 'unique:nilai_to,nama_tryout,NULL,id,username,' . $username
        ], [
            'nama_tryout.unique' => 'Sudah ada nilai tryout',
        ]);

        // Ambil data siswa berdasarkan username
        $siswa = Siswa::where('username', $username)->first();

        // Pastikan siswa ditemukan sebelum melanjutkan
        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan');
        }

        // Simpan nilai tryout baru
        $tryout = new Nilaito;
        $tryout->username = $username;
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

        // Cek apakah sudah ada nilai TO sebelumnya
        $finalTo = Nilai::where('username', $username)->first();


        if (!$finalTo) {
            // Jika tidak ada, buat baru
            $finalTo = new Nilai;
            $finalTo->id_to = 100; // Anda mungkin perlu menyesuaikan ini
            $finalTo->username = $username;
            $finalTo->first_name = $siswa->first_name;
            $finalTo->asal_sekolah = $siswa->asal_sekolah;
        }

        $bobot = Nilai::whereNotNull('nilai_ppu')
        ->whereNotNull('nilai_pu')
        ->whereNotNull('nilai_pm')
        ->whereNotNull('nilai_pk')
        ->whereNotNull('nilai_lbi')
        ->whereNotNull('nilai_lbe')
        ->whereNotNull('nilai_pbm')
        ->first();

        $bobot_ppu = $bobot->nilai_ppu / $bobot->ppu_benar;
        $bobot_pu = $bobot->nilai_pu / $bobot->pu_benar;
        $bobot_pm = $bobot->nilai_pm / $bobot->pm_benar;
        $bobot_pk = $bobot->nilai_pk / $bobot->pk_benar;
        $bobot_lbi = $bobot->nilai_lbi / $bobot->lbi_benar;
        $bobot_lbe = $bobot->nilai_lbe / $bobot->lbe_benar;
        $bobot_pbm = $bobot->nilai_pbm / $bobot->pbm_benar;

        // Update nilai TO
        $finalTo->pu_benar = $tryout->pu;
        $finalTo->nilai_pu = $tryout->pu * $bobot_pu;

        $finalTo->ppu_benar = $tryout->ppu;
        $finalTo->nilai_ppu = $tryout->ppu * $bobot_ppu;

        $finalTo->pm_benar = $tryout->pm;
        $finalTo->nilai_pm = $tryout->pm * $bobot_pm;

        $finalTo->pk_benar = $tryout->pk;
        $finalTo->nilai_pk = $tryout->pk * $bobot_pk;

        $finalTo->lbi_benar = $tryout->lbi;
        $finalTo->nilai_lbi = $tryout->lbi * $bobot_lbi;

        $finalTo->lbe_benar = $tryout->lbe;
        $finalTo->nilai_lbe = $tryout->lbe * $bobot_lbe;

        $finalTo->pbm_benar = $tryout->pbm;
        $finalTo->nilai_pbm = $tryout->pbm * $bobot_pbm;

        $finalTo->total_benar = $tryout->pu + $tryout->ppu + $tryout->pm + $tryout->pk + $tryout->lbi + $tryout->lbe + $tryout->pbm;
        $finalTo->total_nilai = ($finalTo->nilai_ppu + $finalTo->nilai_pu + $finalTo->nilai_pk+ $finalTo->nilai_lbi + $finalTo->nilai_lbe + $finalTo->nilai_pbm + $finalTo->nilai_pm) / 7;

        $finalTo->save(); 

        return redirect()->route("admin.siswa.tryout", $username)->with('success', 'Nilai Tryout berhasil ditambahkan');
    }   
       
    public function update(Request $request, $username, $nama_tryout)
    {
        $tryout = Nilaito::where('nama_tryout', $nama_tryout)
            ->where('username', $username)
            ->first();

        $tryout->tanggal = $request->tanggal;
        $tryout->ppu = $request->ppu;
        $tryout->pu = $request->pu;
        $tryout->pm = $request->pm;
        $tryout->pk = $request->pk;
        $tryout->lbi = $request->lbi;
        $tryout->lbe = $request->lbe;
        $tryout->pbm = $request->pbm;

        $tryout->save();

        return redirect()->route("admin.siswa.tryout", $username)->with('success', 'Nilai Tryout berhasil diubah');   
    }

    public function detail_tryout($username, $nama_tryout, $rata)
    {
        $siswa = Siswa::where('username', $username)->first();
        $tryout = Nilaito::where('nama_tryout', $nama_tryout)
            ->where('username', $username)
            ->first();

        if($siswa){

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
    
        return view("app.admin.siswa.tryoutdetail", compact('siswa', 'tryout', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'rata'));
        }

        else{
            return redirect()->route("admin.siswa.tryout", $username)->with('error', 'Tidak ada data siswa');
        }
    }

    public function edit($username, $nama_tryout)
    {
        $siswa = Siswa::where('username', $username)->first();
        $tryout = Nilaito::where('nama_tryout', $nama_tryout)
            ->where('username', $username)
            ->first();
    
        return view("app.admin.siswa.edit", compact('siswa', 'tryout'));
    }


    public function destroy($username, $nama_tryout)
    {
        $tryout = Nilaito::where('nama_tryout', $nama_tryout)
            ->where('username', $username)
            ->first();

        $tryout->delete();
        
        return redirect()->route("admin.siswa.tryout", $username)->with('success', 'Nilai Tryout berhasil dihapus');    
    }
}
