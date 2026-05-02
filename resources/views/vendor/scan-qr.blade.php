@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-qrcode-scan"></i>
        </span>
        Scan QR Code Pesanan
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vendor.menu.index') }}">Vendor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Scan QR</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Scanner Section - Kiri -->
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Arahkan QR Code ke Kamera</h4>
                <p class="card-description">Scan QR Code dari customer untuk melihat detail pesanan</p>

                <!-- Container untuk Html5Qrcode -->
                <div class="text-center mb-4">
                    <div id="reader" style="width: 100%; max-width: 480px; margin: 0 auto; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;"></div>
                </div>

                <div class="text-center mb-3">
                    <div id="scannerStatus" class="mb-3">
                        <span class="badge bg-secondary p-2">
                            <i class="mdi mdi-camera"></i> Kamera belum aktif
                        </span>
                    </div>

                    <button type="button" class="btn btn-gradient-primary btn-lg me-2" id="btnStartScan">
                        <i class="mdi mdi-play"></i> Mulai Scan
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-lg" id="btnStopScan" style="display: none;">
                        <i class="mdi mdi-stop"></i> Stop Scanner
                    </button>
                </div>

                <div id="cameraError" class="alert alert-warning mt-3" style="display: none;">
                    <i class="mdi mdi-alert"></i>
                    <span id="errorMessage">Tidak dapat mengakses kamera</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Section - Kanan -->
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Detail Pesanan</h4>

                <!-- Default State -->
                <div id="defaultResult" class="text-center py-5">
                    <i class="mdi mdi-qrcode-scan" style="font-size: 5rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Scan QR Code untuk melihat detail pesanan</p>
                    <small class="text-muted">Tekan "Mulai Scan" untuk memulai</small>
                </div>

                <!-- Loading State -->
                <div id="loadingResult" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted">Mengambil detail pesanan...</p>
                </div>

                <!-- Success Result -->
                <div id="successResult" style="display: none;">
                    <div class="text-center mb-3">
                        <i class="mdi mdi-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-2 text-success">Pesanan Ditemukan!</h5>
                    </div>

                    <!-- Order Info -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">ID Pesanan</span>
                            <span class="fw-bold" id="orderId">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Nama Pemesan</span>
                            <span class="fw-bold" id="orderNama">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Metode Bayar</span>
                            <span class="fw-bold" id="orderMetode">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status Bayar</span>
                            <span class="badge" id="orderStatus">-</span>
                        </div>
                    </div>

                    <!-- Menu List -->
                    <div class="border-top pt-3">
                        <h6 class="mb-2">Menu yang Dipesan:</h6>
                        <div id="menuList" class="mb-3">
                            <!-- Menu items will be populated here -->
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary fs-5" id="orderTotal">Rp 0</span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-gradient-primary w-100 mt-3" id="btnScanUlang">
                        <i class="mdi mdi-refresh"></i> Scan Ulang
                    </button>
                </div>

                <!-- Error Result -->
                <div id="errorResult" style="display: none;">
                    <div class="text-center mb-3">
                        <i class="mdi mdi-alert-circle text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-2 text-danger">Pesanan Tidak Ditemukan</h5>
                    </div>

                    <div class="p-3 mb-3" style="background: #fff5f5; border-radius: 10px; border: 1px solid #ffebee;">
                        <p class="text-danger mb-0" id="errorMessage">QR Code tidak valid atau pesanan tidak ditemukan</p>
                    </div>

                    <button type="button" class="btn btn-outline-secondary w-100" id="btnRetryScan">
                        <i class="mdi mdi-refresh"></i> Scan Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Petunjuk Penggunaan -->
<div class="row">
    <div class="col-12">
        <div class="alert alert-info d-flex align-items-center mb-0">
            <i class="mdi mdi-information fs-4 me-3"></i>
            <div>
                <strong>Petunjuk Penggunaan:</strong>
                <ul class="mb-0 mt-2">
                    <li>Klik tombol <strong>"Mulai Scan"</strong> untuk mengaktifkan kamera</li>
                    <li>Arahkan QR Code dari customer ke area scanner</li>
                    <li>Sistem akan otomatis mendeteksi QR Code, memutar suara beep, dan menampilkan detail pesanan</li>
                    <li>Tekan <strong>"Scan Ulang"</strong> untuk scan pesanan berikutnya</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<!-- Html5-qrcode Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
$(document).ready(function() {
    const btnStartScan = document.getElementById('btnStartScan');
    const btnStopScan = document.getElementById('btnStopScan');
    const btnScanUlang = document.getElementById('btnScanUlang');
    const btnRetryScan = document.getElementById('btnRetryScan');
    const cameraError = document.getElementById('cameraError');
    const scannerStatus = document.getElementById('scannerStatus');

    // Result containers
    const defaultResult = document.getElementById('defaultResult');
    const loadingResult = document.getElementById('loadingResult');
    const successResult = document.getElementById('successResult');
    const errorResult = document.getElementById('errorResult');

    // Data elements
    const orderId = document.getElementById('orderId');
    const orderNama = document.getElementById('orderNama');
    const orderMetode = document.getElementById('orderMetode');
    const orderStatus = document.getElementById('orderStatus');
    const menuList = document.getElementById('menuList');
    const orderTotal = document.getElementById('orderTotal');
    const errorMessage = document.getElementById('errorMessage');

    let html5QrCode = null;
    let isScanning = false;

    // Fungsi untuk memutar beep sound
    function playBeep() {
        const audio = new Audio('{{ asset("sounds/notification.mp3") }}');
        audio.currentTime = 0;
        audio.play().catch(e => console.log('Audio play failed:', e));
    }

    // Fungsi untuk menampilkan hasil sukses
    function showSuccess(data) {
        defaultResult.style.display = 'none';
        loadingResult.style.display = 'none';
        successResult.style.display = 'block';
        errorResult.style.display = 'none';

        // Set data pesanan
        orderId.textContent = '#' + data.id;
        orderNama.textContent = data.nama_pemesan;
        orderMetode.textContent = data.metode_bayar;

        // Set status badge
        if (data.status_bayar === 'Lunas') {
            orderStatus.className = 'badge bg-success';
            orderStatus.textContent = 'LUNAS';
        } else {
            orderStatus.className = 'badge bg-warning';
            orderStatus.textContent = data.status_bayar;
        }

        // Clear and populate menu list
        menuList.innerHTML = '';
        if (data.menus && data.menus.length > 0) {
            data.menus.forEach(function(menu) {
                menuList.innerHTML += `
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <span class="fw-semibold">${menu.nama}</span>
                            <span class="text-muted ms-2">×${menu.jumlah}</span>
                        </div>
                        <span class="fw-semibold">Rp ${menu.subtotal.toLocaleString('id-ID')}</span>
                    </div>
                `;
            });
        } else {
            menuList.innerHTML = '<p class="text-muted">Tidak ada menu</p>';
        }

        orderTotal.textContent = 'Rp ' + data.total.toLocaleString('id-ID');
    }

    // Fungsi untuk menampilkan hasil error
    function showError(message) {
        defaultResult.style.display = 'none';
        loadingResult.style.display = 'none';
        successResult.style.display = 'none';
        errorResult.style.display = 'block';

        errorMessage.textContent = message;
    }

    // Fungsi untuk reset tampilan hasil
    function resetResult() {
        defaultResult.style.display = 'block';
        loadingResult.style.display = 'none';
        successResult.style.display = 'none';
        errorResult.style.display = 'none';
    }

    // Fungsi untuk ekstrak ID pesanan dari URL
    function extractPesananId(url) {
        // Format URL: /pesanan/{id}/verifikasi
        // Atau bisa langsung ID numerik
        const match = url.match(/\/pesanan\/(\d+)\/verifikasi/);
        if (match) {
            return match[1];
        }
        // Jika langsung ID numerik
        if (/^\d+$/.test(url)) {
            return url;
        }
        return null;
    }

    // Fungsi untuk mengambil detail pesanan dari API
    async function fetchOrderDetail(pesananId) {
        try {
            const response = await fetch('/vendor/scan/order/' + pesananId, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            return await response.json();
        } catch (error) {
            console.error('Error fetching order:', error);
            return { success: false, message: 'Terjadi kesalahan sistem' };
        }
    }

    // Fungsi untuk memulai scanner
    async function startScanner() {
        resetResult();

        const readerElement = document.getElementById('reader');
        if (!readerElement) {
            alert('Element reader tidak ditemukan!');
            return;
        }

        html5QrCode = new Html5Qrcode("reader");

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        try {
            console.log('Memulai scanner...');

            await html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText, decodedResult) => {
                    console.log('QR Code terdeteksi:', decodedText);

                    // Ekstrak ID pesanan dari URL
                    const pesananId = extractPesananId(decodedText);

                    if (!pesananId) {
                        showError('QR Code tidak valid. Pastikan QR Code berasal dari sistem kantin.');
                        playBeep();
                        return;
                    }

                    // Berhenti scan setelah berhasil mendeteksi
                    stopScanner();

                    // Mainkan beep sound
                    playBeep();

                    // Tampilkan loading
                    defaultResult.style.display = 'none';
                    loadingResult.style.display = 'block';
                    successResult.style.display = 'none';
                    errorResult.style.display = 'none';

                    // Ambil detail pesanan
                    fetchOrderDetail(pesananId).then(result => {
                        if (result.success) {
                            showSuccess(result.data);
                        } else {
                            showError(result.message || 'Pesanan tidak ditemukan');
                        }
                    });
                },
                (errorMessage) => {
                    // Error scanning - ini normal, scanning masih berjalan
                }
            );

            isScanning = true;
            // Update UI
            btnStartScan.style.display = 'none';
            btnStopScan.style.display = 'inline-block';
            scannerStatus.innerHTML = '<span class="badge bg-success p-2"><i class="mdi mdi-record"></i> Scanner Aktif</span>';
            cameraError.style.display = 'none';

        } catch (err) {
            console.error("Error starting scanner:", err);
            cameraError.style.display = 'block';
            document.getElementById('errorMessage').textContent =
                'Gagal mengakses kamera: ' + err.message;
        }
    }

    // Fungsi untuk menghentikan scanner
    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                html5QrCode = null;
                btnStartScan.style.display = 'inline-block';
                btnStopScan.style.display = 'none';
                scannerStatus.innerHTML = '<span class="badge bg-secondary p-2"><i class="mdi mdi-camera-off"></i> Scanner Berhenti</span>';
            }).catch(err => console.error("Error stopping scanner:", err));
        }
    }

    // Event listeners
    btnStartScan.addEventListener('click', startScanner);
    btnStopScan.addEventListener('click', stopScanner);
    btnScanUlang.addEventListener('click', startScanner);
    btnRetryScan.addEventListener('click', startScanner);

    // Cleanup on page leave
    window.addEventListener('beforeunload', function() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {});
        }
    });
});
</script>
@endsection