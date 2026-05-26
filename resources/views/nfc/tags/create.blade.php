{{-- resources/views/nfc/tags/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span> Tambah Kartu NFC Baru
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('nfc.tags.index') }}" class="btn btn-gradient-info btn-icon-text btn-sm">
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
                <h4 class="card-title">Form Tambah Kartu NFC</h4>
                <hr class="mb-4">

                <form action="{{ route('nfc.tags.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_number">Serial Number <span class="text-danger">*</span></label>
                                <input type="text" id="serial_number" name="serial_number"
                                       class="form-control border-primary @error('serial_number') is-invalid @enderror"
                                       value="{{ old('serial_number') }}"
                                       placeholder="Contoh: NFC-001-ABCD1234" required>
                                @error('serial_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_pemilik">Nama Pemilik <span class="text-danger">*</span></label>
                                <input type="text" id="nama_pemilik" name="nama_pemilik"
                                       class="form-control border-primary @error('nama_pemilik') is-invalid @enderror"
                                       value="{{ old('nama_pemilik') }}"
                                       placeholder="Contoh: John Doe" required>
                                @error('nama_pemilik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipe">Tipe <span class="text-danger">*</span></label>
                                <select id="tipe" name="tipe"
                                        class="form-select border-primary @error('tipe') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="admin" {{ old('tipe') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="vendor" {{ old('tipe') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                    <option value="staff" {{ old('tipe') == 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vendor_id">Vendor</label>
                                <select id="vendor_id" name="vendor_id"
                                        class="form-select border-primary @error('vendor_id') is-invalid @enderror">
                                    <option value="">-- Pilih Vendor --</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->nama_vendor }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select id="status" name="status"
                                        class="form-select border-primary @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="hilang" {{ old('status') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">User (Opsional)</label>
                                <select id="user_id" name="user_id"
                                        class="form-select border-primary @error('user_id') is-invalid @enderror">
                                    <option value="">-- Pilih User --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->role }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary btn-lg me-2">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i> Simpan
                        </button>
                        <a href="{{ route('nfc.tags.index') }}" class="btn btn-outline-secondary btn-lg">
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
                <h4 class="card-title"><i class="mdi mdi-information-outline text-info"></i> Petunjuk</h4>
                <hr>
                <h6 class="text-primary">Isi Data Kartu NFC:</h6>
                <ol class="small">
                    <li><strong>Serial Number</strong> - ID unik kartu NFC</li>
                    <li><strong>Nama Pemilik</strong> - Nama orang yang memegang kartu</li>
                    <li><strong>Tipe</strong> - Peran pemilik (Admin/Vendor/Staff)</li>
                    <li><strong>Vendor</strong> - Vendor terkait (jika tipe Vendor/Staff)</li>
                    <li><strong>Status</strong> - Aktif, Nonaktif, atau Hilang</li>
                </ol>
                <hr>
                <div class="alert alert-info small mb-0">
                    <i class="mdi mdi-lightbulb-outline"></i> <strong>Tips:</strong><br>
                    Serial number biasanya terdapat<br>
                    di belakang kartu NFC atau<br>
                    bisa di-scan menggunakan<br>
                    aplikasi NFC di smartphone.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
