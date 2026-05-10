{{-- resources/views/geolocation/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Tambah Lokasi')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tambah Lokasi Baru</h5>
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

                <form action="{{ route('geolocation.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Latitude <span class="text-danger">*</span></label>
                                <input type="text" name="latitude"
                                       class="form-control @error('latitude') is-invalid @enderror"
                                       value="{{ old('latitude') }}"
                                       placeholder="Contoh: -6.2087634" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Longitude <span class="text-danger">*</span></label>
                                <input type="text" name="longitude"
                                       class="form-control @error('longitude') is-invalid @enderror"
                                       value="{{ old('longitude') }}"
                                       placeholder="Contoh: 106.845599" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                  rows="3" placeholder="Masukkan alamat lengkap...">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('geolocation.list') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection