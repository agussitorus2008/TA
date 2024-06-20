<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;

class ProfileController extends Controller
{
    public function siswa()
    {
        $user = Auth::user();
        $siswa = Siswa::where('username', $user->email)->first();
        return view('app.siswa.data_siswa.main', compact('user', 'siswa'));
    }
}
