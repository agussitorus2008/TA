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
use App\Models\ViewNilaiFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;

class DetailTryoutController extends Controller
{

    public function index()
    {
        $currentMonth = Date::now()->month;
        $currentYear = Date::now()->year;

        if ($currentMonth >= 6) {
            $tahunSekarang = $currentYear + 1;
        } else {
            $tahunSekarang = $currentYear;
        }

        $siswaList = TSiswa::where('active', $tahunSekarang)->paginate(10);

        $statusList = [];

        foreach ($siswaList as $siswa) {
            $nilaitoCount = TNilaito::where('username', $siswa->username)->count();

            if ($nilaitoCount > 0) {
                $statusList[$siswa->username] = "Sudah Ada Nilai Tryout";
            } else {
                $statusList[$siswa->username] = "Belum Ada Nilai Tryout";
            }
        }

        $tahun = TSiswa::select('active')
            ->whereNotNull('active')
            ->whereNotIn('active', [0, 23])
            ->distinct()
            ->orderBy('active', 'asc')
            ->pluck('active');

        return view('app.admin.tryout.main', compact('siswaList', 'tahun', 'statusList', 'tahunSekarang'));
    }

    public function detail($username)
    {
        $siswa = TSiswa::where('username', $username)->first();

        if (empty($siswa)) {
            $siswa = "Belum ada data siswa";
        }

        $tryouts = TNilaito::join('t_to', 't_to.id_to', '=', 't_nilai_to.id_to')
            ->where('t_nilai_to.username', $username)
            ->orderBy('t_to.id_to', 'asc')
            ->get();

        $nilai = Nilai::where('username', $username)
        ->orderBy('id_to', 'asc')
        ->get(); 


        $nilaiRata = ViewNilaiFinal::where('username', $username)->first();

        return view('app.admin.tryout.tryout', compact('tryouts', 'siswa', 'nilaiRata', 'nilai'));
    }

    // public function detail_tryout($username, $nama_tryout, $rata)
    // {
    //     $siswa = TSiswa::where('username', $username)->first();
    //     $tryout = TNilaito::where('id_to', $nama_tryout)
    //         ->where('username', $username)
    //         ->first();

    //     if($siswa){

    //     $bobot_ppu = 30;
    //     $bobot_pu = 20;
    //     $bobot_pm = 20;
    //     $bobot_pk = 15;
    //     $bobot_lbi = 30;
    //     $bobot_lbe = 20;
    //     $bobot_pbm = 20;
    //     $bobot_total = 155;
    
    //     return view("app.admin.tryout.tryoutdetail", compact('siswa', 'tryout', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'rata', 'bobot_total'));
    //     }

    //     else{
    //         return redirect()->route("admin.siswa.tryout", $username)->with('error', 'Tidak ada data siswa');
    //     }
    // }

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
            return redirect()->route("admin.siswa.detailtryout", $username)->with('error', 'Tidak ada data siswa');
        }
    }
}
