<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TSiswa;
use App\Models\Nilai;
use App\Models\TNilaiTo;
use App\Models\Sekolah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentMonth = Date::now()->month;
        $currentYear = Date::now()->year;

        if ($currentMonth >= 8) {
            $tahunSekarang = $currentYear + 1;
        } else {
            $tahunSekarang = $currentYear;
        }

        $total_pendaftar = TSiswa::where('active', $tahunSekarang)
            ->count();

        $rata = TSiswa::join('view_rekapitulasi_nilai_to', 't_siswa.username', '=', 'view_rekapitulasi_nilai_to.username')
            ->where('t_siswa.active', $tahunSekarang)
            ->avg('view_rekapitulasi_nilai_to.average_to');
            
        $max = TSiswa::join('view_rekapitulasi_nilai_to', 't_siswa.username', '=', 'view_rekapitulasi_nilai_to.username')
            ->where('t_siswa.active', $tahunSekarang)
            ->max('view_rekapitulasi_nilai_to.average_to');

        $sekolah = TSiswa::where('active', $tahunSekarang)
        ->distinct('asal_sekolah')
        ->count();

        $data = TSiswa::join('view_rekapitulasi_nilai_to', 't_siswa.username', '=', 'view_rekapitulasi_nilai_to.username')
            ->whereNotIn('t_siswa.active', [0, 23])
            ->selectRaw('t_siswa.active as year, AVG(view_rekapitulasi_nilai_to.average_to) as average')
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
