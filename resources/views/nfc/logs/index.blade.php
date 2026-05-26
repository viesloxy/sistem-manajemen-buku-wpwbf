{{-- resources/views/nfc/logs/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span> Log Aktivitas NFC
    </h3>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Riwayat Scan NFC</h4>
                    <div class="d-flex gap-2">
                        <select id="filter-status" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="success">Sukses</option>
                            <option value="failed">Gagal</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="logTable">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Waktu Scan</th>
                                <th>Serial Number</th>
                                <th>Nama Pemilik</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $index => $log)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td><span class="badge badge-primary">{{ $log->serial_number }}</span></td>
                                    <td class="fw-bold">{{ $log->nama_pemilik ?? '-' }}</td>
                                    <td>
                                        <span class="badge @if($log->tipe == 'admin') badge-danger @elseif($log->tipe == 'vendor') badge-warning @else badge-info @endif">
                                            {{ ucfirst($log->tipe) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($log->status == 'success')
                                            <span class="badge badge-success">
                                                <i class="mdi mdi-check"></i> Sukses
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="mdi mdi-close"></i> Gagal
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $log->message ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="mdi mdi-clipboard-text" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Belum ada log aktivitas NFC.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection