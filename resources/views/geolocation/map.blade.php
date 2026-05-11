{{-- resources/views/geolocation/map.blade.php --}}

@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 450px; width: 100%; border-radius: 10px; }
        .map-legend span { margin-right: 15px; }
    </style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map"></i>
        </span> Peta Lokasi Sales
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Peta interaktif lokasi vendor <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

{{-- Filter Card --}}
<div class="row mb-3">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="small text-muted">Filter Vendor</label>
                        <select id="filter-vendor" class="form-select form-select-sm">
                            <option value="">Semua Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Filter Status</label>
                        <select id="filter-status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="titik_awal">Titik Awal</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">&nbsp;</label>
                        <button type="button" id="btn-filter" class="btn btn-gradient-primary btn-sm w-100">
                            <i class="mdi mdi-filter btn-icon-prepend"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-3 text-end">
                        <div class="map-legend">
                            <span class="me-2"><i class="mdi mdi-map-marker text-primary"></i> Titik Awal</span>
                            <span class="me-2"><i class="mdi mdi-map-marker text-success"></i> Diterima</span>
                            <span class="me-2"><i class="mdi mdi-map-marker text-danger"></i> Ditolak</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Loading & Info --}}
<div id="map-info" class="alert alert-info mb-3" style="display: none;">
    <i class="mdi mdi-information"></i> <span id="info-text"></span>
</div>
<div id="map-loading" class="text-center py-3" style="display: none;">
    <div class="spinner-border text-primary" role="status"></div>
    <span class="ms-2">Memuat marker...</span>
</div>

{{-- Map Card --}}
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Peta Interaktif</h4>
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
$(document).ready(function() {
    console.log('=== Map page loaded, initializing... ===');

    const map = L.map('map').setView([-2.5489, 118.0149], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    console.log('Map initialized successfully');

    const icons = {
        titik_awal: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            shadowSize: [41, 41]
        }),
        diterima: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            shadowSize: [41, 41]
        }),
        ditolak: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            shadowSize: [41, 41]
        })
    };

    let markers = [];

    function loadLocations(vendorId, status) {
        vendorId = vendorId || '';
        status = status || '';

        console.log('=== loadLocations called ===');
        console.log('vendorId:', vendorId);
        console.log('status:', status);

        document.getElementById('map-loading').style.display = 'block';
        document.getElementById('map-info').style.display = 'none';

        markers.forEach(function(m) {
            map.removeLayer(m);
        });
        markers = [];
        console.log('Old markers cleared');

        let url = '/api/geolocation-by-vendor';
        let params = [];
        if (vendorId) {
            params.push('vendor_id=' + vendorId);
        }
        if (status) {
            params.push('status=' + status);
        }
        if (params.length) {
            url += '?' + params.join('&');
        }

        console.log('Fetching from URL:', url);

        fetch(url)
            .then(function(response) {
                console.log('Response status:', response.status);
                console.log('Response OK:', response.ok);
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(function(data) {
                console.log('=== Data received ===');
                console.log('Data:', data);
                console.log('Data type:', typeof data);
                console.log('Data length:', data.length);

                document.getElementById('map-loading').style.display = 'none';

                if (!data || data.length === 0) {
                    console.log('No data found');
                    document.getElementById('map-info').style.display = 'block';
                    document.getElementById('map-info').className = 'alert alert-warning mb-3';
                    document.getElementById('info-text').textContent = 'Belum ada data lokasi. Silakan pastikan vendor sudah input titik awal atau kunjungan.';
                    return;
                }

                console.log('Processing', data.length, 'locations...');

                data.forEach(function(location, index) {
                    console.log('Location', index, ':', location);

                    let iconKey = 'ditolak';
                    if (location.type === 'titik_awal') {
                        iconKey = 'titik_awal';
                    } else if (location.status === 'diterima') {
                        iconKey = 'diterima';
                    }

                    console.log('Icon key:', iconKey);

                    try {
                        const marker = L.marker([location.latitude, location.longitude], {
                            icon: icons[iconKey]
                        }).addTo(map);

                        let userName = 'Unknown';
                        if (location.user && location.user.name) {
                            userName = location.user.name;
                        }

                        let popupContent = '<strong>' + userName + '</strong><br>';
                        popupContent += '<small>Type: ' + location.type + '</small><br>';
                        popupContent += '<small>Status: ' + (location.status || '-') + '</small><br>';
                        if (location.barcode) {
                            popupContent += '<small>Barcode: ' + location.barcode + '</small><br>';
                        }
                        popupContent += '<small>Acc: ' + (location.accuracy || 'N/A') + ' meter</small><br>';
                        popupContent += '<small>' + location.created_at + '</small>';

                        marker.bindPopup(popupContent);

                        markers.push(marker);
                        console.log('Marker', index, 'added successfully');
                    } catch (e) {
                        console.error('Error adding marker', index, ':', e);
                    }
                });

                console.log('Total markers added:', markers.length);

                if (data.length > 0) {
                    try {
                        const bounds = L.latLngBounds(data.map(function(loc) {
                            return [loc.latitude, loc.longitude];
                        }));
                        map.fitBounds(bounds, { padding: [50, 50] });
                        console.log('Map bounds adjusted');
                    } catch (e) {
                        console.error('Error adjusting bounds:', e);
                    }
                }

                document.getElementById('map-info').style.display = 'block';
                document.getElementById('map-info').className = 'alert alert-success mb-3';
                document.getElementById('info-text').textContent = 'Menampilkan ' + data.length + ' marker lokasi';
                console.log('=== Load complete ===');
            })
            .catch(function(error) {
                console.error('=== Error loading locations ===');
                console.error('Error:', error);
                console.error('Error message:', error.message);
                document.getElementById('map-loading').style.display = 'none';
                document.getElementById('map-info').style.display = 'block';
                document.getElementById('map-info').className = 'alert alert-danger mb-3';
                document.getElementById('info-text').textContent = 'Gagal memuat data: ' + error.message;
            });
    }

    console.log('Loading initial locations...');
    loadLocations('', '');

    document.getElementById('btn-filter').addEventListener('click', function() {
        console.log('=== Filter button clicked ===');
        const vendorId = document.getElementById('filter-vendor').value;
        const status = document.getElementById('filter-status').value;
        console.log('Selected vendorId:', vendorId);
        console.log('Selected status:', status);
        loadLocations(vendorId, status);
    });

    console.log('=== Event listeners attached ===');
});
</script>
@endsection
