<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Tryout;
use App\Models\Nilaito;
use App\Models\Nilai;
use App\Models\Kelulusan;
use App\Models\ViewNilaiFinalTerbaru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DetailTryoutController extends Controller
{

    public function index()
    {
        $siswaList = Siswa::paginate(10);

        $statusList = [];

        foreach ($siswaList as $siswa) {
            $nilaitoCount = Nilaito::where('username', $siswa->username)->count();

            if ($nilaitoCount > 0) {
                $statusList[$siswa->username] = "Sudah Ada Nilai Tryout";
            } else {
                $statusList[$siswa->username] = "Belum Ada Nilai Tryout";
            }
        }

        $tahun = Siswa::select('active')
            ->whereNotNull('active')
            ->whereNotIn('active', [0, 23])
            ->distinct()
            ->pluck('active');
        return view('app.admin.tryout.main', compact('siswaList', 'tahun', 'statusList'));
    }

    public function detail($username)
    {
        if (empty($username)) {
            return redirect()->route("admin.siswa.main")->with('error', 'Tidak ada data siswa');
        }
        $siswa = Siswa::where('username', $username)->first();
        if (empty($siswa)) {
            $siswa = "Belum ada data siswa";
        }

        $tryouts = Nilaito::where('username', $username)->get();

        if (empty($tryouts)) {
            $tryouts = "Belum ada data Nilai Tryout";
        }

        $bobot_ppu = 30;
        $bobot_pu = 20;
        $bobot_pm = 20;
        $bobot_pk = 15;
        $bobot_lbi = 30;
        $bobot_lbe = 20;
        $bobot_pbm = 20;
        $bobot_total = 155;

        $nilaiRata = ViewNilaiFinalTerbaru::where('username', $username)->first();

        return view('app.admin.tryout.tryout', compact('tryouts', 'siswa', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'nilaiRata', 'bobot_total'));
    }

    public function detail_tryout($username, $nama_tryout, $rata)
    {
        $siswa = Siswa::where('username', $username)->first();
        $tryout = Nilaito::where('nama_tryout', $nama_tryout)
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
    
        return view("app.admin.tryout.tryoutdetail", compact('siswa', 'tryout', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'rata', 'bobot_total'));
        }

        else{
            return redirect()->route("admin.siswa.tryout", $username)->with('error', 'Tidak ada data siswa');
        }
    }
}
