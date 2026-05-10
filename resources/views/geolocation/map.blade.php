{{-- resources/views/geolocation/map.blade.php --}}

@extends('layouts.app')

@section('title', 'Peta Lokasi')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 500px; width: 100%; }
        .legend { line-height: 1.5; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Peta Lokasi Sales</h5>
                <div>
                    <select id="filter-vendor" class="form-select form-select-sm d-inline-block w-auto me-2">
                        <option value="">Semua Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    <select id="filter-status" class="form-select form-select-sm d-inline-block w-auto">
                        <option value="">Semua Status</option>
                        <option value="titik_awal">Titik Awal</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div id="map"></div>

                {{-- Legenda --}}
                <div class="mt-3">
                    <span class="me-3"><i class="fas fa-map-marker-alt text-primary"></i> Titik Awal</span>
                    <span class="me-3"><i class="fas fa-map-marker-alt text-success"></i> Diterima</span>
                    <span class="me-3"><i class="fas fa-map-marker-alt text-danger"></i> Ditolak</span>
                    <button id="btn-print-map" class="btn btn-sm btn-info float-end">
                        <i class="fas fa-print"></i> Cetak Peta
                    </button>
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
            // Hapus marker lama
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            let url = '/api/geolocation';
            let params = [];
            if (vendorId) params.push('vendor_id=' + vendorId);
            if (status) params.push('status=' + status);
            if (params.length) url += '?' + params.join('&');

            fetch(url)
                .then(response => response.json())
                .then(data => {
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

                    if (data.length > 0) {
                        const bounds = L.latLngBounds(data.map(loc => [loc.latitude, loc.longitude]));
                        map.fitBounds(bounds, { padding: [50, 50] });
                    }
                })
                .catch(error => console.error('Error loading locations:', error));
        }

        // Load semua lokasi saat halaman dimuat
        loadLocations();

        // Filter vendor
        document.getElementById('filter-vendor').addEventListener('change', function() {
            loadLocations(this.value, document.getElementById('filter-status').value);
        });

        // Filter status
        document.getElementById('filter-status').addEventListener('change', function() {
            loadLocations(document.getElementById('filter-vendor').value, this.value);
        });

        // Cetak peta
        document.getElementById('btn-print-map').addEventListener('click', function() {
            window.print();
        });
    </script>
@endpush
