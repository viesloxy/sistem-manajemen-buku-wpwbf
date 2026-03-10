@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-page-variant"></i>
        </span> 
        {{ isset($buku) ? 'Edit Buku' : 'Tambah Buku' }}
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{ isset($buku) ? route('buku.update', $buku->idbuku) : route('buku.store') }}" method="POST">
                    @csrf
                    @if(isset($buku))
                        @method('PUT')
                    @endif

                    <!-- Dropdown Kategori -->
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <select class="form-control" id="idkategori" name="idkategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->idkategori }}" 
                                    {{ (isset($buku) && $buku->idkategori == $kat->idkategori) ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kode">Kode Buku</label>
                        <input type="text" class="form-control" name="kode" placeholder="Contoh: NV-01" value="{{ $buku->kode ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <input type="text" class="form-control" name="judul" placeholder="Judul Buku" value="{{ $buku->judul ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" class="form-control" name="pengarang" placeholder="Nama Pengarang" value="{{ $buku->pengarang ?? '' }}" required>
                    </div>

                    <button type="button" class="btn btn-gradient-primary me-2 btn-submit">
                        <span class="btn-text">Simpan</span>
                    </button>
                    <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection