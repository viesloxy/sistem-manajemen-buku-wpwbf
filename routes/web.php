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
use App\Http\Controllers\CustomerAccessController;
use App\Http\Controllers\ScannerBarangController;
use App\Http\Controllers\VendorScanController;
use App\Http\Controllers\GeolocationController;
use App\Http\Controllers\BarcodeController;

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

// QR Code Page (dengan layout, untuk customer lihat QR)
Route::get('/qrcode/{id}/page', [CustomerController::class, 'showQrPage'])->name('customer.qrcode.page');

// Verifikasi Pesanan via QR Code
Route::get('/pesanan/{id}/verifikasi', [CustomerController::class, 'verifyPesanan'])->name('customer.verify');

// =========================================================================
// AREA ANTRIAN — KANTIN BUKU (Modul 10 SSE)
// =========================================================================
use App\Http\Controllers\AntrianGuestController;
use App\Http\Controllers\AntrianAdminController;
use App\Http\Controllers\AntrianSSEController;

Route::prefix('antrian')->name('antrian.')->group(function () {

    // --- SSE Endpoint (publik — untuk papan antrian & guest monitoring) ---
    Route::get('/sse', [AntrianSSEController::class, 'stream'])->name('sse');

    // --- Guest Routes (publik, tanpa login, tapi perlu CSRF protection) ---
    Route::middleware(['web'])->group(function () {
        Route::get('/guest', [AntrianGuestController::class, 'guest'])->name('guest');
        Route::post('/daftar', [AntrianGuestController::class, 'daftar'])->name('daftar');
        Route::get('/saya/{antrian}', [AntrianGuestController::class, 'saya'])->name('saya');
    });

    // --- Admin Antrian (harus login + role antrian_admin) ---
    Route::middleware(['auth', 'role:antrian_admin'])->group(function () {
        Route::get('/admin', [AntrianAdminController::class, 'index'])->name('admin');
        Route::post('/admin/panggil', [AntrianAdminController::class, 'panggilBerikutnya'])->name('admin.panggil');
        Route::post('/admin/selesai/{antrian}', [AntrianAdminController::class, 'selesaikan'])->name('admin.selesai');
        Route::post('/admin/terlambat/{antrian}', [AntrianAdminController::class, 'tandaiTerlambat'])->name('admin.terlambat');
        Route::post('/admin/panggil-ulang/{antrian}', [AntrianAdminController::class, 'panggilUlang'])->name('admin.panggil-ulang');
        Route::post('/admin/reset', [AntrianAdminController::class, 'resetHariIni'])->name('admin.reset');
    });

    // --- Papan Antrian (publik, tanpa login) ---
    Route::get('/papan', [AntrianPapanController::class, 'index'])->name('papan');
});


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

    // --- STUDI KASUS 3: AKSES KAMERA - CUSTOMER MANAGEMENT (ADMIN) ---
    Route::prefix('customer-data')->name('customer-data.')->group(function () {
        Route::get('/', [CustomerAccessController::class, 'index'])->name('index');
        Route::get('/create-blob', [CustomerAccessController::class, 'createBlob'])->name('create-blob');
        Route::post('/store-blob', [CustomerAccessController::class, 'storeBlob'])->name('store-blob');
        Route::get('/create-file', [CustomerAccessController::class, 'createFile'])->name('create-file');
        Route::post('/store-file', [CustomerAccessController::class, 'storeFile'])->name('store-file');
        Route::get('/{id}', [CustomerAccessController::class, 'show'])->name('show');
        Route::delete('/{id}', [CustomerAccessController::class, 'destroy'])->name('destroy');
    });

    // --- PRaktikum 1: SCANNER BARCODE & QR CODE ---
    Route::get('/scanner', [ScannerBarangController::class, 'index'])->name('scanner.index');
    Route::post('/scanner/cek-barang', [ScannerBarangController::class, 'cekBarang'])->name('scanner.cekBarang');

    // --- PRaktikum 2: VENDOR SCAN QR CODE ---
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/scan-qr', [VendorScanController::class, 'index'])->name('scan-qr');
        Route::get('/scan/order/{id}', [VendorScanController::class, 'getOrderDetail'])->name('scan.order');
    });

    // --- FITUR VENDOR ---
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::resource('menu', VendorMenuController::class);
        Route::get('pesanan', [VendorPesananController::class, 'index'])->name('pesanan.index');
    });

    // ========================================================================
    // GEOLOCATION MODULE
    // ========================================================================

    // Admin Geolocation Routes
    Route::prefix('geolocation')->name('geolocation.')->group(function () {
        Route::get('/map', [GeolocationController::class, 'map'])->name('map');
        Route::get('/list', [GeolocationController::class, 'list'])->name('list');
        Route::get('/create', [GeolocationController::class, 'create'])->name('create');
        Route::post('/', [GeolocationController::class, 'store'])->name('store');
        Route::delete('/{id}', [GeolocationController::class, 'destroy'])->name('destroy');

        // Admin Barcode Routes
        Route::prefix('barcode')->name('barcode.')->group(function () {
            Route::get('/', [BarcodeController::class, 'adminIndex'])->name('index');
            Route::get('/create', [BarcodeController::class, 'create'])->name('create');
            Route::post('/', [BarcodeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [BarcodeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BarcodeController::class, 'update'])->name('update');
            Route::delete('/{id}', [BarcodeController::class, 'destroy'])->name('destroy');
            Route::get('/print/{id}', [BarcodeController::class, 'print'])->name('print');
        });
    });

    // Vendor Geolocation Routes
    Route::prefix('vendor/geolocation')->name('vendor.geolocation.')->group(function () {
        Route::get('/titik-awal', [GeolocationController::class, 'titikAwal'])->name('titik-awal');
        Route::get('/titik-kunjungan', [GeolocationController::class, 'titikKunjungan'])->name('titik-kunjungan');
        Route::post('/store', [GeolocationController::class, 'vendorStore'])->name('store');
    });

    // API Routes
    Route::prefix('api')->group(function () {
        Route::get('/geolocation', [GeolocationController::class, 'apiIndex']);
        Route::get('/geolocation-by-vendor', [GeolocationController::class, 'apiIndexByVendor']);
        Route::post('/geolocation', [GeolocationController::class, 'apiStore']);
        Route::get('/barcodes', [BarcodeController::class, 'index']);
        Route::get('/barcodes/{barcode}', [BarcodeController::class, 'show']);
    });
});