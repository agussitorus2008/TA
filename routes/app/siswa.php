<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\IndexController;
use App\Http\Controllers\Web\SiswaController;
use App\Http\Controllers\Web\SimulasiController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TryoutSiswaController;
use App\Http\Controllers\Web\HasilTryoutSiswaController;
use App\Http\Controllers\Web\ProfileController;

Route::prefix('/siswa')->middleware(['auth', 'user'])->group(function () {
    Route::get('/main', [SiswaController::class, 'index'])->name('siswa.main');
    Route::get('/view', [SiswaController::class, 'index'])->name('siswa.view');

    Route::prefix('/tryoutSaya')->group(function () {
        Route::get('/main', [TryoutSiswaController::class, 'index'])->name('siswa.tryoutSaya.main');
        Route::get('/detail/{nama_tryout}/{rata}', [TryoutSiswaController::class, 'show'])->name('siswa.tryoutSaya.detail');
    });

    Route::prefix('/hasilTryoutSiswa')->group(function () {
        Route::any('/main', [HasilTryoutSiswaController::class, 'index'])->name('siswa.hasilTryout.main');
        Route::get('/pilihan1/{nama_prodi}', [HasilTryoutSiswaController::class, 'pilihan1'])->name('siswa.hasilTryout.pilihan1');
        Route::get('/pilihanTotal/{nama_prodi}', [HasilTryoutSiswaController::class, 'pilihanTotal'])->name('siswa.hasilTryout.pilihanTotal');
        Route::get('/rekomendasi', [HasilTryoutSiswaController::class, 'rekomendasi'])->name('siswa.hasilTryout.rekomendasi');
    });

    Route::prefix('/profile')->group(function () {
        Route::get('/main', [SiswaController::class, 'profile'])->name('siswa.profile.main');
        Route::get('/add/{email}', [SiswaController::class, 'view'])->name('siswa.profile.add');
        Route::post('/add/{email}', [SiswaController::class, 'add'])->name('siswa.profile.add');
        Route::get('/edit/{email}', [SiswaController::class, 'edit'])->name('siswa.profile.edit');
        Route::post('/update/{email}', [SiswaController::class, 'update'])->name('siswa.profile.update');
    });

    Route::prefix('/data_siswa')->group(function () {
        Route::get('/main', [ProfileController::class, 'siswa'])->name('siswa.data_siswa.main');
    });

    Route::prefix('/simulasi')->group(function () {
        Route::get('/main', [SimulasiController::class, 'index'])->name('siswa.simulasi.main');
        Route::post('/test', [SimulasiController::class, 'prediksi'])->name('siswa.simulasi.test');

        Route::get('/ptn', [SimulasiController::class, 'index_ptn'])->name('siswa.simulasi.ptn');
        Route::post('/test_ptn', [SimulasiController::class, 'prediksi_ptn'])->name('siswa.simulasi.test_ptn');

        Route::get('/prodi', [SimulasiController::class, 'index_prodi'])->name('siswa.simulasi.prodi');
        Route::post('/test_prodi', [SimulasiController::class, 'prediksi_prodi'])->name('siswa.simulasi.test_prodi');

        //get rekomendasi
        Route::get('/get-rekomendasi', [SimulasiController::class, 'rekomendasi'])->name('siswa.rekomendasi');
    });
});
