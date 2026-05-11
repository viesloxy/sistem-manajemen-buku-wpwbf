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

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-2.5489, 118.0149], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Marker icons
        const icons = {
            titik_awal: L.icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/markers/marker-icon-blue.png', iconSize: [25, 41], shadowUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/markers/marker-shadow.png', iconSize: [25, 41], shadowSize: [41, 41] }),
            diterima: L.icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/markers/marker-icon-green.png', iconSize: [25, 41], shadowUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/markers/marker-shadow.png', iconSize: [25, 41], shadowSize: [41, 41] }),
            ditolak: L.icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/markers/marker-icon-red.png', iconSize: [25, 41], shadowUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/markers/marker-shadow.png', iconSize: [25, 41], shadowSize: [41, 41] })
        };

        let markers = [];

        function loadLocations(vendorId = '', status = '') {
            // Tampilkan loading
            document.getElementById('map-loading').style.display = 'block';
            document.getElementById('map-info').style.display = 'none';

            // Hapus marker lama
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            // Gunakan endpoint yang mendukung filter
            let url = '/api/geolocation-by-vendor';
            let params = [];
            if (vendorId) params.push('vendor_id=' + vendorId);
            if (status) params.push('status=' + status);
            if (params.length) url += '?' + params.join('&');

            console.log('Loading locations from:', url);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Locations loaded:', data);
                    document.getElementById('map-loading').style.display = 'none';

                    if (data.length === 0) {
                        document.getElementById('map-info').style.display = 'block';
                        document.getElementById('info-text').textContent =
                            'Belum ada data lokasi. Silakan pastikan vendor sudah input titik awal atau kunjungan.';
                        return;
                    }

                    data.forEach(location => {
                        let iconKey = location.type === 'titik_awal' ? 'titik_awal' :
                                      location.status === 'diterima' ? 'diterima' : 'ditolak';

                        const marker = L.marker([location.latitude, location.longitude], { icon: icons[iconKey] })
                            .addTo(map);

                        marker.bindPopup(`
                            <strong>${location.user?.name || 'Unknown'}</strong><br>
                            <small>Type: ${location.type}</small><br>
                            <small>Status: ${location.status || '-'}</small><br>
                            ${location.barcode ? '<small>Barcode: ' + location.barcode + '</small><br>' : ''}
                            <small>Acc: ${location.accuracy} meter</small><br>
                            <small>${location.created_at}</small>
                        `);

                        markers.push(marker);
                    });

                    // Zoom ke semua marker
                    if (data.length > 0) {
                        const bounds = L.latLngBounds(data.map(loc => [loc.latitude, loc.longitude]));
                        map.fitBounds(bounds, { padding: [50, 50] });
                    }

                    // Tampilkan info
                    document.getElementById('map-info').style.display = 'block';
                    document.getElementById('info-text').textContent =
                        'Menampilkan ' + data.length + ' marker lokasi';
                })
                .catch(error => {
                    console.error('Error loading locations:', error);
                    document.getElementById('map-loading').style.display = 'none';
                    document.getElementById('map-info').style.display = 'block';
                    document.getElementById('info-text').textContent =
                        'Gagal memuat data. Silakan refresh halaman.';
                });
        }

        // Load semua lokasi saat halaman dimuat
        loadLocations();

        // Filter button click
        document.getElementById('btn-filter').addEventListener('click', function() {
            const vendorId = document.getElementById('filter-vendor').value;
            const status = document.getElementById('filter-status').value;
            loadLocations(vendorId, status);
        });
    </script>
@endpush
