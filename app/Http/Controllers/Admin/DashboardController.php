<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Nilaito;
use App\Models\Sekolah;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        return view('app.admin.dashboard.main', compact('total_pendaftar', 'rata', 'max', 'sekolah'));
    }
}
