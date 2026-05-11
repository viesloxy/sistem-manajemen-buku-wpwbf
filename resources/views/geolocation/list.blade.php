{{-- resources/views/geolocation/list.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-format-list-bulleted"></i>
        </span> Daftar Lokasi Sales
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('geolocation.create') }}" class="btn btn-gradient-primary btn-sm">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Lokasi
                </a>
            </li>
        </ul>
    </nav>
</div>

{{-- Success Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Filter Card --}}
<div class="row mb-3">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Filter Lokasi</h4>
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="small text-muted">Vendor</label>
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
                        <label class="small text-muted">Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">Semua Type</option>
                            <option value="titik_awal" {{ request('type') == 'titik_awal' ? 'selected' : '' }}>Titik Awal</option>
                            <option value="titik_kunjungan" {{ request('type') == 'titik_kunjungan' ? 'selected' : '' }}>Titik Kunjungan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-gradient-secondary btn-sm me-2">
                            <i class="mdi mdi-magnify btn-icon-prepend"></i> Filter
                        </button>
                        <a href="{{ route('geolocation.list') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Lokasi</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-light text-center">
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
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($geolocations as $index => $location)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $location->user->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $location->type)) }}</span>
                                    </td>
                                    <td>
                                        @if($location->barcode)
                                            <span class="badge badge-outline-dark">{{ $location->barcode }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $location->latitude }}</td>
                                    <td>{{ $location->longitude }}</td>
                                    <td>{{ $location->accuracy ? $location->accuracy . ' m' : '-' }}</td>
                                    <td class="text-center">
                                        @if($location->status == 'diterima')
                                            <span class="badge badge-success">DITERIMA</span>
                                        @elseif($location->status == 'ditolak')
                                            <span class="badge badge-danger">DITOLAK</span>
                                        @else
                                            <span class="badge badge-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $location->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <a href="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}"
                                           target="_blank" class="btn btn-gradient-info btn-sm btn-icon-text me-1" title="Lihat di Maps">
                                            <i class="mdi mdi-google-maps btn-icon-prepend"></i> Maps
                                        </a>
                                        <form action="{{ route('geolocation.destroy', $location->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin hapus lokasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-gradient-danger btn-sm btn-icon-text" title="Hapus">
                                                <i class="mdi mdi-delete btn-icon-prepend"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="mdi mdi-map-marker-off" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Belum ada data lokasi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $geolocations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection