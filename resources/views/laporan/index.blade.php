@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-file-document-multiple"></i>
        </span> Generator Laporan PDF
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Katalog Eksklusif 
                    <i class="mdi mdi-image-filter mdi-24px float-end"></i>
                </h4>
                <p>Mencetak daftar buku dengan tampilan estetik landscape untuk keperluan pajangan atau promosi.</p>
                <div class="mt-4">
                    <a href="{{ route('laporan.katalog') }}" target="_blank" class="btn btn-outline-light btn-sm font-weight-bold">
                        <i class="mdi mdi-download me-1"></i> Download Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Laporan Stok Resmi 
                    <i class="mdi mdi-file-document mdi-24px float-end"></i>
                </h4>
                <p>Mencetak laporan stok buku resmi dengan format portrait lengkap dengan Kop Surat Universitas.</p>
                <div class="mt-4">
                    <a href="{{ route('laporan.stok') }}" target="_blank" class="btn btn-outline-light btn-sm font-weight-bold">
                        <i class="mdi mdi-download me-1"></i> Download Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection