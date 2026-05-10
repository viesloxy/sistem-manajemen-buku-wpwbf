{{-- resources/views/vendor/geolocation/titik-kunjungan.blade.php --}}

@extends('layouts.app')

@section('title', 'Titik Kunjungan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.css" />
<style>
    .scanner-box {
        border: 3px dashed #667eea;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        background: #f8f9ff;
        margin-bottom: 20px;
    }
    .scanner-box.active {
        border-color: #28a745;
        background: #f0fff4;
    }
    .data-card {
        border-left: 4px solid #667eea;
        background: #f8f9ff;
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
    .beep-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        background: #dc3545;
        border-radius: 50%;
        animation: blink 1s infinite;
    }
    @keyframes blink {
        50% { opacity: 0.3; }
    }
    .camera-icon {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card: Scanner Barcode --}}
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-barcode"></i> Scan Barcode Toko</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="scanner-box" id="scanner-container">
                        <div class="camera-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h5>Arahkan Kamera ke Barcode</h5>
                        <p class="text-muted">Klik tombol "Mulai Scan" untuk membuka kamera</p>
                        <button type="button" id="btn-start-scan" class="btn btn-primary btn-lg">
                            <i class="fas fa-camera"></i> Mulai Scan
                        </button>
                        <button type="button" id="btn-stop-scan" class="btn btn-danger btn-lg" style="display: none;">
                            <i class="fas fa-stop"></i> Stop Scan
                        </button>
                    </div>
                    <div id="reader" style="width: 100%; display: none;"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">atau input manual barcode:</label>
                    <div class="input-group mb-3">
                        <input type="text" id="barcode-input" class="form-control"
                               placeholder="Ketik atau scan barcode...">
                        <button type="button" id="btn-cari-barcode" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Data dari DB (Hasil Scan) --}}
    <div class="card mb-3" id="barcode-data-section" style="display: none;">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-database"></i> Data Toko Target</h5>
        </div>
        <div class="card-body">
            <div class="data-card">
                <div class="row">
                    <div class="col-md-4">
                        <label class="text-muted small">Kode Barcode</label>
                        <h4 id="barcode-code">-</h4>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Nama Toko</label>
                        <h4 id="barcode-nama">-</h4>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Accuracy Target</label>
                        <h4 id="barcode-accuracy">-</h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label class="text-muted small">Latitude Target</label>
                        <p class="mb-0" id="barcode-lat">-</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Longitude Target</label>
                        <p class="mb-0" id="barcode-lng">-</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Distance</label>
                        <p class="mb-0" id="barcode-distance">-</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Status</label>
                        <div id="barcode-status-badge"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Input Lokasi Kunjungan --}}
    <div class="card mb-3" id="kunjungan-section" style="display: none;">
        <div class="card-header bg-warning">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Data Lokasi Kunjungan</h5>
        </div>
        <div class="card-body">
            <form id="formKunjungan">
                @csrf
                <input type="hidden" id="selected-barcode" name="barcode_id">
                <input type="hidden" id="visit-type" name="type" value="titik_kunjungan">

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Latitude <span class="text-danger">*</span></label>
                            <input type="text" id="visit-latitude" name="latitude"
                                   class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Longitude <span class="text-danger">*</span></label>
                            <input type="text" id="visit-longitude" name="longitude"
                                   class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Accuracy (meter)</label>
                            <input type="text" id="visit-accuracy" name="accuracy"
                                   class="form-control" readonly>
                        </div>
                    </div>
                </div>

                {{-- Result Status --}}
                <div class="result-card mt-3" id="result-status" style="display: none;">
                    <h3 id="result-status-text">-</h3>
                    <p id="result-distance"></p>
                </div>

                <div class="mb-3 mt-3">
                    <button type="button" id="btn-ambil-lokasi" class="btn btn-success btn-lg">
                        <i class="fas fa-satellite-dish"></i> Ambil Lokasi Saat Ini
                    </button>
                    <button type="submit" id="btn-simpan" class="btn btn-primary btn-lg" style="display: none;">
                        <i class="fas fa-save"></i> Simpan Kunjungan
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg ms-2" onclick="resetAll()">
                        <i class="fas fa-redo"></i> Reset / Scan Ulang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Panduan --}}
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-book"></i> Panduan Penggunaan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-barcode text-primary"></i> Langkah Scan Barcode</h6>
                    <ol class="small">
                        <li>Klik tombol <strong>"Mulai Scan"</strong></li>
                        <li>Izinkan akses kamera jika diminta</li>
                        <li>Arahkan barcode ke kamera</li>
                        <li>Setelah terdeteksi, akan terdengar <strong>beep</strong></li>
                        <li>Data toko akan otomatis muncul</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-check-circle text-success"></i> Aturan Validasi</h6>
                    <ul class="small">
                        <li><strong>Jarak < Accuracy Target</strong> → <span class="badge bg-success">DITERIMA</span></li>
                        <li><strong>Jarak ≥ Accuracy Target</strong> → <span class="badge bg-danger">DITOLAK</span></li>
                    </ul>
                    <p class="small text-muted">
                        Contoh: Accuracy Target = 50 meter<br>
                        Jika jarak Anda ke toko ≤ 50 meter → DITERIMA<br>
                        Jika jarak > 50 meter → DITOLAK
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Html5-qrcode Library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrCode;
    let isScanning = false;

    // Audio beep function
    function playBeep() {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleQYMRaTR44pEIghTmtnXrHoKFDuV1tyyZBgQJZTX4bNmGRE/l9rjtHEkGimV2N66ZyAVMZPW5LhxKhczmNTiu3UsHziX1+S8eDIjOpjW5b57NCc+nNjkvX00JTya1+S/fzQpQJzY5cB/NCxDoNjkwn81Lkah2eXDgDUwSKPZ5cSAVjFKptnmxYRWMk2p2efGhVgzTqrZ58eGWDNQrdroxoZYNFKu2unIhVoyU7Db6smJWTJVs93ry4paNVW13uzLi181VrXi78yMXzZYt+LuzJBgOVq44+/NjmI7X7vm8M6RYT1hu+fzz5RkPmO86PLPlmY+Y77q8s+WZz9kvuzyz5dnP2S/7PLPl2c/ZMDs8s+XaD9kwO3yz5doP2TA7vLP+9g=');
        audio.play().catch(e => console.log('Beep audio not supported'));
    }

    // Start Scanner
    document.getElementById('btn-start-scan').addEventListener('click', function() {
        html5QrCode = new Html5Qrcode("reader");

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText) => {
                // 1. Bunyi beep
                playBeep();

                // 2. Stop scanner
                stopScanner();

                // 3. Isi barcode input
                document.getElementById('barcode-input').value = decodedText;

                // 4. Fetch data
                fetchBarcodeData(decodedText);
            },
            (errorMessage) => {
                // Scanning in progress...
            }
        ).then(() => {
            isScanning = true;
            document.getElementById('reader').style.display = 'block';
            document.getElementById('btn-start-scan').style.display = 'none';
            document.getElementById('btn-stop-scan').style.display = 'inline-block';
            document.getElementById('scanner-container').classList.add('active');
        }).catch((err) => {
            alert('Kamera tidak tersedia atau tidak diizinkan: ' + err);
        });
    });

    // Stop Scanner
    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                document.getElementById('reader').style.display = 'none';
                document.getElementById('btn-start-scan').style.display = 'inline-block';
                document.getElementById('btn-stop-scan').style.display = 'none';
                document.getElementById('scanner-container').classList.remove('active');
            });
        }
    }

    document.getElementById('btn-stop-scan').addEventListener('click', stopScanner);

    // Fetch barcode data (from API)
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

    // Manual barcode input
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
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengambil lokasi...';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const acc = position.coords.accuracy;

                    document.getElementById('visit-latitude').value = lat;
                    document.getElementById('visit-longitude').value = lng;
                    document.getElementById('visit-accuracy').value = acc;

                    // Ambil data target dari barcode
                    const targetLat = parseFloat(document.getElementById('barcode-lat').textContent);
                    const targetLng = parseFloat(document.getElementById('barcode-lng').textContent);
                    const accuracyTarget = parseFloat(document.getElementById('barcode-accuracy').textContent);

                    // Hitung jarak (Haversine formula)
                    const distance = calculateDistance(lat, lng, targetLat, targetLng);
                    const status = distance <= accuracyTarget ? 'diterima' : 'ditolak';

                    // Update UI
                    document.getElementById('barcode-distance').textContent = Math.round(distance) + ' meter';

                    // Badge status
                    const badgeClass = status === 'diterima' ? 'bg-success' : 'bg-danger';
                    const badgeText = status === 'diterima' ? 'DITERIMA' : 'DITOLAK';
                    document.getElementById('barcode-status-badge').innerHTML =
                        `<span class="badge ${badgeClass}">${badgeText}</span>`;

                    // Result card
                    const resultCard = document.getElementById('result-status');
                    resultCard.style.display = 'block';
                    resultCard.className = 'result-card ' + status;
                    document.getElementById('result-status-text').textContent =
                        status === 'diterima' ? '✓ LOKASI DITERIMA' : '✗ LOKASI DITOLAK';
                    document.getElementById('result-distance').textContent =
                        `Jarak: ${Math.round(distance)} meter (Batas: ${accuracyTarget} meter)`;

                    // Show submit button
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check"></i> Lokasi Terdeteksi!';
                    document.getElementById('btn-simpan').style.display = 'inline-block';
                },
                function(error) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-satellite-dish"></i> Ambil Lokasi Saat Ini';

                    let errorMsg = 'Tidak dapat mendapatkan lokasi: ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg += 'Izin ditolak';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg += 'Lokasi tidak tersedia';
                            break;
                        case error.TIMEOUT:
                            errorMsg += 'Waktu habis';
                            break;
                        default:
                            errorMsg += error.message;
                    }
                    alert(errorMsg);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-satellite-dish"></i> Ambil Lokasi Saat Ini';
            alert('Geolocation tidak didukung browser ini.');
        }
    });

    // Haversine formula untuk menghitung jarak
    function calculateDistance(lat1, lng1, lat2, lng2) {
        const R = 6371000; // Radius bumi dalam meter
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

        // Ambil status dari result
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
        document.getElementById('btn-ambil-lokasi').innerHTML = '<i class="fas fa-satellite-dish"></i> Ambil Lokasi Saat Ini';
        stopScanner();
    }
</script>
@endpush