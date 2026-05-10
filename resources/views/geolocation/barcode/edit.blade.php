{{-- resources/views/geolocation/barcode/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Barcode')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Barcode</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('geolocation.barcode.update', $barcode->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Barcode <span class="text-danger">*</span></label>
                                <input type="text" name="barcode"
                                       class="form-control @error('barcode') is-invalid @enderror"
                                       value="{{ old('barcode', $barcode->barcode) }}"
                                       placeholder="Contoh: TOKO-001" required>
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Toko <span class="text-danger">*</span></label>
                                <input type="text" name="nama_toko"
                                       class="form-control @error('nama_toko') is-invalid @enderror"
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
                            <div class="mb-3">
                                <label class="form-label">Latitude <span class="text-danger">*</span></label>
                                <input type="text" name="latitude"
                                       class="form-control @error('latitude') is-invalid @enderror"
                                       value="{{ old('latitude', $barcode->latitude) }}"
                                       placeholder="Contoh: -6.2087634" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Longitude <span class="text-danger">*</span></label>
                                <input type="text" name="longitude"
                                       class="form-control @error('longitude') is-invalid @enderror"
                                       value="{{ old('longitude', $barcode->longitude) }}"
                                       placeholder="Contoh: 106.845599" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Accuracy (m)</label>
                                <input type="number" name="accuracy"
                                       class="form-control @error('accuracy') is-invalid @enderror"
                                       value="{{ old('accuracy', $barcode->accuracy) }}"
                                       placeholder="Contoh: 50" min="1">
                                <small class="text-muted">Default: 50 meter</small>
                                @error('accuracy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('geolocation.barcode.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
