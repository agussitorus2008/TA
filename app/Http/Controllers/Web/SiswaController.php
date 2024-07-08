<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TSiswa;
use App\Models\Nilai;
use App\Models\Prodi;
use App\Models\PTN;
use App\Models\ViewNilaiFInal;
use App\Models\Sekolah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;


class SiswaController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $siswa = TSiswa::with('sekolah_siswa')->where('username', $user->email)->first();
        
        $propinsi = "Tidak Diketahui";
        if ($siswa && $siswa->sekolah_siswa) {
            $propinsi = $siswa->sekolah_siswa->propinsi;
        }

        return view('app.siswa.profile.profile', compact('user', 'siswa', 'propinsi'));
    }


    public function index()
    {
        $currentMonth = Date::now()->month;
        $currentYear = Date::now()->year;

        if ($currentMonth >= 6) {
            $tahunSekarang = $currentYear + 1;
        } else {
            $tahunSekarang = $currentYear;
        }

        $total_pendaftar = TSiswa::where('active', $tahunSekarang)->count();
            
        $rata = TSiswa::join('view_rekapitulasi_nilai_to', 't_siswa.username', '=', 'view_rekapitulasi_nilai_to.username')
            ->where('t_siswa.active', $tahunSekarang)
            ->avg('view_rekapitulasi_nilai_to.average_to');
            
        $max = TSiswa::join('view_rekapitulasi_nilai_to', 't_siswa.username', '=', 'view_rekapitulasi_nilai_to.username')
            ->where('t_siswa.active', $tahunSekarang)
            ->max('view_rekapitulasi_nilai_to.average_to');
            
        // $sekolah = Sekolah::count();
        $sekolah = TSiswa::where('active', $tahunSekarang)
        ->distinct('asal_sekolah')
        ->count();


        return view('app.siswa.main', compact('total_pendaftar', 'rata', 'max', 'sekolah'));
    }

    public function view($email)
    {
        $user = Auth::user();
        $prodi = Prodi::where('active', 2024)->get();
        $sekolah = Sekolah::all();
        $ptn = PTN::get();
        return view('app.siswa.profile.add', compact('user', 'prodi', 'sekolah', 'ptn'));
    }

    public function edit($email)
    {
        $user = Auth::user();
        $prodi = Prodi::where('active', 2024)->get();
        $sekolah = Sekolah::all();
        
        $selectedProdi = TSiswa::where('username', $email)->first();

        $selectedProdi1 = $selectedProdi->pilihan1_utbk;
        $selectedProdi2 = $selectedProdi->pilihan2_utbk;

        $prodi1 = Prodi::where('id_prodi', $selectedProdi1)->first();
        $prodi2 = Prodi::where('id_prodi', $selectedProdi2)->first();

        $selectedPTN1 = DB::table('t_prodi')
            ->join('t_ptn', 't_ptn.id_ptn', '=', 't_prodi.id_ptn')
            ->where('t_prodi.id_prodi', '=', $selectedProdi1)
            ->select('t_ptn.id_ptn')
            ->first();
        
        $selectedPTN2 = DB::table('t_prodi')
            ->join('t_ptn', 't_ptn.id_ptn', '=', 't_prodi.id_ptn')
            ->where('t_prodi.id_prodi', '=', $selectedProdi2)
            ->select('t_ptn.id_ptn')
            ->first();

        $ptn = PTN::get();
        $provinsi = Sekolah::where('id', '=', $selectedProdi->asal_sekolah)->select('propinsi')->first();

        return view('app.siswa.profile.edit', compact('user', 'prodi', 'sekolah', 'selectedProdi1', 'ptn', 'selectedProdi2', 'selectedProdi', 'selectedPTN1', 'selectedPTN2', 'prodi1', 'prodi2', 'provinsi'));
    }

    public function add(Request $request, $email)
    {
        $request->validate([
            'asal_sekolah' => 'required',
            'kelompok_ujian' => 'required',
            'ptn_pilihan1' => 'required',
            'ptn_pilihan2' => 'required',
            'prodi_piihan1' => 'required',
            'prodi_piihan2' => 'required|different:prodi_piihan1',
        ], [
            'prodi_piihan2.different' => 'Pilihan 1 dan Pilihan 2 tidak boleh sama',
        ]);
        
        $currentMonth = Date::now()->month;
        $currentYear = Date::now()->year;
        $expectedYear = ($currentMonth >= 6) ? $currentYear + 1 : $currentYear;

        $siswa = new TSiswa();
        $siswa->username = $email;
        $siswa->first_name = $request->nama;
        $siswa->asal_sekolah = $request->asal_sekolah;
        $siswa->kelompok_ujian = $request->kelompok_ujian;
        $siswa->pilihan1_utbk = $request->prodi_piihan1;
        $siswa->pilihan2_utbk = $request->prodi_piihan2;
        $siswa->telp1 = auth()->user()->no_handphone;
        $siswa->active = $expectedYear;
    
        $siswa->save();
    
        return redirect()->route('siswa.profile.main')->withSuccess('Data Siswa Berhasil Ditambahkan');
    }

    public function update(Request $request, $email)
    {
        $request->validate([
            'asal_sekolah' => 'required',
            'kelompok_ujian' => 'required',
            'ptn_pilihan1' => 'required',
            'ptn_pilihan2' => 'required',
            'prodi_piihan1' => 'required',
            'prodi_piihan2' => 'required|different:prodi_piihan1',
        ], [
            'prodi_piihan2.different' => 'Pilihan 1 dan Pilihan 2 tidak boleh sama',
        ]);

        $siswa = TSiswa::where('username', $email)->first();

        if (!$siswa) {
            return redirect()->route('siswa.profile.main')->withError('Siswa not found');
        }

        $siswa->first_name = $request->nama;
        $siswa->asal_sekolah = $request->asal_sekolah;
        $siswa->kelompok_ujian = $request->kelompok_ujian;
        $siswa->pilihan1_utbk = $request->prodi_piihan1;
        $siswa->pilihan2_utbk = $request->prodi_piihan2;
        $siswa->telp1 = auth()->user()->no_handphone;

        $siswa->save();

        return redirect()->route('siswa.profile.main')->withSuccess('Data Siswa Berhasil Diubah');
    }
}
