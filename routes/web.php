<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\KasirController;

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

    Route::resource('barang', BarangController::class);
    Route::post('barang/cetak-label', [BarangController::class, 'cetakLabel'])->name('barang.cetakLabel');

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/katalog-pdf', [LaporanController::class, 'generateKatalog'])->name('laporan.katalog');
    Route::get('laporan/stok-pdf', [LaporanController::class, 'generateStok'])->name('laporan.stok');

    // --- Menu Simulasi ---
    Route::get('/simulasi-produk', function () {
        return view('simulasi.simulasi-index');
    })->name('simulasi.index');

    Route::get('/simulasi-datatables', function () {
        return view('simulasi.simulasi-datatables');
    })->name('simulasi.datatables');

    Route::get('/simulasi-wilayah', function () {
        return view('simulasi.simulasi-wilayah');
    })->name('simulasi.wilayah');

    // --- Menu Wilayah Administrasi ---
    Route::get('/wilayah-indonesia', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::prefix('api-wilayah')->group(function () {
        Route::get('/provinsi', [WilayahController::class, 'getProvinsi'])->name('api.provinsi');
        Route::get('/kabupaten/{id}', [WilayahController::class, 'getKabupaten'])->name('api.kabupaten');
        Route::get('/kecamatan/{id}', [WilayahController::class, 'getKecamatan'])->name('api.kecamatan');
        Route::get('/kelurahan/{id}', [WilayahController::class, 'getKelurahan'])->name('api.kelurahan');
    });

    // --- Menu Kasir ---
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::prefix('api-kasir')->group(function () {
        Route::get('/barang/{kode}', [KasirController::class, 'cekBarang'])->name('api.kasir.cek');
        Route::post('/simpan', [KasirController::class, 'store'])->name('api.kasir.simpan');
    });
});