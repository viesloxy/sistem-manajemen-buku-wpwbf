{{-- resources/views/geolocation/barcode/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-barcode"></i>
        </span> Edit Barcode
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('geolocation.barcode.index') }}" class="btn btn-gradient-info btn-icon-text btn-sm">
                    <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Kembali
                </a>
            </li>
        </ul>
    </nav>
</div>

{{-- Error Alert --}}
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

{{-- Form Card --}}
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Barcode</h4>
                <hr class="mb-4">

                <form action="{{ route('geolocation.barcode.update', $barcode->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barcode">Kode Barcode <span class="text-danger">*</span></label>
                                <input type="text" id="barcode" name="barcode"
                                       class="form-control border-primary @error('barcode') is-invalid @enderror"
                                       value="{{ old('barcode', $barcode->barcode) }}"
                                       placeholder="Contoh: TOKO-001" required>
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_toko">Nama Toko <span class="text-danger">*</span></label>
                                <input type="text" id="nama_toko" name="nama_toko"
                                       class="form-control border-primary @error('nama_toko') is-invalid @enderror"
                                       value="{{ old('nama_toko', $barcode->nama_toko) }}"
                                       placeholder="Contoh: Toko Maju Jaya" required>
                                @error('nama_toko')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latitude">Latitude <span class="text-danger">*</span></label>
                                <input type="text" id="latitude" name="latitude"
                                       class="form-control border-primary @error('latitude') is-invalid @enderror"
                                       value="{{ old('latitude', $barcode->latitude) }}"
                                       placeholder="Contoh: -6.2087634" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="longitude">Longitude <span class="text-danger">*</span></label>
                                <input type="text" id="longitude" name="longitude"
                                       class="form-control border-primary @error('longitude') is-invalid @enderror"
                                       value="{{ old('longitude', $barcode->longitude) }}"
                                       placeholder="Contoh: 106.845599" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="accuracy">Accuracy (meter)</label>
                                <input type="number" id="accuracy" name="accuracy"
                                       class="form-control border-primary @error('accuracy') is-invalid @enderror"
                                       value="{{ old('accuracy', $barcode->accuracy) }}"
                                       placeholder="Contoh: 50" min="1">
                                @error('accuracy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary btn-lg me-2">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i> Perbarui
                        </button>
                        <a href="{{ route('geolocation.barcode.index') }}" class="btn btn-outline-secondary btn-lg">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="mdi mdi-information-outline text-info"></i> Info</h4>
                <hr>
                <div class="alert alert-warning small">
                    <i class="mdi mdi-alert"></i> <strong>Perhatian!</strong><br>
                    Pastikan kode barcode unik dan tidak<br>
                    digunakan oleh toko lain.
                </div>
                <hr>
                <h6 class="text-primary">Data Saat Ini:</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Barcode</td>
                        <td class="fw-bold">{{ $barcode->barcode }}</td>
                    </tr>
                    <tr>
                        <td>Toko</td>
                        <td class="fw-bold">{{ $barcode->nama_toko }}</td>
                    </tr>
                    <tr>
                        <td>Accuracy</td>
                        <td class="fw-bold">{{ $barcode->accuracy }} meter</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection