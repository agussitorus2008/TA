<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TryoutController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DetailTryoutController;
use App\Http\Controllers\Admin\ManageTryoutController;

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/main', [DashboardController::class, 'index'])->name('admin.main'); // hanya bisa dimasuki oleh admin

    Route::prefix('/siswa')->group(function () {
        Route::get('/main', [SiswaController::class, 'index'])->name('admin.siswa.main');

        Route::prefix('/tryout')->group(function () {
            Route::get('/{username}', [SiswaController::class, 'detail'])->name('admin.siswa.tryout');
            Route::get('add/{username}', [TryoutController::class, 'add'])->name('admin.siswa.tryout.add');
            Route::post('add/{username}', [TryoutController::class, 'store'])->name('admin.siswa.tryout.add');
            Route::get('/{username}/{id_to}/{rata}', [TryoutController::class, 'detail_tryout'])->name('admin.siswa.tryout.detail');
            Route::get('/{username}/{id_to}', [TryoutController::class, 'edit'])->name('admin.siswa.tryout.edit');
            Route::post('/{username}/{id_to}', [TryoutController::class, 'update'])->name('admin.siswa.tryout.update');
            Route::delete('/{username}/{id_to}/delete', [TryoutController::class, 'destroy'])->name('admin.siswa.tryout.delete');
        });

        Route::prefix('/detailtryout')->group(function () {
            Route::get('/main', [DetailTryoutController::class, 'index'])->name('admin.detailtryout.main');
            Route::get('/{username}', [DetailTryoutController::class, 'detail'])->name('admin.siswa.detailtryout');
            Route::get('/{username}/{id_to}/{rata}', [DetailTryoutController::class, 'detail_tryout'])->name('admin.siswa.detailtryout.detail');
        });

        Route::prefix('/managetryout')->group(function () {
            Route::get('/main', [ManageTryoutController::class, 'index'])->name('admin.managetryout.main');
            Route::get('/add', [ManageTryoutController::class, 'add'])->name('admin.managetryout.add');
            Route::get('/detail/{id}', [ManageTryoutController::class, 'detail'])->name('admin.managetryout.detail');
            Route::post('/add', [ManageTryoutController::class, 'store'])->name('admin.managetryout.add');
            Route::get('/edit/{id}', [ManageTryoutController::class, 'edit'])->name('admin.managetryout.edit');
            Route::delete('/delete/{id}', [ManageTryoutController::class, 'delete'])->name('admin.managetryout.delete');
            Route::post('/update/{id}', [ManageTryoutController::class, 'update'])->name('admin.managetryout.update');
        });
    });

});
