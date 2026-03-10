<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\BarangController;

Auth::routes();

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

    Route::post('barang/cetak-label', [BarangController::class, 'cetakLabel'])->name('barang.cetakLabel');
    Route::resource('barang', BarangController::class);

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/katalog-pdf', [LaporanController::class, 'generateKatalog'])->name('laporan.katalog');
    Route::get('laporan/stok-pdf', [LaporanController::class, 'generateStok'])->name('laporan.stok');

    Route::post('barang/cetak-label', [BarangController::class, 'cetakLabel'])->name('barang.cetakLabel');

    Route::get('/simulasi-produk', function () {
        return view('simulasi.simulasi-index');
    })->name('simulasi.index');

    Route::get('/simulasi-datatables', function () {
        return view('simulasi.simulasi-datatables');
    })->name('simulasi.datatables');

    Route::get('/simulasi-wilayah', function () {
        return view('simulasi.simulasi-wilayah');
    })->name('simulasi.wilayah');
});