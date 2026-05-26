{{-- resources/views/vendor/nfc/logs.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-warning text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span> Log Aktivitas NFC Vendor
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
                <h4 class="card-title">Riwayat Scan NFC Saya</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Waktu</th>
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th>Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $index => $log)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td><span class="badge badge-warning">{{ $log->serial_number }}</span></td>
                                    <td>
                                        @if($log->status == 'success')
                                            <span class="badge badge-success">Sukses</span>
                                        @else
                                            <span class="badge badge-danger">Gagal</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->message ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="mdi mdi-clipboard-text" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Belum ada log NFC.</p>
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