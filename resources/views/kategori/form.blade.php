@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-format-list-bulleted"></i>
        </span> 
        {{ isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori' }}
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{ isset($kategori) ? route('kategori.update', $kategori->idkategori) : route('kategori.store') }}" method="POST">
                    @csrf
                    @if(isset($kategori))
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Contoh: Novel" value="{{ $kategori->nama_kategori ?? '' }}" required>
                    </div>

                    <button type="button" class="btn btn-gradient-primary me-2 btn-submit">
                        <span class="btn-text">Simpan</span>
                    </button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection