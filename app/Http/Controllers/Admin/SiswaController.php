<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Tryout;
use App\Models\TNilaito;
use App\Models\Nilai;
use App\Models\TSiswa;
use App\Models\ViewNilaiFinalTerbaru;
use App\Models\ViewNilaiFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SiswaController extends Controller
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
            
        return view('app.admin.siswa.main', compact('siswaList', 'tahun', 'statusList'));
    }

    public function detail($username)
    {
        $siswa = TSiswa::where('username', $username)->first();

        if (empty($siswa)) {
            $siswa = "Belum ada data siswa";
        }

        $tryouts = TNilaito::where('username', $username)->get();

        if (empty($tryouts)) {
            $tryouts = "Belum ada data Nilai Tryout";
        }

        $nilaiRata = ViewNilaiFinal::where('username', $username)->first();

        $bobot_ppu = 30;
        $bobot_pu = 20;
        $bobot_pm = 20;
        $bobot_pk = 15;
        $bobot_lbi = 30;
        $bobot_lbe = 20;
        $bobot_pbm = 20;
        $bobot_total = 155;

        return view('app.admin.siswa.tryout', compact('tryouts', 'siswa', 'bobot_ppu', 'bobot_pu', 'bobot_pm', 'bobot_pk', 'bobot_lbi', 'bobot_lbe', 'bobot_pbm', 'nilaiRata', 'bobot_total'));
    }


    public function showindex($id)
    {
        $tryout = Tryout::findOrFail($id);
        $siswa = Siswa::where('username', $tryout->username)->first();

        return view('app.admin.siswa.tryout', compact('siswa', 'tryout'));
    }

    public function tryoutdetail($id)
    {
        $tryout = Tryout::findOrFail($id);

        $siswa = Siswa::where('username', $username)->first();

        return view('app.admin.siswa.tryoutdetail', compact('siswa', 'tryout'));
    }

    public function tryout()
    {
        $siswa = Siswa::first();
        $tryouts = Tryout::all();

        return view('app.admin.siswa.tryout', compact('siswa', 'tryouts'));
    }


    public function store(Request $request)
    {
        //
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('app.admin.profile.edit', ['user' => $user]);
    }

    
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
