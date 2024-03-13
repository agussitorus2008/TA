<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Tryout;
use App\Models\Nilaito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siswaList = Siswa::paginate(10);
        return view('app.admin.siswa.main', ['siswaList' => $siswaList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function tryout()
    {
        $siswa = Siswa::first(); // Mengambil data siswa, Anda mungkin perlu menyesuaikan query ini sesuai dengan kebutuhan Anda
        $tryouts = Tryout::all(); // Mengambil data tryout, Anda mungkin perlu menyesuaikan query ini sesuai dengan kebutuhan Anda

        return view('app.admin.siswa.tryout', compact('siswa', 'tryouts'));
    }

    public function tryoutdetail($id)
    {
        // Ambil data siswa berdasarkan ID
        $siswa = Siswa::findOrFail($id);

        // Ambil data tryout yang diikuti oleh siswa
        $tryouts = Tryout::where('siswa_id', $id)->get();

        // Load view tryoutdetail.blade.php dan passing data siswa dan tryouts
        return view('app.admin.siswa.tryoutdetail', compact('siswa', 'tryouts'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $siswa = Siswa::where('username', $username)->first();

        // if ($siswa) {
        //     // Jika siswa ditemukan, ambil ID siswa
        //     $siswaId = $siswa->id;

        //     // Kemudian arahkan pengguna ke halaman tryout berdasarkan ID siswa
        //     return redirect()->route('admin.siswa.tryout.show', $siswaId);
        // } else {
        //     // Jika siswa tidak ditemukan, Anda dapat menangani kasus ini sesuai kebutuhan
        //     return abort(404); // Contoh: Mengembalikan response 404 Not Found
        // }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        return view('app.admin.profile.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
