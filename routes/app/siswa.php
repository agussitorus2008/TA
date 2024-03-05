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
        Route::any('/main', [HasilTryoutSiswaController::class, 'index'])->name('siswa.hasilTryout.main');
    });

    Route::prefix('/profile')->group(function () {
        Route::get('/main', [SiswaController::class, 'profile'])->name('siswa.profile.main');
        Route::get('/add/{email}', [SiswaController::class, 'view'])->name('siswa.profile.add');
        Route::post('/add/{email}', [SiswaController::class, 'add'])->name('siswa.profile.add');
        Route::get('/edit/{email}', [SiswaController::class, 'edit'])->name('siswa.profile.edit');
        Route::post('/update/{email}', [SiswaController::class, 'update'])->name('siswa.profile.update');
    });

    Route::prefix('/simulasi')->group(function () {
        Route::get('/main', [SimulasiController::class, 'index'])->name('siswa.simulasi.main');
        Route::post('/test', [SimulasiController::class, 'prediksi'])->name('siswa.simulasi.test');
    });
});