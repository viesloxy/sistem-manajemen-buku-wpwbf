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
use App\Http\Controllers\VendorMenuController;
use App\Http\Controllers\VendorPesananController;
use App\Http\Controllers\CustomerController;

Auth::routes();

// --- ROUTE AUTENTIKASI ---
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('auth/otp', [GoogleController::class, 'showOtpForm'])->name('otp.view');
Route::post('auth/otp', [GoogleController::class, 'verifyOtp'])->name('otp.verify');

// =========================================================================
// AREA TANPA LOGIN (CUSTOMER BISA AKSES)
// =========================================================================

// Halaman Utama untuk Customer Pesan Makanan (Landing Page)
Route::get('/', [CustomerController::class, 'index'])->name('customer.pos');

// API Khusus Customer
Route::prefix('customer/api')->group(function () {
    Route::get('/menu-by-vendor/{id}', [CustomerController::class, 'getMenuByVendor']);
    Route::get('/menu-detail/{id}', [CustomerController::class, 'getMenuDetail']);
    Route::post('/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
});

// Webhook Midtrans (WAJIB di luar auth agar Midtrans bisa ngirim data masuk)
Route::post('/midtrans/callback', [CustomerController::class, 'midtransCallback'])->name('midtrans.callback');

// QR Code API (tanpa auth - untuk modal popup setelah pembayaran)
Route::get('/qrcode/{id}', [CustomerController::class, 'generateQRCode'])->name('customer.qrcode');

// Verifikasi Pesanan via QR Code
Route::get('/pesanan/{id}/verifikasi', [CustomerController::class, 'verifyPesanan'])->name('customer.verify');


// =========================================================================
// AREA WAJIB LOGIN (ADMIN & VENDOR SAJA)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    
    // Route dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Jika ada yang akses /home, lempar ke dashboard
    Route::get('/home', function () {
        return redirect('/dashboard');
    });

    // --- FITUR ADMIN ---
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
    Route::resource('barang', BarangController::class);
    Route::post('barang/cetak-label', [BarangController::class, 'cetakLabel'])->name('barang.cetakLabel');

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/katalog-pdf', [LaporanController::class, 'generateKatalog'])->name('laporan.katalog');
    Route::get('laporan/stok-pdf', [LaporanController::class, 'generateStok'])->name('laporan.stok');

    Route::get('/simulasi-produk', function () { return view('simulasi.simulasi-index'); })->name('simulasi.index');
    Route::get('/simulasi-datatables', function () { return view('simulasi.simulasi-datatables'); })->name('simulasi.datatables');
    Route::get('/simulasi-wilayah', function () { return view('simulasi.simulasi-wilayah'); })->name('simulasi.wilayah');

    Route::get('/wilayah-indonesia', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::prefix('api-wilayah')->group(function () {
        Route::get('/provinsi', [WilayahController::class, 'getProvinsi'])->name('api.provinsi');
        Route::get('/kabupaten/{id}', [WilayahController::class, 'getKabupaten'])->name('api.kabupaten');
        Route::get('/kecamatan/{id}', [WilayahController::class, 'getKecamatan'])->name('api.kecamatan');
        Route::get('/kelurahan/{id}', [WilayahController::class, 'getKelurahan'])->name('api.kelurahan');
    });

    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::prefix('api-kasir')->group(function () {
        Route::get('/barang/{kode}', [KasirController::class, 'cekBarang'])->name('api.kasir.cek');
        Route::post('/simpan', [KasirController::class, 'store'])->name('api.kasir.simpan');
    });

    // Kelola User / Vendor
    Route::get('/kelola-user', [App\Http\Controllers\AdminUserController::class, 'index'])->name('user.index');
    Route::patch('/kelola-user/{id}/role', [App\Http\Controllers\AdminUserController::class, 'updateRole'])->name('user.updateRole');

    // --- FITUR VENDOR ---
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::resource('menu', VendorMenuController::class);
        Route::get('pesanan', [VendorPesananController::class, 'index'])->name('pesanan.index');
    });
});