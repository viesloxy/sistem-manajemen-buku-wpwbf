{{-- resources/views/geolocation/barcode/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Daftar Barcode')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Master Barcode</h5>
                <a href="{{ route('geolocation.barcode.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Barcode
                </a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Nama Toko</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Accuracy</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barcodes as $index => $barcode)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $barcode->barcode }}</span>
                                    </td>
                                    <td>{{ $barcode->nama_toko }}</td>
                                    <td>{{ $barcode->latitude }}</td>
                                    <td>{{ $barcode->longitude }}</td>
                                    <td>{{ $barcode->accuracy ? $barcode->accuracy . ' m' : '-' }}</td>
                                    <td>{{ $barcode->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('geolocation.barcode.edit', $barcode->id) }}"
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('geolocation.barcode.print', $barcode->id) }}"
                                           class="btn btn-info btn-sm" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <form action="{{ route('geolocation.barcode.destroy', $barcode->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin hapus barcode ini?')">
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
                                    <td colspan="8" class="text-center">Belum ada data barcode.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $barcodes->links() }}
            </div>
        </div>
    </div>
@endsection
