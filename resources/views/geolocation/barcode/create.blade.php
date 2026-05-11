{{-- resources/views/geolocation/barcode/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-barcode"></i>
        </span> Tambah Barcode Baru
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
                <h4 class="card-title">Form Tambah Barcode</h4>
                <hr class="mb-4">

                <form action="{{ route('geolocation.barcode.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barcode">Kode Barcode <span class="text-danger">*</span></label>
                                <input type="text" id="barcode" name="barcode"
                                       class="form-control border-primary @error('barcode') is-invalid @enderror"
                                       value="{{ old('barcode') }}"
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
                                       value="{{ old('nama_toko') }}"
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
                                       value="{{ old('latitude') }}"
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
                                       value="{{ old('longitude') }}"
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
                                       value="{{ old('accuracy', 50) }}"
                                       placeholder="Contoh: 50" min="1">
                                <small class="text-muted">Default: 50 meter</small>
                                @error('accuracy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary btn-lg me-2">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i> Simpan
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
                <h4 class="card-title"><i class="mdi mdi-information-outline text-info"></i> Petunjuk</h4>
                <hr>
                <h6 class="text-primary">Isi Data Barcode:</h6>
                <ol class="small">
                    <li><strong>Kode Barcode</strong> - ID unik untuk toko</li>
                    <li><strong>Nama Toko</strong> - Nama outlet/toko</li>
                    <li><strong>Latitude</strong> - Koordinat lintang</li>
                    <li><strong>Longitude</strong> - Koordinat bujur</li>
                    <li><strong>Accuracy</strong> - Radius toleransi (meter)</li>
                </ol>
                <hr>
                <div class="alert alert-info small mb-0">
                    <i class="mdi mdi-lightbulb-outline"></i> <strong>Tips:</strong><br>
                    Accuracy menentukan jarak maksimal<br>
                    yang diperbolehkan saat vendor<br>
                    melakukan visit. Default: 50 meter
                </div>
            </div>
        </div>
    </div>
</div>
@endsection