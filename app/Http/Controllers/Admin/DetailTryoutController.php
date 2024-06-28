<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\TSiswa;
use App\Models\Sekolah;
use App\Models\Tryout;
use App\Models\TNilaito;
use App\Models\Nilai;
use App\Models\Kelulusan;
use App\Models\ViewNilaiFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DetailTryoutController extends Controller
{

    public function index()
    {
        $siswaList = TSiswa::whereNotIn('active', [2023, 23, 0])->paginate(20);

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
            ->pluck('active');
        return view('app.admin.tryout.main', compact('siswaList', 'tahun', 'statusList'));
    }

    public function detail($username)
    {
        if (empty($username)) {
            return redirect()->route("admin.siswa.main")->with('error', 'Tidak ada data siswa');
        }
        $siswa = TSiswa::where('username', $username)->first();
        if (empty($siswa)) {
            $siswa = "Belum ada data siswa";
        }

        $tryouts = TNilaito::where('username', $username)->get();

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

        $nilaiRata = ViewNilaiFinal::where('username', $username)->first();

        return view('app.admin.tryout.tryout', compact('tryouts', 'siswa', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'nilaiRata', 'bobot_total'));
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
    
        return view("app.admin.tryout.tryoutdetail", compact('siswa', 'tryout', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'rata', 'bobot_total'));
        }

        else{
            return redirect()->route("admin.siswa.tryout", $username)->with('error', 'Tidak ada data siswa');
        }
    }
}
