{{-- resources/views/vendor/geolocation/titik-awal.blade.php --}}

@extends('layouts.app')

@section('title', 'Input Titik Awal')

@push('styles')
<style>
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .info-box h5 {
        margin-bottom: 5px;
    }
    .method-card {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .method-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }
    .method-card.active {
        border-color: #667eea;
        background-color: #f8f9ff;
    }
    .method-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    .method-number {
        width: 35px;
        height: 35px;
        background: #667eea;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 12px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info Box --}}
    <div class="info-box">
        <h5><i class="fas fa-info-circle"></i> Petunjuk Input Titik Awal</h5>
        <p class="mb-0">Tentukan lokasi awal toko/kantor Anda. Ada 2 cara yang bisa digunakan:</p>
    </div>

    {{-- Form Titik Awal --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Input Titik Awal</h5>
        </div>
        <div class="card-body">
            <form id="formTitikAwal">
                @csrf

                {{-- Cara 1: Manual Google Maps --}}
                <div class="method-card active" id="method-manual">
                    <div class="method-header">
                        <div class="method-number">1</div>
                        <div>
                            <h6 class="mb-0">Cara Manual - Google Maps</h6>
                            <small class="text-muted">Klik kanan pada lokasi di Google Maps untuk mendapatkan koordinat</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Latitude <span class="text-danger">*</span></label>
                                <input type="text" id="latitude" name="latitude"
                                       class="form-control" placeholder="-6.2087634" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Longitude <span class="text-danger">*</span></label>
                                <input type="text" id="longitude" name="longitude"
                                       class="form-control" placeholder="106.845599" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Accuracy (meter)</label>
                                <input type="text" id="accuracy" name="accuracy"
                                       class="form-control" placeholder="10" readonly>
                                <small class="text-muted">Otomatis terisi dari GPS</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <a href="https://www.google.com/maps" target="_blank"
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt"></i> Buka Google Maps
                        </a>
                        <small class="text-muted ms-2">
                            Klik kanan pada lokasi → Koordinat akan muncul di atas
                        </small>
                    </div>
                </div>

                {{-- Cara 2: GPS Otomatis --}}
                <div class="method-card" id="method-gps">
                    <div class="method-header">
                        <div class="method-number">2</div>
                        <div>
                            <h6 class="mb-0">Cara Otomatis - GPS Browser</h6>
                            <small class="text-muted">Aktifkan GPS di perangkat Anda</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="button" id="btn-geoloc" class="btn btn-success btn-lg">
                            <i class="fas fa-satellite-dish"></i> Ambil Lokasi Saat Ini (Geoloc)
                        </button>
                        <span id="gps-status" class="ms-3 text-muted"></span>
                    </div>
                </div>

                {{-- Info Lokasi --}}
                <div class="alert alert-info" id="location-info" style="display: none;">
                    <i class="fas fa-check-circle"></i>
                    <strong>Lokasi Terdeteksi!</strong>
                    <p class="mb-0" id="location-details"></p>
                </div>

                {{-- Submit Button --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Submit Titik Awal
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg ms-2" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Guide Card --}}
    <div class="card mt-3">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-book"></i> Panduan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-map-marked-alt text-primary"></i> Cara 1: Google Maps</h6>
                    <ol class="small">
                        <li>Buka <a href="https://www.google.com/maps" target="_blank">Google Maps</a></li>
                        <li>Cari lokasi toko/kantor Anda</li>
                        <li>Klik kanan pada titik lokasi</li>
                        <li>Angka pertama adalah <strong>Latitude</strong></li>
                        <li>Angka kedua adalah <strong>Longitude</strong></li>
                        <li>Copy kedua angka ke form di atas</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-satellite-dish text-success"></i> Cara 2: GPS Browser</h6>
                    <ol class="small">
                        <li>Pastikan GPS perangkat aktif</li>
                        <li>Klik tombol <strong>"Ambil Lokasi Saat Ini"</strong></li>
                        <li>Izinkan akses lokasi jika diminta</li>
                        <li>Koordinat akan terisi otomatis</li>
                        <li>Klik <strong>Submit Titik Awal</strong></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Ambil lokasi saat ini (GPS)
    document.getElementById('btn-geoloc').addEventListener('click', function() {
        const btn = this;
        const statusEl = document.getElementById('gps-status');
        const locationInfo = document.getElementById('location-info');
        const locationDetails = document.getElementById('location-details');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengambil lokasi...';
        statusEl.textContent = 'Mencari sinyal GPS...';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const acc = position.coords.accuracy;

                    // Isi form
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    document.getElementById('accuracy').value = acc;

                    // Update UI
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check"></i> Lokasi Terdeteksi!';
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-success', 'disabled');

                    statusEl.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Lokasi berhasil diambil</span>';

                    // Tampilkan info lokasi
                    locationInfo.style.display = 'block';
                    locationDetails.innerHTML = `
                        <strong>Latitude:</strong> ${lat}<br>
                        <strong>Longitude:</strong> ${lng}<br>
                        <strong>Accuracy:</strong> ±${acc} meter
                    `;

                    // Highlight method GPS
                    document.getElementById('method-gps').classList.add('active');
                    document.getElementById('method-manual').classList.remove('active');
                },
                function(error) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-satellite-dish"></i> Ambil Lokasi Saat Ini (Geoloc)';

                    let errorMsg = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Izin akses lokasi ditolak. Silakan aktifkan di pengaturan browser.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Lokasi tidak tersedia. Coba gunakan cara manual.';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Waktu habis. Coba lagi atau gunakan cara manual.';
                            break;
                        default:
                            errorMsg = 'Terjadi kesalahan: ' + error.message;
                    }

                    statusEl.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> ' + errorMsg + '</span>';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-satellite-dish"></i> Ambil Lokasi Saat Ini (Geoloc)';
            statusEl.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> Geolocation tidak didukung browser ini</span>';
        }
    });

    // Submit form
    document.getElementById('formTitikAwal').addEventListener('submit', function(e) {
        e.preventDefault();

        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;

        if (!lat || !lng) {
            alert('Silakan masukkan koordinat terlebih dahulu!');
            return;
        }

        const formData = new FormData(this);
        formData.append('type', 'titik_awal');

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
                alert('Titik awal berhasil disimpan!');
                resetForm();
            } else {
                alert('Gagal menyimpan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error.message);
        });
    });

    // Reset form
    function resetForm() {
        document.getElementById('formTitikAwal').reset();
        document.getElementById('location-info').style.display = 'none';
        document.getElementById('gps-status').textContent = '';
        document.getElementById('btn-geoloc').disabled = false;
        document.getElementById('btn-geoloc').innerHTML = '<i class="fas fa-satellite-dish"></i> Ambil Lokasi Saat Ini (Geoloc)';
        document.getElementById('method-gps').classList.remove('active');
        document.getElementById('method-manual').classList.add('active');
    }
</script>
@endpush
