{{-- resources/views/nfc/tags/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span> Daftar Kartu NFC
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('nfc.tags.create') }}" class="btn btn-gradient-primary btn-sm">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah NFC
                </a>
            </li>
        </ul>
    </nav>
</div>

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
                <h4 class="card-title">Daftar Kartu NFC Terdaftar</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Serial Number</th>
                                <th>Nama Pemilik</th>
                                <th>Tipe</th>
                                <th>Vendor</th>
                                <th>Status</th>
                                <th>Daftar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tags as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $item->serial_number }}</span>
                                    </td>
                                    <td class="fw-bold">{{ $item->nama_pemilik }}</td>
                                    <td>
                                        <span class="badge @if($item->tipe == 'admin') badge-danger @elseif($item->tipe == 'vendor') badge-warning @else badge-info @endif">
                                            {{ ucfirst($item->tipe) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->vendor->nama_vendor ?? '-' }}</td>
                                    <td>
                                        @if($item->status == 'aktif')
                                            <span class="badge badge-success">{{ $item->status }}</span>
                                        @elseif($item->status == 'nonaktif')
                                            <span class="badge badge-secondary">{{ $item->status }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('nfc.tags.edit', $item->id) }}"
                                           class="btn btn-gradient-warning btn-sm btn-icon-text me-1" title="Edit">
                                            <i class="mdi mdi-pencil btn-icon-prepend"></i> Edit
                                        </a>
                                        <form action="{{ route('nfc.tags.destroy', $item->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin hapus kartu NFC ini?')">
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
                                        <i class="mdi mdi-nfc-off" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Belum ada data kartu NFC.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $tags->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
