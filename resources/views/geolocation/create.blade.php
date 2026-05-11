{{-- resources/views/geolocation/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker-plus"></i>
        </span> Tambah Lokasi Baru
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('geolocation.list') }}" class="btn btn-gradient-info btn-icon-text btn-sm">
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
                <h4 class="card-title">Form Tambah Lokasi</h4>
                <hr class="mb-4">

                <form action="{{ route('geolocation.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude">Latitude <span class="text-danger">*</span></label>
                                <input type="text" id="latitude" name="latitude"
                                       class="form-control border-primary @error('latitude') is-invalid @enderror"
                                       value="{{ old('latitude') }}"
                                       placeholder="Contoh: -6.2087634" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Klik kanan di Google Maps untuk mendapatkan koordinat</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitude">Longitude <span class="text-danger">*</span></label>
                                <input type="text" id="longitude" name="longitude"
                                       class="form-control border-primary @error('longitude') is-invalid @enderror"
                                       value="{{ old('longitude') }}"
                                       placeholder="Contoh: 106.845599" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" class="form-control border-primary @error('address') is-invalid @enderror"
                                  rows="4" placeholder="Masukkan alamat lengkap...">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary btn-lg me-2">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i> Simpan
                        </button>
                        <a href="{{ route('geolocation.list') }}" class="btn btn-outline-secondary btn-lg">
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
                <h4 class="card-title"><i class="mdi mdi-information-outline text-info"></i> Petunjuk</h4>
                <hr>
                <h6 class="text-primary">Cara Mendapatkan Koordinat:</h6>
                <ol class="small">
                    <li>Buka <a href="https://www.google.com/maps" target="_blank">Google Maps</a></li>
                    <li>Cari lokasi yang diinginkan</li>
                    <li>Klik kanan pada titik lokasi</li>
                    <li>Pilih angka pertama = <strong>Latitude</strong></li>
                    <li>Pilih angka kedua = <strong>Longitude</strong></li>
                </ol>
                <hr>
                <div class="alert alert-info small mb-0">
                    <i class="mdi mdi-lightbulb-outline"></i> <strong>Tips:</strong><br>
                    Koordinat untuk Indonesia umumnya:<br>
                    Latitude: <strong>-11.0</strong> sampai <strong>6.0</strong><br>
                    Longitude: <strong>95.0</strong> sampai <strong>141.0</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection