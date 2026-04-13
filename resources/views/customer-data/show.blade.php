@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account"></i>
        </span> Detail Customer
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer-data.index') }}">Customer</a></li>
            <li class="breadcrumb-item active">{{ $customer->nama }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($customer->foto_blob || $customer->foto_path)
                    <img src="{{ $customer->foto_url }}"
                         alt="Foto {{ $customer->nama }}"
                         class="img-fluid rounded"
                         style="max-height: 300px; border: 3px solid #ddd;">
                @else
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                         style="width: 100%; height: 300px;">
                        <i class="mdi mdi-account text-white" style="font-size: 80px;"></i>
                    </div>
                @endif

                <hr>
                <p class="text-muted mb-1" style="font-size: 12px;">
                    <i class="mdi mdi-information"></i>
                    @if($customer->foto_blob)
                        Tipe: Blob (Base64)
                    @elseif($customer->foto_path)
                        Tipe: File Path
                    @else
                        Tanpa Foto
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{ $customer->nama }}</h4>

                <table class="table table-borderless">
                    <tr>
                        <td width="150" class="text-muted">ID Customer</td>
                        <td>: <strong>#{{ $customer->id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. HP</td>
                        <td>: {{ $customer->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>: {{ $customer->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat</td>
                        <td>: {{ $customer->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Daftar</td>
                        <td>: {{ $customer->created_at->format('d F Y, H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Terakhir Update</td>
                        <td>: {{ $customer->updated_at->format('d F Y, H:i') }} WIB</td>
                    </tr>
                </table>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('customer-data.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                    </a>
                    <form action="{{ route('customer-data.destroy', $customer->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Yakin hapus customer ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="mdi mdi-delete"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
