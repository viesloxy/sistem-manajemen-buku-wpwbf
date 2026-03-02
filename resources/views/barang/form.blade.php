@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-tag-multiple"></i>
        </span> 
        {{ isset($barang) ? 'Edit Barang' : 'Tambah Barang' }}
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('barang.index') }}" class="btn btn-gradient-primary btn-icon-text btn-sm">
                    <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Kembali
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Informasi Produk UMKM</h4>
                <form class="forms-sample" action="{{ isset($barang) ? route('barang.update', $barang->id_barang) : route('barang.store') }}" method="POST">
                    @csrf
                    @if(isset($barang))
                        @method('PUT')
                    @endif

                    @if(isset($barang))
                    <div class="form-group">
                        <label>UID Barang (Dibuat otomatis oleh Sistem)</label>
                        <input type="text" class="form-control" value="{{ $barang->id_barang }}" disabled>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="nama">Nama Barang</label>
                        <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkan nama barang" value="{{ $barang->nama ?? old('nama') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga Satuan (Rp)</label>
                        <input type="number" class="form-control" name="harga" id="harga" placeholder="Contoh: 15000" value="{{ $barang->harga ?? old('harga') }}" required>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary me-2">
                        <i class="mdi mdi-content-save me-1"></i> Simpan Data
                    </button>
                    <a href="{{ route('barang.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection