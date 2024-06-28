<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TSiswa;
use App\Models\Nilai;
use App\Models\Nilaito;
use App\Models\Sekolah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total_pendaftar = TSiswa::where('active', now()->year)
            ->count();
        $rata = Nilai::join('t_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_nilai_to.username')
            ->whereYear('t_nilai_to.tanggal', now()->year)
            ->avg('mv_rekapitulasi_nilai_to.total_nilai');
        $max = Nilai::join('t_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_nilai_to.username')
            ->whereYear('t_nilai_to.tanggal', now()->year)
            ->max('mv_rekapitulasi_nilai_to.total_nilai');

        $sekolah = Sekolah::count();

        $currentYear = Carbon::now()->year;

        $data = Nilai::join('t_nilai_to', 'mv_rekapitulasi_nilai_to.username', '=', 't_nilai_to.username')
            ->selectRaw('YEAR(t_nilai_to.tanggal) as year, AVG(mv_rekapitulasi_nilai_to.total_nilai) as average')
            ->whereYear('t_nilai_to.tanggal', '>=', $currentYear) 
            ->whereYear('t_nilai_to.tanggal', '<=', $currentYear + 1) 
            ->groupBy('year')
            ->get();

        $years = [];
        $averages = [];

        foreach ($data as $item) {
            $years[] = $item->year;
            $averages[] = $item->average;
        }

        return view('app.admin.dashboard.main', compact('total_pendaftar', 'rata', 'max', 'sekolah', 'years', 'averages'));
    }
}
