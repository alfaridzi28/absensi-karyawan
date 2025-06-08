<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\UserController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/absen/post', [AbsensiController::class, 'absenPost'])->name('absen.post');

    Route::get('/riwayat', [HistoryController::class, 'riwayat'])->name('absen.riwayat');
    Route::get('/riwayat/data', [HistoryController::class, 'riwayatData'])->name('absen.riwayat.data');

    Route::post('/users/store', [UserController::class, 'storeOrUpdate'])->name('users.store');
    Route::resource('users', UserController::class)->except(['store', 'update']);
});
