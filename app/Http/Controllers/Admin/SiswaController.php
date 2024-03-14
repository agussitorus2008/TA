<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Tryout;
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
        $siswaList = Siswa::join('sekolah_sma', 't_siswa.sekolah', '=', 'sekolah_sma.id')
            ->select('t_siswa.*', 'sekolah_sma.*')
            ->paginate(10);

        // $siswaList = Siswa::all();

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
    public function showindex($id)
    {
        // Cari data tryout berdasarkan ID
        $tryout = Tryout::findOrFail($id);

        // Cari siswa yang terkait dengan tryout tersebut berdasarkan kolom 'username'
        $siswa = Siswa::where('username', $tryout->username)->first();

        return view('app.admin.siswa.tryout', compact('siswa', 'tryout'));
    }

    public function tryoutdetail($id)
    {
        // Cari data tryout berdasarkan ID
        $tryout = Tryout::findOrFail($id);

        // Cari siswa yang terkait dengan tryout tersebut berdasarkan kolom 'username'
        $siswa = Siswa::where('username', $tryout->username)->first();

        return view('app.admin.siswa.tryoutdetail', compact('siswa', 'tryout'));
    }

    public function tryout()
    {
        $siswa = Siswa::first(); // Mengambil data siswa, Anda mungkin perlu menyesuaikan query ini sesuai dengan kebutuhan Anda
        $tryouts = Tryout::all(); // Mengambil data tryout, Anda mungkin perlu menyesuaikan query ini sesuai dengan kebutuhan Anda

        return view('app.admin.siswa.tryout', compact('siswa', 'tryouts'));
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
