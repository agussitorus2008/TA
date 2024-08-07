<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ApiController;

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


Route::prefix('/auth')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'dologin'])->name('auth.dologin');

    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'doregister'])->name('auth.doregister');

    Route::get('/logout', [AuthController::class, 'dologout'])->name('auth.dologout');

    Route::get('/forget-password', [AuthController::class, 'forget'])->name('auth.forget-password');
    Route::post('/forget-password', [AuthController::class, 'postForget'])->name('auth.post-forget-password');

    Route::get('/change-password', [AuthController::class, 'change'])->name('auth.change-password');
    Route::post('/change-password', [AuthController::class, 'postChange'])->name('auth.post-change-password');
});


Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/search_prodi', 'ProdiController@search')->name('search_prodi');

Route::get('/get-provinces/{asalSekolah}', [ApiController::class, 'getProvinces']);
Route::post('/add-sekolah/', [ApiController::class, 'addSekolah']);

Route::get('/get-prodi-from-ptn/{idPtn}', [ApiController::class, 'getProdiFromPTN']);

Route::get('/siswa/search', [ApiController::class, 'search']);
Route::get('/siswa/filter', [ApiController::class, 'filter']);

Route::get('/tryout/search', [ApiController::class, 'search_tryout']);
Route::get('/tryout/filter', [ApiController::class, 'filter_tryout']);
Route::get('/tryout/getTanggal', [ApiController::class, 'getTanggalTryout']);

require_once __DIR__ . '/app/admin.php';

require_once __DIR__ . '/app/siswa.php';