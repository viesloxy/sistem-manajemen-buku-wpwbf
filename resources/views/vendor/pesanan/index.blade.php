@extends('layouts.app')

@section('style-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .page-title-icon { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .filter-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
    
    /* Styling Badge Status ala Midtrans */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-badge .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    
    .status-lunas { background-color: #e6f6ec; color: #1e7e34; border: 1px solid #c3e6cb; }
    .status-lunas .dot { background-color: #28a745; }
    
    .status-pending { background-color: #fff8e5; color: #856404; border: 1px solid #ffeeba; }
    .status-pending .dot { background-color: #ffc107; }

    .status-expired { background-color: #fceceb; color: #721c24; border: 1px solid #f5c6cb; }
    .status-expired .dot { background-color: #dc3545; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-cart-arrow-down"></i>
        </span> Daftar Transaksi
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Pesanan Kantin <i class="mdi mdi-check-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary">Filter Pencarian</h4>
                
                <!-- FILTER FORM -->
                <div class="filter-card border-primary" style="border-width: 1px;">
                    <form action="{{ route('vendor.pesanan.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label class="small text-muted font-weight-bold">Status Pembayaran</label>
                                <select name="status" class="form-select border-primary">
                                    <option value="Semua" {{ $statusFilter == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="Lunas" {{ $statusFilter == 'Lunas' ? 'selected' : '' }}>Lunas (Settlement)</option>
                                    <option value="Pending" {{ $statusFilter == 'Pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                                    <option value="Kedaluwarsa" {{ $statusFilter == 'Kedaluwarsa' ? 'selected' : '' }}>Kedaluwarsa (Expired)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small text-muted font-weight-bold">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control border-primary" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small text-muted font-weight-bold">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control border-primary" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-gradient-primary w-100">
                                    <i class="mdi mdi-filter"></i> Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TABEL DATATABLES -->
                <div class="table-responsive">
                    <table id="pesanan-table" class="table table-striped table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th> Tanggal & Waktu </th>
                                <th> Order ID </th>
                                <th> Nama Customer </th>
                                <th> Item Menu </th>
                                <th> Nilai (Total) </th>
                                <th class="text-center"> Status </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanans as $pesanan)
                                @php
                                    $totalVendorIni = $pesanan->detailPesanans->sum('subtotal');
                                @endphp
                                <tr>
                                    <td>{{ $pesanan->created_at->format('d M Y, H:i') }}</td>
                                    <td><label class="badge badge-outline-dark">#ORD-{{ $pesanan->id }}</label></td>
                                    <td>{{ $pesanan->nama }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0 pl-0" style="font-size: 13px;">
                                            @foreach($pesanan->detailPesanans as $detail)
                                                <li>- {{ $detail->menu->nama_menu }} <b>(x{{ $detail->jumlah }})</b></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="font-weight-bold text-primary">Rp {{ number_format($totalVendorIni, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($pesanan->status_bayar == 'Lunas' || strtolower($pesanan->status_bayar) == 'settlement')
                                            <span class="status-badge status-lunas">
                                                <span class="dot"></span> Settlement
                                            </span>
                                        @elseif($pesanan->status_bayar == 'Pending')
                                            <span class="status-badge status-pending">
                                                <span class="dot"></span> Tertunda
                                            </span>
                                        @else
                                            <span class="status-badge status-expired">
                                                <span class="dot"></span> {{ $pesanan->status_bayar }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#pesanan-table').DataTable({ 
            "language": { 
                "search": "Cari Order ID / Nama:", 
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "order": [[ 0, "desc" ]] // Urutkan dari yang terbaru
        });
    });
</script>
@endsection