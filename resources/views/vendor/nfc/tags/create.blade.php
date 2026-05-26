{{-- resources/views/vendor/nfc/tags/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-warning text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span> Tambah NFC Saya
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('vendor.nfc.scan') }}" class="btn btn-gradient-info btn-icon-text btn-sm">
                    <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Kembali
                </a>
            </li>
        </ul>
    </nav>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="mdi mdi-alert-circle"></i> Terjadi Kesalahan!</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah NFC Vendor</h4>
                <hr class="mb-4">

                <form action="{{ route('vendor.nfc.tags.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="serial_number">Serial Number <span class="text-danger">*</span></label>
                        <input type="text" id="serial_number" name="serial_number"
                               class="form-control border-primary @error('serial_number') is-invalid @enderror"
                               value="{{ old('serial_number') }}"
                               placeholder="Contoh: NFC-V-001-ABCD1234" required>
                        @error('serial_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="nama_pemilik">Nama Pemilik <span class="text-danger">*</span></label>
                        <input type="text" id="nama_pemilik" name="nama_pemilik"
                               class="form-control border-primary @error('nama_pemilik') is-invalid @enderror"
                               value="{{ old('nama_pemilik', Auth::user()->name) }}" required>
                        @error('nama_pemilik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="tipe" value="vendor">
                    <input type="hidden" name="status" value="aktif">

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-warning btn-lg me-2">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i> Simpan
                        </button>
                        <a href="{{ route('vendor.nfc.scan') }}" class="btn btn-outline-secondary btn-lg">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="mdi mdi-information-outline text-warning"></i> Petunjuk</h4>
                <hr>
                <p class="small text-muted">
                    Isi serial number NFC Anda. Serial number biasanya ada di belakang kartu NFC.
                </p>
                <div class="alert alert-warning small mb-0">
                    <i class="mdi mdi-lightbulb-outline"></i> <strong>Tips:</strong><br>
                    Gunakan aplikasi NFC scanner<br>
                    di smartphone untuk membaca<br>
                    serial number kartu Anda.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection