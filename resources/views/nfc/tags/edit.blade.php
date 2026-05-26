{{-- resources/views/nfc/tags/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span> Edit Kartu NFC
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
                <h4 class="card-title">Form Edit Kartu NFC</h4>
                <hr class="mb-4">

                <form action="{{ route('nfc.tags.update', $tag->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_number">Serial Number <span class="text-danger">*</span></label>
                                <input type="text" id="serial_number" name="serial_number"
                                       class="form-control border-primary @error('serial_number') is-invalid @enderror"
                                       value="{{ old('serial_number', $tag->serial_number) }}" required>
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
                                       value="{{ old('nama_pemilik', $tag->nama_pemilik) }}" required>
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
                                    <option value="admin" {{ old('tipe', $tag->tipe) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="vendor" {{ old('tipe', $tag->tipe) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                    <option value="staff" {{ old('tipe', $tag->tipe) == 'staff' ? 'selected' : '' }}>Staff</option>
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
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id', $tag->vendor_id) == $vendor->id ? 'selected' : '' }}>
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
                                    <option value="aktif" {{ old('status', $tag->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $tag->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="hilang" {{ old('status', $tag->status) == 'hilang' ? 'selected' : '' }}>Hilang</option>
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
                                        <option value="{{ $user->id }}" {{ old('user_id', $tag->user_id) == $user->id ? 'selected' : '' }}>
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
                            <i class="mdi mdi-content-save btn-icon-prepend"></i> Perbarui
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
                <h4 class="card-title"><i class="mdi mdi-information-outline text-info"></i> Info</h4>
                <hr>
                <div class="alert alert-warning small">
                    <i class="mdi mdi-alert"></i> <strong>Perhatian!</strong><br>
                    Pastikan serial number unik dan tidak<br>
                    digunakan oleh kartu lain.
                </div>
                <hr>
                <h6 class="text-primary">Data Saat Ini:</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Serial Number</td>
                        <td class="fw-bold">{{ $tag->serial_number }}</td>
                    </tr>
                    <tr>
                        <td>Pemilik</td>
                        <td class="fw-bold">{{ $tag->nama_pemilik }}</td>
                    </tr>
                    <tr>
                        <td>Tipe</td>
                        <td class="fw-bold">{{ ucfirst($tag->tipe) }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td class="fw-bold">{{ $tag->status }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
