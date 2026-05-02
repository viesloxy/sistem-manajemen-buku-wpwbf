@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-qrcode-scanner"></i>
        </span> Scanner Barcode & QR Code
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Scanner</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Scanner Section - Kiri -->
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Arahkan Barcode ke Kamera</h4>
                <p class="card-description">Scan label barang untuk melihat informasi produk</p>

                <div class="text-center mb-4">
                    <video id="videoScanner" width="100%" autoplay playsinline
                           style="border: 2px solid #ddd; border-radius: 8px; background: #000; max-width: 480px;">
                    </video>
                    <canvas id="canvasScanner" width="480" height="320" style="display: none;"></canvas>
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
                <h4 class="card-title">Hasil Scan</h4>

                <!-- Default State -->
                <div id="defaultResult" class="text-center py-5">
                    <i class="mdi mdi-barcode-scan" style="font-size: 5rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Scan barcode untuk melihat informasi barang</p>
                    <small class="text-muted">Tekan "Mulai Scan" untuk memulai</small>
                </div>

                <!-- Loading State -->
                <div id="loadingResult" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted">Mencari informasi barang...</p>
                </div>

                <!-- Success Result -->
                <div id="successResult" style="display: none;">
                    <div class="text-center mb-4">
                        <i class="mdi mdi-check-circle text-success" style="font-size: 4rem;"></i>
                        <h5 class="mt-2 text-success">Barcode Terdeteksi!</h5>
                    </div>

                    <div class="p-3 mb-3" style="background: #f8f9fa; border-radius: 10px;">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 100px;">ID Barang</td>
                                <td class="fw-bold" id="resultId">-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama</td>
                                <td class="fw-bold fs-5" id="resultNama">-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga</td>
                                <td class="fw-bold text-primary fs-4" id="resultHarga">-</td>
                            </tr>
                        </table>
                    </div>

                    <button type="button" class="btn btn-gradient-primary w-100" id="btnScanUlang">
                        <i class="mdi mdi-refresh"></i> Scan Ulang
                    </button>
                </div>

                <!-- Error Result -->
                <div id="errorResult" style="display: none;">
                    <div class="text-center mb-4">
                        <i class="mdi mdi-alert-circle text-danger" style="font-size: 4rem;"></i>
                        <h5 class="mt-2 text-danger">Barang Tidak Ditemukan</h5>
                    </div>

                    <div class="p-3 mb-3" style="background: #fff5f5; border-radius: 10px; border: 1px solid #ffebee;">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 100px;">ID Barcode</td>
                                <td class="fw-bold text-danger" id="errorId">-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Pesan</td>
                                <td id="errorMessage">Barang tidak ditemukan di database</td>
                            </tr>
                        </table>
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
                    <li>Arahkan barcode pada label barang ke area scanner</li>
                    <li>Sistem akan otomatis mendeteksi barcode, memutar suara beep, dan menampilkan informasi barang</li>
                    <li>Tekan <strong>"Scan Ulang"</strong> untuk scan barang berikutnya</li>
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
    const video = document.getElementById('videoScanner');
    const canvas = document.getElementById('canvasScanner');
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

    // Result data elements
    const resultId = document.getElementById('resultId');
    const resultNama = document.getElementById('resultNama');
    const resultHarga = document.getElementById('resultHarga');
    const errorId = document.getElementById('errorId');
    const errorMessage = document.getElementById('errorMessage');

    let html5QrCode = null;
    let stream = null;

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

        resultId.textContent = data.id_barang;
        resultNama.textContent = data.nama;
        resultHarga.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.harga);
    }

    // Fungsi untuk menampilkan hasil error
    function showError(idBarang, message) {
        defaultResult.style.display = 'none';
        loadingResult.style.display = 'none';
        successResult.style.display = 'none';
        errorResult.style.display = 'block';

        errorId.textContent = idBarang;
        errorMessage.textContent = message;
    }

    // Fungsi untuk reset tampilan hasil
    function resetResult() {
        defaultResult.style.display = 'block';
        loadingResult.style.display = 'none';
        successResult.style.display = 'none';
        errorResult.style.display = 'none';
    }

    // Fungsi untuk mengambil info barang dari API
    async function fetchBarangInfo(idBarang) {
        try {
            const response = await fetch('/scanner/cek-barang', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id_barang: idBarang })
            });
            return await response.json();
        } catch (error) {
            console.error('Error fetching barang:', error);
            return { success: false, message: 'Terjadi kesalahan sistem' };
        }
    }

    // Fungsi untuk memulai scanner
    async function startScanner() {
        resetResult();

        html5QrCode = new Html5Qrcode("videoScanner");

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 100 },
            aspectRatio: 1.5
        };

        try {
            await html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText, decodedResult) => {
                    // Berhenti scan setelah berhasil mendeteksi
                    stopScanner();

                    // Mainkan beep sound
                    playBeep();

                    // Tampilkan loading
                    defaultResult.style.display = 'none';
                    loadingResult.style.display = 'block';
                    successResult.style.display = 'none';
                    errorResult.style.display = 'none';

                    // Ambil info barang
                    fetchBarangInfo(decodedText).then(result => {
                        if (result.success) {
                            showSuccess(result.data);
                        } else {
                            showError(decodedText, result.message || 'Barang tidak ditemukan');
                        }
                    });
                },
                (errorMessage) => {
                    // Error scanning - normal, abaikan
                }
            );

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
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
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
        if (html5QrCode) {
            html5QrCode.stop().then(() => {});
        }
    });
});
</script>
@endsection