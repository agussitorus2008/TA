<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Prodi;
use App\Models\Sekolah;



class SiswaController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $siswa = Siswa::where('username', $user->username)->first();
        return view('app.siswa.profile.profile', compact('user', 'siswa'));
    }

    public function index()
    {
        return view('app.siswa.main');
    }

    public function view($email)
    {
        $user = Auth::user();
        $prodi = Prodi::where('active', 2024)->get();
        $sekolah = Sekolah::all();
        return view('app.siswa.profile.add', compact('user', 'prodi', 'sekolah'));
    }

    public function add(Request $request, $email)
    {
        $siswa = new Siswa();
        $siswa->username = $email;
        $siswa->asal_sekolah = $request->asal_sekolah; 
        $siswa->kelompok_ujian = $request->kelompok_ujian;
        $siswa->pilihan1_utbk_aktual = $request->pilihan1_utbk_aktual;
        $siswa->pilihan2_utbk_aktual = $request->pilihan2_utbk_aktual;
        $siswa->save();
        return view('app.siswa.profile.profile')->withSuccess('Data Siswa Berhasil Ditambahkan');
    }
}
