@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-format-list-bulleted"></i>
        </span> Kategori
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-icon-text btn-sm">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Kategori
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Kategori</h4>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th> No. </th>
                                <th style="width: 70%;"> Nama Kategori </th>
                                <th class="text-end"> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategori as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->nama_kategori }} </td>
                                <td class="text-end">
                                    <form action="{{ route('kategori.destroy', $item->idkategori) }}" method="POST" class="d-inline">
                                        <a href="{{ route('kategori.edit', $item->idkategori) }}" class="btn btn-gradient-warning btn-sm btn-icon-text">
                                            Edit <i class="mdi mdi-pencil btn-icon-append"></i>
                                        </a>
                                        
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-gradient-danger btn-sm btn-icon-text" onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                            Hapus <i class="mdi mdi-delete btn-icon-append"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Data kategori belum tersedia.</td>
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