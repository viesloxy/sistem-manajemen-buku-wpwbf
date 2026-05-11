{{-- resources/views/vendor/geolocation/titik-kunjungan.blade.php --}}

@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.css" />
<style>
    .scanner-container {
        text-align: center;
        margin-bottom: 20px;
    }
    .data-card {
        border-left: 4px solid #6610f2;
        background: #f8f7ff;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    .result-card {
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }
    .result-card.diterima {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    .result-card.ditolak {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    #reader {
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-qrcode-scan"></i>
        </span> Titik Kunjungan
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Scan barcode & validasi lokasi <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

{{-- Success Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Scanner Card --}}
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="mdi mdi-barcode text-primary"></i> Scan Barcode Toko</h4>
                <p class="card-description">Arahkan barcode ke kamera untuk scan</p>
                <hr class="mb-4">

                {{-- Scanner Container --}}
                <div class="scanner-container mb-4">
                    <div id="reader" style="width: 100%; max-width: 480px; margin: 0 auto; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;"></div>
                </div>

                {{-- Scanner Status --}}
                <div id="scannerStatus" class="mb-3 text-center">
                    <span class="badge bg-secondary p-2">
                        <i class="mdi mdi-camera"></i> Kamera belum aktif
                    </span>
                </div>

                {{-- Scanner Buttons --}}
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-gradient-primary btn-lg me-2" id="btnStartScan">
                        <i class="mdi mdi-play"></i> Mulai Scan
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-lg" id="btnStopScan" style="display: none;">
                        <i class="mdi mdi-stop"></i> Stop Scanner
                    </button>
                </div>

                {{-- Camera Error --}}
                <div id="cameraError" class="alert alert-warning mt-3" style="display: none;">
                    <i class="mdi mdi-alert"></i>
                    <span id="errorMessage">Tidak dapat mengakses kamera</span>
                </div>

                {{-- Manual Input --}}
                <div class="mt-4">
                    <label class="form-label text-muted small">Atau input manual barcode:</label>
                    <div class="input-group">
                        <input type="text" id="barcode-input" class="form-control border-primary"
                               placeholder="Ketik atau scan barcode...">
                        <button type="button" id="btn-cari-barcode" class="btn btn-outline-primary">
                            <i class="mdi mdi-magnify btn-icon-prepend"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Petunjuk Card --}}
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="mdi mdi-information-outline text-info"></i> Panduan</h4>
                <hr class="mb-4">

                <h6 class="text-primary"><i class="mdi mdi-barcode"></i> Langkah Scan Barcode</h6>
                <ol class="small mb-4">
                    <li>Klik tombol <strong>"Mulai Scan"</strong></li>
                    <li>Izinkan akses kamera jika diminta</li>
                    <li>Arahkan barcode ke kamera</li>
                    <li>Setelah terdeteksi, terdengar <strong>beep</strong></li>
                    <li>Data toko akan otomatis muncul</li>
                </ol>

                <hr>

                <h6 class="text-success"><i class="mdi mdi-check-circle"></i> Aturan Validasi</h6>
                <ul class="small mb-2">
                    <li><strong>Jarak < Accuracy Target</strong> → <span class="badge badge-success">DITERIMA</span></li>
                    <li><strong>Jarak ≥ Accuracy Target</strong> → <span class="badge badge-danger">DITOLAK</span></li>
                </ul>
                <div class="alert alert-info small mb-0">
                    <i class="mdi mdi-lightbulb-outline"></i> Contoh: Accuracy = 50m<br>
                    Jika jarak ≤ 50m → DITERIMA<br>
                    Jika jarak > 50m → DITOLAK
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Data Toko Card --}}
<div class="row" id="barcode-data-section" style="display: none;">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="mdi mdi-database text-info"></i> Data Toko Target</h4>
                <div class="data-card">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="text-muted small">Kode Barcode</label>
                            <h4 id="barcode-code">-</h4>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small">Nama Toko</label>
                            <h4 id="barcode-nama">-</h4>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small">Accuracy Target</label>
                            <h4 id="barcode-accuracy">-</h4>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small">Status</label>
                            <div id="barcode-status-badge"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="text-muted small">Latitude Target</label>
                            <p class="mb-0 fw-bold" id="barcode-lat">-</p>
                        </div>
                        <div class="col-md-2">
                            <label class="text-muted small">Longitude Target</label>
                            <p class="mb-0 fw-bold" id="barcode-lng">-</p>
                        </div>
                        <div class="col-md-2">
                            <label class="text-muted small">Jarak</label>
                            <p class="mb-0 fw-bold" id="barcode-distance">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lokasi Kunjungan Card --}}
<div class="row" id="kunjungan-section" style="display: none;">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="mdi mdi-map-marker-radius text-warning"></i> Data Lokasi Kunjungan</h4>
                <hr class="mb-4">

                <form id="formKunjungan">
                    @csrf
                    <input type="hidden" id="selected-barcode" name="barcode_id">
                    <input type="hidden" id="visit-type" name="type" value="titik_kunjungan">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="visit-latitude">Latitude <span class="text-danger">*</span></label>
                                <input type="text" id="visit-latitude" name="latitude"
                                       class="form-control border-primary" readonly required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="visit-longitude">Longitude <span class="text-danger">*</span></label>
                                <input type="text" id="visit-longitude" name="longitude"
                                       class="form-control border-primary" readonly required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="visit-accuracy">Accuracy (meter)</label>
                                <input type="text" id="visit-accuracy" name="accuracy"
                                       class="form-control border-primary" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Result Status --}}
                    <div class="result-card mt-3" id="result-status" style="display: none;">
                        <h3 id="result-status-text">-</h3>
                        <p id="result-distance"></p>
                    </div>

                    <div class="mt-4">
                        <button type="button" id="btn-ambil-lokasi" class="btn btn-gradient-success btn-lg me-2">
                            <i class="mdi mdi-satellite-variant btn-icon-prepend"></i> Ambil Lokasi Saat Ini
                        </button>
                        <button type="submit" id="btn-simpan" class="btn btn-gradient-primary btn-lg" style="display: none;">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i> Simpan Kunjungan
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="resetAll()">
                            <i class="mdi mdi-refresh btn-icon-prepend"></i> Reset / Scan Ulang
                        </button>
                    </div>
                </form>
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
    const scannerStatus = document.getElementById('scannerStatus');
    const cameraError = document.getElementById('cameraError');

    let html5QrCode = null;
    let isScanning = false;

    // Fungsi untuk memutar beep sound
    function playBeep() {
        const audio = new Audio('{{ asset("sounds/notification.mp3") }}');
        audio.currentTime = 0;
        audio.play().catch(e => console.log('Audio play failed:', e));
    }

    // Fungsi untuk reset scanner
    function resetScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                html5QrCode = null;
            }).catch(err => console.error("Error stopping scanner:", err));
        }
        btnStartScan.style.display = 'inline-block';
        btnStopScan.style.display = 'none';
        scannerStatus.innerHTML = '<span class="badge bg-secondary p-2"><i class="mdi mdi-camera"></i> Kamera belum aktif</span>';
    }

    // Fungsi untuk memulai scanner
    async function startScanner() {
        const readerElement = document.getElementById('reader');
        if (!readerElement) {
            alert('Element reader tidak ditemukan!');
            return;
        }

        html5QrCode = new Html5Qrcode("reader");

        const config = {
            fps: 10,
            qrbox: { width: 300, height: 150 },
            aspectRatio: 1.333
        };

        try {
            console.log('Memulai scanner...');

            await html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText, decodedResult) => {
                    console.log('Barcode terdeteksi:', decodedText);

                    // Berhenti scan setelah berhasil mendeteksi
                    stopScanner();

                    // Mainkan beep sound
                    playBeep();

                    // Set value dan fetch data
                    document.getElementById('barcode-input').value = decodedText;
                    fetchBarcodeData(decodedText);
                },
                (errorMessage) => {
                    // Error scanning - ini normal, scanning masih berjalan
                    // console.log('Scanning...');
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
            document.getElementById('errorMessage').textContent = 'Gagal mengakses kamera: ' + err.message;
        }
    }

    // Fungsi untuk menghentikan scanner
    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                btnStartScan.style.display = 'inline-block';
                btnStopScan.style.display = 'none';
                scannerStatus.innerHTML = '<span class="badge bg-secondary p-2"><i class="mdi mdi-camera-off"></i> Scanner Berhenti</span>';
            }).catch(err => console.error("Error stopping scanner:", err));
        }
    }

    // Event listeners untuk scanner
    btnStartScan.addEventListener('click', startScanner);
    btnStopScan.addEventListener('click', stopScanner);

    // Cleanup on page leave
    window.addEventListener('beforeunload', function() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {});
        }
    });

    // Fetch barcode data
    function fetchBarcodeData(barcode) {
        fetch(`/api/barcodes/${encodeURIComponent(barcode)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showBarcodeData(data.data);
                } else {
                    alert('Barcode tidak ditemukan!');
                    resetAll();
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
                resetAll();
            });
    }

    document.getElementById('btn-cari-barcode').addEventListener('click', function() {
        const barcode = document.getElementById('barcode-input').value.trim();
        if (barcode) {
            fetchBarcodeData(barcode);
        } else {
            alert('Silakan masukkan barcode terlebih dahulu!');
        }
    });

    // Show barcode data
    function showBarcodeData(data) {
        document.getElementById('barcode-code').textContent = data.barcode;
        document.getElementById('barcode-nama').textContent = data.nama_toko;
        document.getElementById('barcode-accuracy').textContent = data.accuracy + ' meter';
        document.getElementById('barcode-lat').textContent = data.latitude;
        document.getElementById('barcode-lng').textContent = data.longitude;
        document.getElementById('selected-barcode').value = data.id;

        document.getElementById('barcode-data-section').style.display = 'block';
        document.getElementById('kunjungan-section').style.display = 'block';
    }

    // Ambil Lokasi (GPS)
    document.getElementById('btn-ambil-lokasi').addEventListener('click', function() {
        if (!document.getElementById('selected-barcode').value) {
            alert('Silakan scan barcode terlebih dahulu!');
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin btn-icon-prepend"></i> Mengambil lokasi...';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const acc = position.coords.accuracy;

                    document.getElementById('visit-latitude').value = lat;
                    document.getElementById('visit-longitude').value = lng;
                    document.getElementById('visit-accuracy').value = acc;

                    const targetLat = parseFloat(document.getElementById('barcode-lat').textContent);
                    const targetLng = parseFloat(document.getElementById('barcode-lng').textContent);
                    const accuracyTarget = parseFloat(document.getElementById('barcode-accuracy').textContent);

                    const distance = calculateDistance(lat, lng, targetLat, targetLng);
                    const status = distance <= accuracyTarget ? 'diterima' : 'ditolak';

                    document.getElementById('barcode-distance').textContent = Math.round(distance) + ' meter';

                    const badgeClass = status === 'diterima' ? 'badge-success' : 'badge-danger';
                    const badgeText = status === 'diterima' ? 'DITERIMA' : 'DITOLAK';
                    document.getElementById('barcode-status-badge').innerHTML =
                        `<span class="badge ${badgeClass}">${badgeText}</span>`;

                    const resultCard = document.getElementById('result-status');
                    resultCard.style.display = 'block';
                    resultCard.className = 'result-card ' + status;
                    document.getElementById('result-status-text').textContent =
                        status === 'diterima' ? '✓ LOKASI DITERIMA' : '✗ LOKASI DITOLAK';
                    document.getElementById('result-distance').textContent =
                        `Jarak: ${Math.round(distance)} meter (Batas: ${accuracyTarget} meter)`;

                    btn.disabled = false;
                    btn.innerHTML = '<i class="mdi mdi-check btn-icon-prepend"></i> Lokasi Terdeteksi!';
                    document.getElementById('btn-simpan').style.display = 'inline-block';
                },
                function(error) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="mdi mdi-satellite-variant btn-icon-prepend"></i> Ambil Lokasi Saat Ini';
                    alert('Tidak dapat mendapatkan lokasi: ' + error.message);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="mdi mdi-satellite-variant btn-icon-prepend"></i> Ambil Lokasi Saat Ini';
            alert('Geolocation tidak didukung browser ini.');
        }
    });

    // Haversine formula
    function calculateDistance(lat1, lng1, lat2, lng2) {
        const R = 6371000;
        const dLat = toRad(lat2 - lat1);
        const dLng = toRad(lng2 - lng1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                  Math.sin(dLng/2) * Math.sin(dLng/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function toRad(deg) {
        return deg * (Math.PI/180);
    }

    // Submit form
    document.getElementById('formKunjungan').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const statusText = document.getElementById('result-status-text').textContent;
        const status = statusText.includes('DITERIMA') ? 'diterima' : 'ditolak';
        formData.append('status', status);

        fetch('{{ route('vendor.geolocation.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Kunjungan berhasil disimpan! Status: ' + status.toUpperCase());
                resetAll();
            } else {
                alert('Gagal menyimpan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error.message);
        });
    });

    // Reset all
    function resetAll() {
        document.getElementById('formKunjungan').reset();
        document.getElementById('barcode-input').value = '';
        document.getElementById('barcode-data-section').style.display = 'none';
        document.getElementById('kunjungan-section').style.display = 'none';
        document.getElementById('result-status').style.display = 'none';
        document.getElementById('btn-simpan').style.display = 'none';
        document.getElementById('btn-ambil-lokasi').disabled = false;
        document.getElementById('btn-ambil-lokasi').innerHTML = '<i class="mdi mdi-satellite-variant btn-icon-prepend"></i> Ambil Lokasi Saat Ini';
        resetScanner();
    }
});
</script>
@endsection