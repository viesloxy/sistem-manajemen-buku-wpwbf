{{-- resources/views/geolocation/barcode/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-barcode"></i>
        </span> Daftar Master Barcode
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('geolocation.barcode.create') }}" class="btn btn-gradient-primary btn-sm">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Barcode
                </a>
            </li>
        </ul>
    </nav>
</div>

{{-- Success/Error Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Table Card --}}
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Barcode Toko</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode Barcode</th>
                                <th>Nama Toko</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Accuracy</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barcodes as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $item->barcode }}</span>
                                    </td>
                                    <td class="fw-bold">{{ $item->nama_toko }}</td>
                                    <td>{{ $item->latitude }}</td>
                                    <td>{{ $item->longitude }}</td>
                                    <td>{{ $item->accuracy ? $item->accuracy . ' m' : '-' }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('geolocation.barcode.print', $item->id) }}"
                                           target="_blank" class="btn btn-gradient-info btn-sm btn-icon-text me-1" title="Cetak">
                                            <i class="mdi mdi-printer btn-icon-prepend"></i> Cetak
                                        </a>
                                        <a href="{{ route('geolocation.barcode.edit', $item->id) }}"
                                           class="btn btn-gradient-warning btn-sm btn-icon-text me-1" title="Edit">
                                            <i class="mdi mdi-pencil btn-icon-prepend"></i> Edit
                                        </a>
                                        <form action="{{ route('geolocation.barcode.destroy', $item->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin hapus barcode ini?')">
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
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="mdi mdi-barcode-off" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Belum ada data barcode.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $barcodes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection