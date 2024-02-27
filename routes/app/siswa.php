<?php
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\SiswaController;
use App\Http\Controllers\Web\IndexController;
use App\Http\Controllers\Web\TryoutSiswaController;
use App\Http\Controllers\Web\HasilTryoutSiswaController;
use App\Http\Controllers\Web\SimulasiController;

Route::prefix('/siswa')->group(function () {
    Route::get('/main', [SiswaController::class, 'index'])->name('siswa.main');
    Route::get('/view', [SiswaController::class, 'index'])->name('siswa.view');

    Route::prefix('/tryoutSaya')->group(function () {
        Route::get('/main', [TryoutSiswaController::class, 'index'])->name('siswa.tryoutSaya.main');
        Route::get('/detail/{id}', [TryoutSiswaController::class, 'index'])->name('siswa.tryoutSaya.detail');
    });

    Route::prefix('/hasilTryoutSiswa')->group(function () {
        Route::get('/main', [HasilTryoutSiswaController::class, 'index'])->name('siswa.hasilTryout.main');
    });

    Route::prefix('/profile')->group(function () {
        Route::get('/main', [SiswaController::class, 'profile'])->name('siswa.profile.main');
    });

    Route::prefix('/simulasi')->group(function () {
        Route::get('/main', [SimulasiController::class, 'index'])->name('siswa.simulasi.main');
        Route::post('/test', [SimulasiController::class, 'test'])->name('siswa.simulasi.test');
    });
});