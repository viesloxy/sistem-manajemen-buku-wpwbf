<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LaporanController;

Auth::routes();

// Google Login & OTP harus di luar middleware auth agar bisa diakses saat tamu (guest)
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('auth/otp', [GoogleController::class, 'showOtpForm'])->name('otp.view');
Route::post('auth/otp', [GoogleController::class, 'verifyOtp'])->name('otp.verify');


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/home', function () {
        return redirect('/');
    });

    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/katalog-pdf', [LaporanController::class, 'generateKatalog'])->name('laporan.katalog');
    Route::get('laporan/stok-pdf', [LaporanController::class, 'generateStok'])->name('laporan.stok');
});