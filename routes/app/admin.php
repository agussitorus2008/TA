<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->group(function () {
    Route::get('/main', [DashboardController::class, 'index'])->name('admin.main');



    Route::prefix('siswa')->group(function () {
        Route::get('/main', [SiswaController::class, 'index'])->name('admin.siswa.main');
        Route::get('/tryoutdetail/{id}', [SiswaController::class, 'tryoutdetail'])->name('admin.siswa.tryoutdetail');

        Route::get('/tryout', [SiswaController::class, 'tryout'])->name('admin.siswa.tryout');


        // Rute untuk mengarahkan pengguna ke halaman tryout berdasarkan ID tryout
        Route::get('/tryout/{id}', [SiswaController::class, 'showTryout'])->name('admin.siswa.tryout.show');
    });
});
