@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-food"></i>
        </span> Master Menu / Produk ({{ $vendor->nama_vendor }})
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Kelola Produk Vendor <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- Form Input Menu -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Menu/Produk Baru</h4>
                <hr>
                <form action="{{ route('vendor.menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Nama Menu / Produk</label>
                        <input type="text" name="nama_menu" class="form-control border-primary" placeholder="Masukkan nama..." required>
                    </div>

                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white">Rp</span>
                            </div>
                            <input type="number" name="harga" class="form-control border-primary" placeholder="0" required min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Upload Gambar (Opsional)</label>
                        <input type="file" name="gambar" class="form-control border-primary" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG. Maks: 2MB.</small>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary w-100 py-3">
                        <i class="mdi mdi-content-save"></i> SIMPAN PRODUK
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Menu -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Menu Tersedia</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light text-center font-weight-bold">
                            <tr>
                                <th width="50">No</th>
                                <th width="80">Gambar</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $key => $m)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="text-center">
                                        @if($m->path_gambar)
                                            <img src="{{ asset($m->path_gambar) }}" alt="Gambar" style="width: 50px; height: 50px; border-radius: 5px; object-fit: cover;">
                                        @else
                                            <span class="text-muted"><i class="mdi mdi-image-broken"></i></span>
                                        @endif
                                    </td>
                                    <td>{{ $m->nama_menu }}</td>
                                    <td class="text-end font-weight-bold text-primary">Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('vendor.menu.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger p-2" title="Hapus">
                                                <i class="mdi mdi-delete-variant"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada menu/produk yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection