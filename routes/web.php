<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\IndexController;

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
    Route::get('/view', [SiswaController::class, 'view'])->name('siswa.view');
});

// Assuming you want to define routes related to 'register'
Route::get('/index', [IndexController::class, 'index'])->name('index');
