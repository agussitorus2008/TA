<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Nilaito;
use App\Models\Prodi;
use App\Models\Sekolah;
use Carbon\Carbon;

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

        $sekolah = Siswa::join('sekolah_sma', 'siswa.asal_sekolah', '=', 'sekolah_sma.sekolah')
            ->whereYear('siswa.active', now()->year)
            ->distinct('sekolah_sma.sekolah')
            ->count();

        return view('app.siswa.main', compact('total_pendaftar', 'rata', 'max', 'sekolah'));
    }

    public function view($email)
    {
        $user = Auth::user();
        $prodi = Prodi::where('active', 2024)->get();
        $sekolah = Sekolah::all();
        return view('app.siswa.profile.add', compact('user', 'prodi', 'sekolah'));
    }

    public function edit($email)
    {
        $user = Auth::user();
        $prodi = Prodi::where('active', 2024)->get();
        $sekolah = Sekolah::all();
        $selectedProdi = Siswa::where('username', $email)->first();
        return view('app.siswa.profile.edit', compact('user', 'prodi', 'sekolah', 'selectedProdi'));
    }

    public function add(Request $request, $email)
    {
        $request->validate([
            'asal_sekolah' => 'required',
            'kelompok_ujian' => 'required',
            'pilihan1_utbk_aktual' => 'required',
            'pilihan2_utbk_aktual' => 'required|different:pilihan1_utbk_aktual',
        ], [
            'pilihan2_utbk_aktual.different' => 'Pilihan 1 dan Pilihan 2 tidak boleh sama',
        ]);
        
        $siswa = new Siswa();
        $siswa->username = $email;
        $siswa->first_name = $request->nama;
        $siswa->asal_sekolah = $request->asal_sekolah;
        $siswa->kelompok_ujian = $request->kelompok_ujian;
        $siswa->pilihan1_utbk_aktual = $request->pilihan1_utbk_aktual;
        $siswa->pilihan2_utbk_aktual = $request->pilihan2_utbk_aktual;
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
            'pilihan1_utbk_aktual' => 'required',
            'pilihan2_utbk_aktual' => 'required|different:pilihan1_utbk_aktual',
        ], [
            'pilihan2_utbk_aktual.different' => 'Pilihan 1 dan Pilihan 2 tidak boleh sama',
        ]);

        // Retrieve the existing record based on the username (email)
        $siswa = Siswa::where('username', $email)->first();

        // Check if the record exists
        if (!$siswa) {
            return redirect()->route('siswa.profile.main')->withError('Siswa not found');
        }

        // Update the record's properties
        $siswa->first_name = $request->nama;
        $siswa->asal_sekolah = $request->asal_sekolah;
        $siswa->kelompok_ujian = $request->kelompok_ujian;
        $siswa->pilihan1_utbk_aktual = $request->pilihan1_utbk_aktual;
        $siswa->pilihan2_utbk_aktual = $request->pilihan2_utbk_aktual;
        $siswa->telp1 = auth()->user()->no_handphone;
        $siswa->active = Carbon::now()->year;

        // Save the updated record
        $siswa->save();

        return redirect()->route('siswa.profile.main')->withSuccess('Data Siswa Berhasil Diubah');
    }

    
}
