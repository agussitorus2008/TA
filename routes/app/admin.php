<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TryoutController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DetailTryoutController;

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/main', [DashboardController::class, 'index'])->name('admin.main'); // hanya bisa dimasuki oleh admin

    Route::prefix('/siswa')->group(function () {
        Route::get('/main', [SiswaController::class, 'index'])->name('admin.siswa.main');

        Route::prefix('/tryout')->group(function () {
            Route::get('/{username}', [SiswaController::class, 'detail'])->name('admin.siswa.tryout');
            Route::get('add/{username}', [TryoutController::class, 'add'])->name('admin.siswa.tryout.add');
            Route::post('add/{username}', [TryoutController::class, 'store'])->name('admin.siswa.tryout.add');
            Route::get('/{username}/{nama_tryout}/{rata}', [TryoutController::class, 'detail_tryout'])->name('admin.siswa.tryout.detail');
            Route::get('/{username}/{nama_tryout}', [TryoutController::class, 'edit'])->name('admin.siswa.tryout.edit');
            Route::post('/{username}/{nama_tryout}', [TryoutController::class, 'update'])->name('admin.siswa.tryout.update');
            Route::delete('/{username}/{nama_tryout}/delete', [TryoutController::class, 'destroy'])->name('admin.siswa.tryout.delete');
        });

        Route::prefix('/detailtryout')->group(function () {
            Route::get('/main', [DetailTryoutController::class, 'index'])->name('admin.detailtryout.main');
            Route::get('/{username}', [DetailTryoutController::class, 'detail'])->name('admin.siswa.detailtryout');
            Route::get('/{username}/{nama_tryout}/{rata}', [DetailTryoutController::class, 'detail_tryout'])->name('admin.siswa.detailtryout.detail');
        });
    });

});
