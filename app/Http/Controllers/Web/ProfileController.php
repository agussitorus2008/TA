<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TSiswa;

class ProfileController extends Controller
{
    public function siswa()
    {
        $user = Auth::user();
        $siswa = TSiswa::where('username', $user->email)->first();
        return view('app.siswa.data_siswa.main', compact('user', 'siswa'));
    }
}
