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
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Petunjuk Penggunaan</h4>

                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="mdi mdi-information fs-4 me-2"></i>
                    <div>
                        <strong>Cara Penggunaan:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Klik tombol <strong>"Mulai Scan"</strong> untuk mengaktifkan kamera</li>
                            <li>Arahkan barcode pada label barang ke area scanner</li>
                            <li>Sistem akan otomatis mendeteksi dan menampilkan informasi barang</li>
                            <li>Tekan <strong>"Scan Ulang"</strong> untuk scan barang berikutnya</li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <!-- Scanner Section -->
                    <div class="col-lg-8">
                        <div class="scanner-container text-center">
                            <div class="scanner-wrapper mb-3" style="display: inline-block; position: relative;">
                                <div id="reader" style="border-radius: 15px; overflow: hidden;"></div>
                                <div class="scanner-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;">
                                    <div style="width: 200px; height: 200px; border: 3px solid #667eea; border-radius: 20px; box-shadow: 0 0 0 4000px rgba(0, 0, 0, 0.4);"></div>
                                </div>
                            </div>
                            <p class="text-muted mb-3">
                                <i class="mdi mdi-camera"></i> Pastikan kamera diizinkan dan cahaya cukup
                            </p>

                            <div class="scanner-controls d-flex gap-2 justify-content-center flex-wrap">
                                <button id="btnStartScan" class="btn btn-gradient-primary" onclick="startScanner()">
                                    <i class="mdi mdi-play btn-icon-prepend"></i> Mulai Scan
                                </button>
                                <button id="btnStopScan" class="btn btn-secondary" onclick="stopScanner()" disabled>
                                    <i class="mdi mdi-stop btn-icon-prepend"></i> Stop Scanner
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Result Section -->
                    <div class="col-lg-4">
                        <div id="resultSection" class="result-card p-4 text-center" style="display: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; color: white; min-height: 300px; display: flex; flex-direction: column; justify-content: center;">
                            <div class="result-icon mb-3">
                                <i class="mdi mdi-check-circle" style="font-size: 4rem;"></i>
                            </div>
                            <div class="result-label text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.8;">ID Barang</div>
                            <div class="result-id mb-3" style="font-size: 1.2rem; font-weight: bold;" id="resultId">-</div>

                            <div class="result-info p-3 mb-3" style="background: rgba(255,255,255,0.2); border-radius: 10px;">
                                <div class="result-nama mb-2" style="font-size: 1.5rem; font-weight: bold;" id="resultNama">-</div>
                                <div class="result-harga" style="font-size: 1.8rem; font-weight: bold;" id="resultHarga">-</div>
                            </div>

                            <button class="btn btn-light" onclick="resetScanner()">
                                <i class="mdi mdi-refresh btn-icon-prepend"></i> Scan Ulang
                            </button>
                        </div>

                        <!-- Default state when no scan -->
                        <div id="defaultResult" class="p-4 text-center" style="background: #f8f9fa; border-radius: 15px; min-height: 300px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <i class="mdi mdi-barcode-scan" style="font-size: 5rem; color: #ccc;"></i>
                            <p class="text-muted mt-3 mb-0">Scan barcode untuk melihat informasi barang</p>
                        </div>

                        <!-- Error state -->
                        <div id="errorResult" class="p-4 text-center" style="display: none; background: linear-gradient(135deg, #f5365c 0%, #fb6340 100%); border-radius: 15px; color: white; min-height: 300px; display: none; flex-direction: column; justify-content: center;">
                            <div class="mb-3">
                                <i class="mdi mdi-alert-circle" style="font-size: 4rem;"></i>
                            </div>
                            <div class="mb-3" style="font-size: 1.2rem; font-weight: bold;" id="errorId">-</div>
                            <p class="mb-0" id="errorMessage">Barang tidak ditemukan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Animation Overlay -->
<div id="successAnimation" class="success-animation" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(102, 126, 234, 0.95); display: none; justify-content: center; align-items: center; z-index: 9999;">
    <div style="text-align: center; color: white;">
        <i class="mdi mdi-check-circle" style="font-size: 5rem; animation: bounce 0.5s ease;"></i>
        <h2 class="mt-3">Barcode Terdeteksi!</h2>
    </div>
</div>

<style>
@keyframes bounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}
</style>
@endsection

@section('javascript-page')
<!-- Html5-qrcode Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    // Global variable untuk scanner
    let html5QrCode;
    let isScanning = false;

    // Fungsi untuk memutar beep sound
    function playBeep() {
        const audio = new Audio('{{ asset("sounds/notification.mp3") }}');
        audio.currentTime = 0;
        audio.play().catch(e => console.log('Audio play failed:', e));
    }

    // Fungsi untuk menampilkan animasi sukses
    function showSuccessAnimation() {
        const anim = document.getElementById('successAnimation');
        anim.style.display = 'flex';
        setTimeout(() => {
            anim.style.display = 'none';
        }, 1000);
    }

    // Fungsi untuk mencari barang dari API
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

    // Fungsi untuk menampilkan hasil scan berhasil
    function displaySuccessResult(data) {
        document.getElementById('resultSection').style.display = 'flex';
        document.getElementById('defaultResult').style.display = 'none';
        document.getElementById('errorResult').style.display = 'none';

        document.getElementById('resultId').textContent = data.id_barang;
        document.getElementById('resultNama').textContent = data.nama;
        document.getElementById('resultHarga').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.harga);
    }

    // Fungsi untuk menampilkan hasil scan error
    function displayErrorResult(idBarang, message) {
        document.getElementById('errorResult').style.display = 'flex';
        document.getElementById('resultSection').style.display = 'none';
        document.getElementById('defaultResult').style.display = 'none';

        document.getElementById('errorId').textContent = idBarang;
        document.getElementById('errorMessage').textContent = message;
    }

    // Fungsi untuk reset tampilan hasil
    function resetResultDisplay() {
        document.getElementById('resultSection').style.display = 'none';
        document.getElementById('defaultResult').style.display = 'flex';
        document.getElementById('errorResult').style.display = 'none';
    }

    // Fungsi untuk memulai scanner
    async function startScanner() {
        html5QrCode = new Html5Qrcode("reader");

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
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

                    // Tampilkan animasi sukses
                    showSuccessAnimation();

                    // Ambil info barang
                    fetchBarangInfo(decodedText).then(result => {
                        if (result.success) {
                            displaySuccessResult(result.data);
                        } else {
                            displayErrorResult(decodedText, result.message || 'Barang tidak ditemukan');
                        }
                    });
                },
                (errorMessage) => {
                    // Error saat scanning (normal, tidak perlu ditampilkan)
                }
            );

            isScanning = true;
            document.getElementById('btnStartScan').disabled = true;
            document.getElementById('btnStopScan').disabled = false;
            resetResultDisplay();

        } catch (err) {
            console.error("Error starting scanner:", err);
            alert("Gagal mengakses kamera. Pastikan kamera tersedia dan izin sudah diberikan.");
        }
    }

    // Fungsi untuk menghentikan scanner
    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                document.getElementById('btnStartScan').disabled = false;
                document.getElementById('btnStopScan').disabled = true;
            }).catch(err => console.error("Error stopping scanner:", err));
        }
    }

    // Fungsi untuk reset scanner
    function resetScanner() {
        startScanner();
    }
</script>
@endsection