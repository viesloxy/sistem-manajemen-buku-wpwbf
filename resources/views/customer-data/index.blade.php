@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account-multiple"></i>
        </span> Data Customer
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Customer</li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Daftar Customer</h4>
                    <div>
                        <a href="{{ route('customer-data.create-blob') }}" class="btn btn-gradient-primary btn-sm me-2">
                            <i class="mdi mdi-camera btn-icon-prepend"></i> Tambah (Blob)
                        </a>
                        <a href="{{ route('customer-data.create-file') }}" class="btn btn-gradient-info btn-sm">
                            <i class="mdi mdi-camera btn-icon-prepend"></i> Tambah (File)
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($customer->foto_blob || $customer->foto_path)
                                        <img src="{{ $customer->foto_url }}"
                                             alt="Foto {{ $customer->nama }}"
                                             class="rounded-circle"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="mdi mdi-account text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $customer->nama }}</td>
                                <td>{{ $customer->no_hp ?? '-' }}</td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>{{ Str::limit($customer->alamat, 30) ?? '-' }}</td>
                                <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('customer-data.show', $customer->id) }}"
                                       class="btn btn-outline-info btn-sm me-1" title="Detail">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <form action="{{ route('customer-data.destroy', $customer->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus customer ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="mdi mdi-account-off display-4 d-block mb-2"></i>
                                        <p class="mb-0">Belum ada data customer</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
