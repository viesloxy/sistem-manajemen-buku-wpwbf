{{-- resources/views/geolocation/list.blade.php --}}

@extends('layouts.app')

@section('title', 'Daftar Lokasi')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Lokasi Sales</h5>
                <a href="{{ route('geolocation.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Lokasi
                </a>
            </div>
            <div class="card-body">
                {{-- Filter --}}
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <select name="vendor_id" class="form-select form-select-sm">
                            <option value="">Semua Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select form-select-sm">
                            <option value="">Semua Type</option>
                            <option value="titik_awal" {{ request('type') == 'titik_awal' ? 'selected' : '' }}>Titik Awal</option>
                            <option value="titik_kunjungan" {{ request('type') == 'titik_kunjungan' ? 'selected' : '' }}>Titik Kunjungan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                        <a href="{{ route('geolocation.list') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Vendor</th>
                                <th>Type</th>
                                <th>Barcode</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Accuracy</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($geolocations as $index => $location)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $location->user->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $location->type)) }}</span>
                                    </td>
                                    <td>{{ $location->barcode ?? '-' }}</td>
                                    <td>{{ $location->latitude }}</td>
                                    <td>{{ $location->longitude }}</td>
                                    <td>{{ $location->accuracy ? $location->accuracy . ' m' : '-' }}</td>
                                    <td>
                                        @if($location->status == 'diterima')
                                            <span class="badge bg-success">DITERIMA</span>
                                        @elseif($location->status == 'ditolak')
                                            <span class="badge bg-danger">DITOLAK</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $location->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}"
                                           target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </a>
                                        <form action="{{ route('geolocation.destroy', $location->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin hapus lokasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Belum ada data lokasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $geolocations->links() }}
            </div>
        </div>
    </div>
@endsection