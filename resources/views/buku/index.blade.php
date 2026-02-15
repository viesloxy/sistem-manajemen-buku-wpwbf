@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-page-variant"></i>
        </span> Data Buku
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-icon-text btn-sm">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Buku
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Koleksi Buku</h4>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th> Kode </th>
                                <th> Judul </th>
                                <th> Pengarang </th>
                                <th> Kategori </th>
                                <th class="text-end"> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buku as $item)
                            <tr>
                                <td> 
                                    <label class="badge badge-outline-dark">{{ $item->kode }}</label>
                                </td>
                                <td class="text-wrap" style="max-width: 300px;"> {{ $item->judul }} </td>
                                <td> {{ $item->pengarang }} </td>
                                <td> 
                                    <label class="badge badge-gradient-info">
                                        {{ $item->kategori->nama_kategori ?? '-' }} 
                                    </label>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('buku.destroy', $item->idbuku) }}" method="POST" class="d-inline">
                                        <a href="{{ route('buku.edit', $item->idbuku) }}" class="btn btn-gradient-warning btn-sm btn-icon-text">
                                            Edit <i class="mdi mdi-pencil btn-icon-append"></i>
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-gradient-danger btn-sm btn-icon-text" onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                            Hapus <i class="mdi mdi-delete btn-icon-append"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Data buku belum tersedia.</td>
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