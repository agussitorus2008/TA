<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Nilaito;
use App\Models\Prodi;
use App\Models\PTN;
use App\Models\Sekolah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class SiswaController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $siswa = Siswa::where('username', $user->email)->first();
        if($siswa){
            $propinsi = Sekolah::where('sekolah', $siswa->asal_sekolah)->value('propinsi');
        }
        if(empty($propinsi)){
            $propinsi = "Tidak Diketahui";
        }
        return view('app.siswa.profile.profile', compact('user', 'siswa', 'propinsi'));
    }


    public function index()
    {
        $total_pendaftar = Siswa::where('active', now()->year)
            ->count();
        $rata = Nilai::join('nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'nilai_to.username')
            ->whereYear('nilai_to.tanggal', now()->year)
            ->avg('mv_rekapitulasi_nilai_to.total_nilai');
        $max = Nilai::join('nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 'nilai_to.username')
            ->whereYear('nilai_to.tanggal', now()->year)
            ->max('mv_rekapitulasi_nilai_to.total_nilai');

        $sekolah = Sekolah::count();

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
        
        $selectedProdi = Siswa::where('username', $email)->first();

        $selectedProdi1 = $selectedProdi->pilihan1_utbk_aktual;
        $selectedProdi2 = $selectedProdi->pilihan2_utbk_aktual;

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
        return view('app.siswa.profile.edit', compact('user', 'prodi', 'sekolah', 'selectedProdi1', 'ptn', 'selectedProdi2', 'selectedProdi', 'selectedPTN1', 'selectedPTN2', 'prodi1', 'prodi2'));
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
        
        $siswa = new Siswa();
        $siswa->username = $email;
        $siswa->first_name = $request->nama;
        $siswa->asal_sekolah = $request->asal_sekolah;
        $siswa->kelompok_ujian = $request->kelompok_ujian;
        $siswa->pilihan1_utbk_aktual = $request->prodi_piihan1;
        $siswa->pilihan2_utbk_aktual = $request->prodi_piihan2;
        $siswa->telp1 = auth()->user()->no_handphone;
        $siswa->active = Carbon::now()->year;
    
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

        $siswa = Siswa::where('username', $email)->first();

        if (!$siswa) {
            return redirect()->route('siswa.profile.main')->withError('Siswa not found');
        }

        $siswa->first_name = $request->nama;
        $siswa->asal_sekolah = $request->asal_sekolah;
        $siswa->kelompok_ujian = $request->kelompok_ujian;
        $siswa->pilihan1_utbk_aktual = $request->prodi_piihan1;
        $siswa->pilihan2_utbk_aktual = $request->prodi_piihan2;
        $siswa->telp1 = auth()->user()->no_handphone;
        $siswa->active = Carbon::now()->year;

        $siswa->save();

        return redirect()->route('siswa.profile.main')->withSuccess('Data Siswa Berhasil Diubah');
    }
}
