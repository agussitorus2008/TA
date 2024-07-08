<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Tryout;
use App\Models\TNilaito;
use App\Models\Nilai;
use App\Models\TTo;
use App\Models\TSiswa;
use App\Models\ViewNilaiFinalTerbaru;
use App\Models\ViewNilaiFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;

class SiswaController extends Controller
{
    public function index()
    {
        $currentMonth = Date::now()->month;
        $currentYear = Date::now()->year;

        if ($currentMonth >= 8) {
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
            
        return view('app.admin.siswa.main', compact('siswaList', 'tahun', 'statusList', 'tahunSekarang'));
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

        return view('app.admin.siswa.tryout', compact('tryouts', 'siswa', 'nilaiRata', 'nilai'));
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
