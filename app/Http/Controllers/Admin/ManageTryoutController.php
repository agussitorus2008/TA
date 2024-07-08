<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TTo;
use App\Models\TNilaito;
use Illuminate\Support\Facades\Date;


class ManageTryoutController extends Controller
{
    public function index(){
        $tahun = TTo::select('active')->distinct()->orderBy('active', 'asc')->get();
        $currentMonth = Date::now()->month;
        $currentYear = Date::now()->year;

        if ($currentMonth >= 8) {
            $tahunSekarang = $currentYear + 1;
        } else {
            $tahunSekarang = $currentYear;
        }


        $tryoutlist = TTo::where('active', $tahunSekarang)->paginate(10);

        $statusList = [];

        foreach ($tryoutlist as $to) {
            $nilai_to_count = TNilaiTo::where('id_to', $to->id_to)->count();

            if ($nilai_to_count > 0) {
                $statusList[$to->id_to] = true;
            } else {
                $statusList[$to->id_to] = false;
            }
        }

        
        return view("app.admin.managetryout.main", compact('tryoutlist', 'tahun', 'tahunSekarang', 'statusList'));
    }

    public function add(){
        $currentMonth = Date::now()->month;
        $currentYear = Date::now()->year;

        if ($currentMonth >= 8) {
            $tahunSekarang = $currentYear + 1;
        } else {
            $tahunSekarang = $currentYear;
        }
        
        $lastTryout = TTo::where('active', $tahunSekarang)->latest()->first();
        if ($lastTryout) {
            $pattern = '/(\d+)$/';
            if (preg_match($pattern, $lastTryout->nama_to, $matches)) {
                $lastNumber = (int)$matches[1];
                $newNumber = $lastNumber + 1;
                $newTryoutName = preg_replace($pattern, $newNumber, $lastTryout->nama_to);
            } else {
                $newTryoutName = $lastTryout->nama_to . '1';
            }
        } else {
            $newTryoutName = 'Tryout-1';
        }
        return view("app.admin.managetryout.add", compact('newTryoutName', 'tahunSekarang'));
    }

    public function store(Request $request){
        $request->validate([
            't_ppu' => 'integer',
            't_pu' => 'integer',
            't_pm' => 'integer',
            't_pk' => 'integer',
            't_lbi' => 'integer',
            't_lbe' => 'integer',
            't_pbm' => 'integer',
            'total' => 'integer',
            'tanggal' => 'date',
            'tahun' => 'integer',
        ], [
            't_ppu.integer' => 'Masukkan angka yang valid untuk PPU',
            't_pu.integer' => 'Masukkan angka yang valid untuk PU',
            't_pm.integer' => 'Masukkan angka yang valid untuk PM',
            't_pk.integer' => 'Masukkan angka yang valid untuk PK',
            't_lbi.integer' => 'Masukkan angka yang valid untuk LBI',
            't_lbe.integer' => 'Masukkan angka yang valid untuk LBE',
            't_pbm.integer' => 'Masukkan angka yang valid untuk PBM',
            'total.integer' => 'Masukkan angka yang valid untuk Total',
            'tanggal.date' => 'Masukkan tanggal yang valid',
            'tahun.integer' => 'Masukkan tahun yang valid',
        ]);

        // Validasi khusus untuk tahun berdasarkan bulan
        $currentMonth = Date::now()->month;
        $currentYear = Date::now()->year;

        $expectedYear = ($currentMonth >= 8) ? $currentYear + 1 : $currentYear;

        if ($request->input('tahun') != $expectedYear) {
            return back()->withErrors(['tahun' => 'Tahun yang dimasukkan tidak valid berdasarkan bulan saat ini.'])->withInput();
        }

        $tryout = new TTo();
        $tryout->nama_to = $request->input('nama_tryout');
        $tryout->active = $request->input('tahun');
        $tryout->tanggal = $request->input('tanggal');
        $tryout->t_ppu = $request->input('t_ppu');
        $tryout->t_pu = $request->input('t_pu');
        $tryout->t_pm = $request->input('t_pm');
        $tryout->t_pk = $request->input('t_pk');
        $tryout->t_lbi = $request->input('t_lbi');
        $tryout->t_lbe = $request->input('t_lbe');
        $tryout->t_pbm = $request->input('t_pbm');
        $tryout->total = $request->input('total');
        $tryout->save();

        return redirect()->route("admin.managetryout.main")->with('success', 'Tryout berhasil ditambahkan');

    }

    public function update($id, Request $request) {
        $tryout = TTo::findOrFail($id);
    
        $request->validate([
            't_ppu' => 'integer',
            't_pu' => 'integer',
            't_pm' => 'integer',
            't_pk' => 'integer',
            't_lbi' => 'integer',
            't_lbe' => 'integer',
            't_pbm' => 'integer',
            'total' => 'integer',
            'tanggal' => 'date',
            'tahun' => 'integer',
        ], [
            't_ppu.integer' => 'Masukkan angka yang valid untuk PPU',
            't_pu.integer' => 'Masukkan angka yang valid untuk PU',
            't_pm.integer' => 'Masukkan angka yang valid untuk PM',
            't_pk.integer' => 'Masukkan angka yang valid untuk PK',
            't_lbi.integer' => 'Masukkan angka yang valid untuk LBI',
            't_lbe.integer' => 'Masukkan angka yang valid untuk LBE',
            't_pbm.integer' => 'Masukkan angka yang valid untuk PBM',
            'total.integer' => 'Masukkan angka yang valid untuk Total',
            'tanggal.date' => 'Masukkan tanggal yang valid',
            'tahun.integer' => 'Masukkan tahun yang valid',
        ]);
    
        // Update the attributes
        $tryout->nama_to = $request->input('nama_tryout');
        $tryout->active = $request->input('tahun');
        $tryout->tanggal = $request->input('tanggal');
        $tryout->t_ppu = $request->input('t_ppu');
        $tryout->t_pu = $request->input('t_pu');
        $tryout->t_pm = $request->input('t_pm');
        $tryout->t_pk = $request->input('t_pk');
        $tryout->t_lbi = $request->input('t_lbi');
        $tryout->t_lbe = $request->input('t_lbe');
        $tryout->t_pbm = $request->input('t_pbm');
        $tryout->total = $request->input('total');
    
        $tryout->save(); // Save the changes
    
        return redirect()->route("admin.managetryout.main")->with('success', 'Tryout berhasil diubah');
    }
    
    public function detail($id) {
        $tryout = TTo::where('id_to', $id)->first();
        return view("app.admin.managetryout.detail", compact('tryout'));
    }
    

    public function edit($id){
        $tryout = TTo::where('id_to', $id)->first();
        return view("app.admin.managetryout.edit", compact('tryout'));
    }

    public function delete($id) {
        $tryout = TTo::where('id_to',$id);
    
        if (!$tryout) {
            return redirect()->back()->with('error', 'Tryout not found.');
        }
    
        $tryout->delete();
    
        return redirect()->route("admin.managetryout.main")->with('success', 'Tryout berhasil dihapus.');
    }
    

}
