<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\TryoutSiswaController;
use App\Http\Controllers\HasilTryoutSiswaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/auth', [AuthController::class, 'index'])->name('auth');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Assuming you want to define routes related to 'siswa'
Route::prefix('/siswa')->group(function () {
    Route::get('/main', [SiswaController::class, 'index'])->name('siswa.main');
    Route::get('/view', [SiswaController::class, 'index'])->name('siswa.view');

    Route::prefix('/tryoutSaya')->group(function () {
        Route::get('/main', [TryoutSiswaController::class, 'index'])->name('siswa.tryoutSaya.main');
        Route::get('/detail', [TryoutSiswaController::class, 'index'])->name('siswa.tryoutSaya.main');
    });

    Route::prefix('/hasilTryoutSiswa')->group(function () {
        Route::get('/main', [HasilTryoutSiswaController::class, 'index'])->name('siswa.hasilTryout.main');
    });

    Route::prefix('/profile')->group(function () {
        Route::get('/main', [SiswaController::class, 'profile'])->name('siswa.profile.main');
    });
});

// Assuming you want to define routes related to 'register'
Route::get('/index', [IndexController::class, 'index'])->name('index');
