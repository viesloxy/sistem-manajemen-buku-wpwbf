{{-- resources/views/vendor/geolocation/titik-awal.blade.php --}}

@extends('layouts.app')

@push('styles')
<style>
    .method-card {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
        background: #fff;
    }
    .method-card:hover {
        border-color: #6610f2;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
    }
    .method-card.active {
        border-color: #6610f2;
        background-color: #f8f7ff;
    }
    .method-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    .method-number {
        width: 35px;
        height: 35px;
        background: #6610f2;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 12px;
    }
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker-plus"></i>
        </span> Input Titik Awal
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Tentukan lokasi awal toko <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
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

{{-- Info Box --}}
<div class="alert alert-info d-flex align-items-center mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
    <i class="mdi mdi-information fs-4 me-3"></i>
    <div>
        <strong>Petunjuk Input Titik Awal</strong>
        <p class="mb-0 mt-1">Tentukan lokasi awal toko/kantor Anda. Ada 2 cara yang bisa digunakan.</p>
    </div>
</div>

{{-- Main Form Card --}}
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Input Titik Awal</h4>
                <hr class="mb-4">

                <form id="formTitikAwal">
                    @csrf

                    {{-- Cara 1: Manual Google Maps --}}
                    <div class="method-card active mb-4" id="method-manual">
                        <div class="method-header">
                            <div class="method-number">1</div>
                            <div>
                                <h6 class="mb-0 text-primary">Cara Manual - Google Maps</h6>
                                <small class="text-muted">Klik kanan pada lokasi di Google Maps untuk mendapatkan koordinat</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="latitude">Latitude <span class="text-danger">*</span></label>
                                    <input type="text" id="latitude" name="latitude"
                                           class="form-control border-primary" placeholder="-6.2087634" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="longitude">Longitude <span class="text-danger">*</span></label>
                                    <input type="text" id="longitude" name="longitude"
                                           class="form-control border-primary" placeholder="106.845599" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="accuracy">Accuracy (meter)</label>
                                    <input type="text" id="accuracy" name="accuracy"
                                           class="form-control border-primary" placeholder="Auto dari GPS" readonly>
                                    <small class="text-muted">Otomatis terisi dari GPS</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="https://www.google.com/maps" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-open-in-new btn-icon-prepend"></i> Buka Google Maps
                            </a>
                            <small class="text-muted ms-2">Klik kanan pada lokasi → Koordinat akan muncul di atas</small>
                        </div>
                    </div>

                    {{-- Cara 2: GPS Otomatis --}}
                    <div class="method-card mb-4" id="method-gps">
                        <div class="method-header">
                            <div class="method-number">2</div>
                            <div>
                                <h6 class="mb-0 text-success">Cara Otomatis - GPS Browser</h6>
                                <small class="text-muted">Aktifkan GPS di perangkat Anda</small>
                            </div>
                        </div>
                        <button type="button" id="btn-geoloc" class="btn btn-gradient-success btn-lg">
                            <i class="mdi mdi-satellite-variant btn-icon-prepend"></i> Ambil Lokasi Saat Ini (Geoloc)
                        </button>
                        <span id="gps-status" class="ms-3"></span>
                    </div>

                    {{-- Info Lokasi --}}
                    <div class="alert alert-success" id="location-info" style="display: none;">
                        <i class="mdi mdi-check-circle"></i> <strong>Lokasi Terdeteksi!</strong>
                        <p class="mb-0" id="location-details"></p>
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary btn-lg me-2">
                            <i class="mdi mdi-send btn-icon-prepend"></i> Submit Titik Awal
                        </button>
                        <button type="button" id="btn-reset" class="btn btn-outline-secondary btn-lg">
                            <i class="mdi mdi-refresh btn-icon-prepend"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Guide Card --}}
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="mdi mdi-book-open-variant text-primary"></i> Panduan</h4>
                <hr>
                <h6 class="text-primary"><i class="mdi mdi-map-marked-alt"></i> Cara 1: Google Maps</h6>
                <ol class="small mb-4">
                    <li>Buka <a href="https://www.google.com/maps" target="_blank">Google Maps</a></li>
                    <li>Kari lokasi toko/kantor Anda</li>
                    <li>Klik kanan pada titik lokasi</li>
                    <li>Angka pertama = <strong>Latitude</strong></li>
                    <li>Angka kedua = <strong>Longitude</strong></li>
                </ol>
                <hr>
                <h6 class="text-success"><i class="mdi mdi-satellite-variant"></i> Cara 2: GPS Browser</h6>
                <ol class="small">
                    <li>Pastikan GPS perangkat aktif</li>
                    <li>Klik tombol <strong>"Ambil Lokasi Saat Ini"</strong></li>
                    <li>Izinkan akses lokasi jika diminta</li>
                    <li>Koordinat akan terisi otomatis</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script>
$(document).ready(function() {
    // Ambil lokasi saat ini (GPS)
    document.getElementById('btn-geoloc').addEventListener('click', function() {
        const btn = this;
        const statusEl = document.getElementById('gps-status');
        const locationInfo = document.getElementById('location-info');
        const locationDetails = document.getElementById('location-details');

        btn.disabled = true;
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin btn-icon-prepend"></i> Mengambil lokasi...';
        statusEl.textContent = 'Mencari sinyal GPS...';
        statusEl.className = 'text-muted';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const acc = position.coords.accuracy;

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    document.getElementById('accuracy').value = acc;

                    btn.disabled = false;
                    btn.innerHTML = '<i class="mdi mdi-check btn-icon-prepend"></i> Lokasi Terdeteksi!';
                    btn.classList.remove('btn-gradient-success');
                    btn.classList.add('btn-gradient-success');

                    statusEl.innerHTML = '<i class="mdi mdi-check-circle text-success"></i>';
                    statusEl.className = 'text-success';

                    locationInfo.style.display = 'block';
                    locationDetails.innerHTML = `
                        <strong>Latitude:</strong> ${lat}<br>
                        <strong>Longitude:</strong> ${lng}<br>
                        <strong>Accuracy:</strong> ±${acc} meter
                    `;

                    document.getElementById('method-gps').classList.add('active');
                    document.getElementById('method-manual').classList.remove('active');
                },
                function(error) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="mdi mdi-satellite-variant btn-icon-prepend"></i> Ambil Lokasi Saat Ini (Geoloc)';

                    let errorMsg = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Izin akses lokasi ditolak.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Waktu habis.';
                            break;
                        default:
                            errorMsg = error.message;
                    }

                    statusEl.innerHTML = '<i class="mdi mdi-close-circle text-danger"></i> ' + errorMsg;
                    statusEl.className = 'text-danger';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="mdi mdi-satellite-variant btn-icon-prepend"></i> Ambil Lokasi Saat Ini (Geoloc)';
            statusEl.innerHTML = '<i class="mdi mdi-close-circle text-danger"></i> Geolocation tidak didukung.';
            statusEl.className = 'text-danger';
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

        console.log('Submitting data...');
        console.log('Lat:', lat, 'Lng:', lng);

        fetch('{{ route('vendor.geolocation.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.text().then(text => {
                console.log('Response text:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid JSON: ' + text);
                }
            });
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert('Titik awal berhasil disimpan!');
                resetForm();
            } else {
                alert('Gagal menyimpan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    });

    // Reset form
    function resetForm() {
        document.getElementById('formTitikAwal').reset();
        document.getElementById('location-info').style.display = 'none';
        document.getElementById('gps-status').textContent = '';
        document.getElementById('btn-geoloc').disabled = false;
        document.getElementById('btn-geoloc').innerHTML = '<i class="mdi mdi-satellite-variant btn-icon-prepend"></i> Ambil Lokasi Saat Ini (Geoloc)';
        document.getElementById('method-gps').classList.remove('active');
        document.getElementById('method-manual').classList.add('active');
    }

    // Reset button event listener
    document.getElementById('btn-reset').addEventListener('click', function() {
        resetForm();
    });
});
</script>
@endsection
