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

class DetailTryoutController extends Controller
{

    public function index()
    {
        $siswaList = Siswa::paginate(10);

        $statusList = []; // Inisialisasi array untuk menyimpan status

        foreach ($siswaList as $siswa) {
            $nilaitoCount = Nilaito::where('username', $siswa->username)->count();

            if ($nilaitoCount > 0) {
                $statusList[$siswa->username] = "Sudah Ada Nilai Tryout";
            } else {
                $statusList[$siswa->username] = "Belum Ada Nilai Tryout";
            }
        }

        $tahun = Siswa::select('active')
            ->whereNotNull('active') // Exclude null values
            ->whereNotIn('active', [0, 23]) // Exclude 0 and 23
            ->distinct()
            ->pluck('active'); // Get distinct values
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

        return view('app.admin.tryout.tryout', compact('tryouts', 'siswa', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm'));
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
    
        return view("app.admin.tryout.tryoutdetail", compact('siswa', 'tryout', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'rata'));
        }

        else{
            return redirect()->route("admin.siswa.tryout", $username)->with('error', 'Tidak ada data siswa');
        }
    }
}
