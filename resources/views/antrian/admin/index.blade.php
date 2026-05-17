@extends('layouts.app')

@section('css-page')
<style>
    :root {
        --pa-purple: #9a55ff;
        --pa-purple2: #da8cff;
        --pa-dark: #5b21b6;
        --pa-bg: #f2edf3;
        --pa-border: #e8dfff;
    }

    body { background: var(--pa-bg); }
    .page-body-wrapper { background: var(--pa-bg); }

    /* ── TOMBOL AREA ── */
    .action-btn-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .btn-action-primary {
        background: linear-gradient(135deg, #9a55ff, #7c3aed);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(154, 85, 255, 0.35);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-action-primary:hover {
        background: linear-gradient(135deg, #7c3aed, #5b21b6);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(154, 85, 255, 0.5);
    }
    .btn-action-secondary {
        background: #fff;
        color: #7c3aed;
        border: 2px solid #e8dfff;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-action-secondary:hover {
        background: #f2edf3;
        color: #5b21b6;
        border-color: #9a55ff;
    }

    /* ── KOTAK NOMOR DIPANGGIL ── */
    .dipanggil-card {
        background: linear-gradient(135deg, #7c3aed, #5b21b6);
        border-radius: 18px;
        padding: 24px;
        color: #fff;
        text-align: center;
        box-shadow: 0 8px 32px rgba(92, 33, 182, 0.3);
    }
    .dipanggil-card .nomor-besar {
        font-size: clamp(60px, 10vw, 100px);
        font-weight: 900;
        line-height: 1;
        color: #fbbf24;
        text-shadow: 0 0 30px rgba(251, 191, 36, 0.6);
        animation: goldPulse 2s ease-in-out infinite;
    }
    @keyframes goldPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.04); }
    }
    .dipanggil-card .nama-pemanggil {
        font-size: 1.4rem;
        font-weight: 700;
        margin-top: 8px;
    }
    .dipanggil-card .vendor-pemanggil {
        font-size: 1rem;
        opacity: 0.85;
    }
    .dipanggil-kosong {
        font-size: 3rem;
        opacity: 0.4;
        padding: 30px 0;
    }

    /* ── STATUS BAR ── */
    .status-bar-wrapper {
        background: #fff;
        border-radius: 16px;
        padding: 16px 24px;
        display: flex;
        gap: 0;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid var(--pa-border);
        overflow: hidden;
    }
    .status-item {
        flex: 1;
        text-align: center;
        padding: 8px 12px;
        border-right: 1px solid var(--pa-border);
    }
    .status-item:last-child { border-right: none; }
    .status-item .status-count {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1;
    }
    .status-item .status-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }
    .status-menunggu .status-count { color: #f59e0b; }
    .status-dipanggil .status-count { color: #9a55ff; }
    .status-terlambat .status-count { color: #ef4444; }
    .status-selesai .status-count { color: #10b981; }

    /* ── TABEL ── */
    .table-antrian {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .table-antrian thead th {
        background: linear-gradient(135deg, #7c3aed, #5b21b6);
        color: #fff;
        font-weight: 700;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border: none;
    }
    .table-antrian thead th:first-child { border-radius: 12px 0 0 12px; }
    .table-antrian thead th:last-child { border-radius: 0 12px 12px 0; }
    .table-antrian tbody tr:hover { background: #f8f5ff; }
    .table-antrian td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--pa-border);
        font-size: 0.9rem;
    }
    .table-antrian tbody tr:last-child td { border-bottom: none; }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-menunggu { background: #fef3c7; color: #d97706; }
    .badge-dipanggil { background: #ede9fe; color: #7c3aed; font-weight: 800; }
    .badge-terlambat { background: #fee2e2; color: #dc2626; }
    .badge-selesai { background: #d1fae5; color: #059669; }

    .btn-aksi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        padding: 0;
    }
    .btn-aksi-selesai { background: #d1fae5; color: #059669; }
    .btn-aksi-selesai:hover { background: #10b981; color: #fff; }
    .btn-aksi-terlambat { background: #fee2e2; color: #dc2626; }
    .btn-aksi-terlambat:hover { background: #ef4444; color: #fff; }
    .btn-aksi-panggil { background: #ede9fe; color: #7c3aed; }
    .btn-aksi-panggil:hover { background: #9a55ff; color: #fff; }

    .row-dipanggil {
        background: linear-gradient(90deg, #ede9fe 0%, #f8f5ff 100%) !important;
        border-left: 4px solid #9a55ff;
    }

    .alert-flash {
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
{{-- FLASH MESSAGE --}}
@if(session('success'))
    <div class="alert alert-success alert-flash d-flex align-items-center gap-2" role="alert">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-flash d-flex align-items-center gap-2" role="alert">
        <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
    </div>
@endif

{{-- 3 TOMBOL AKSI ATAS --}}
<div class="action-btn-group">
    <form action="{{ route('antrian.admin.panggil') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn-action-primary" id="btn-panggil">
            <i class="mdi mdi-bullhorn"></i> Panggil Nomor Berikutnya
        </button>
    </form>

    <form action="{{ route('antrian.admin.reset') }}" method="POST" class="d-inline"
          onsubmit="return confirm('Yakin ingin mereset semua antrian hari ini? Data akan dihapus permanently.');">
        @csrf
        <button type="submit" class="btn-action-secondary" style="color:#ef4444; border-color:#fecaca;">
            <i class="mdi mdi-refresh"></i> Reset Antrian Hari Ini
        </button>
    </form>

    <a href="{{ route('antrian.papan') }}" target="_blank" class="btn-action-secondary">
        <i class="mdi mdi-monitor-multiple"></i> Buka Papan Antrian
    </a>
</div>

{{-- KOTAK NOMOR DIPANGGIL + STATUS BAR --}}
<div class="row mb-4">
    <div class="col-lg-5 mb-3">
        <div class="dipanggil-card">
            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.7; margin-bottom: 8px;">
                Sedang Dipanggil
            </div>

            @if($dipanggil)
                <div class="nomor-besar" id="nomor-dipanggil">{{ $dipanggil->nomor }}</div>
                <div class="nama-pemanggil" id="nama-dipanggil">{{ $dipanggil->nama }}</div>
                <div class="vendor-pemanggil" id="vendor-dipanggil">
                    <i class="mdi mdi-store"></i> {{ $dipanggil->vendor->nama_vendor ?? '-' }}
                </div>
                <div style="margin-top: 16px; display:flex; gap: 8px; justify-content: center;">
                    <form action="{{ route('antrian.admin.terlambat', $dipanggil->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-action-secondary" style="color:#fff; border-color:rgba(255,255,255,0.4);">
                            <i class="mdi mdi-clock-alert-outline"></i> Terlambat
                        </button>
                    </form>
                    <form action="{{ route('antrian.admin.selesai', $dipanggil->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-action-primary" style="background:#fff; color:#059669; box-shadow:none;">
                            <i class="mdi mdi-check-circle"></i> Selesai
                        </button>
                    </form>
                </div>
            @else
                <div class="dipanggil-kosong">
                    <i class="mdi mdi-bullhorn-outline"></i>
                </div>
                <div style="opacity: 0.7; font-size: 0.9rem;">Belum ada nomor yang dipanggil</div>
            @endif
        </div>
    </div>

    <div class="col-lg-7 mb-3">
        <div class="status-bar-wrapper" style="height: 100%; align-items: center; min-height: 140px;">
            <div class="status-item status-menunggu">
                <div class="status-count" id="stat-menunggu">{{ $stats['menunggu'] }}</div>
                <div class="status-label">
                    <i class="mdi mdi-clock-outline"></i> Menunggu
                </div>
            </div>
            <div class="status-item status-dipanggil">
                <div class="status-count" id="stat-dipanggil">{{ $stats['dipanggil'] }}</div>
                <div class="status-label">
                    <i class="mdi mdi-bullhorn"></i> Dipanggil
                </div>
            </div>
            <div class="status-item status-terlambat">
                <div class="status-count" id="stat-terlambat">{{ $stats['terlambat'] }}</div>
                <div class="status-label">
                    <i class="mdi mdi-clock-alert-outline"></i> Terlambat
                </div>
            </div>
            <div class="status-item status-selesai">
                <div class="status-count" id="stat-selesai">{{ $stats['selesai'] }}</div>
                <div class="status-label">
                    <i class="mdi mdi-check-circle-outline"></i> Selesai
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL DAFTAR ANTRIAN HARI INI --}}
<div class="card" style="border-radius: 16px; border: 1px solid var(--pa-border); box-shadow: 0 4px 16px rgba(154,85,255,0.1);">
    <div class="card-header" style="background:#fff; border-bottom: 2px solid var(--pa-border); border-radius: 16px 16px 0 0; padding: 16px 24px;">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0 font-weight-bold" style="color: #5b21b6;">
                <i class="mdi mdi-format-list-bulleted"></i> Daftar Antrian Hari Ini
            </h5>
            <span class="badge" style="background: linear-gradient(135deg,#9a55ff,#7c3aed); color:#fff; padding:6px 14px; border-radius:20px;">
                {{ $antrians->count() }} Total
            </span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-antrian" id="tabel-antrian">
                <thead>
                    <tr>
                        <th class="text-center" style="width:50px;">No</th>
                        <th>Nama Pembeli</th>
                        <th>Nama Vendor</th>
                        <th style="width:100px;">Jam Daftar</th>
                        <th style="width:120px; text-align:center;">Status</th>
                        <th style="width:130px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-antrian">
                    @forelse($antrians as $antrian)
                        <tr id="row-{{ $antrian->id }}"
                            class="{{ $antrian->status === 'dipanggil' ? 'row-dipanggil' : '' }}">
                            <td class="text-center font-weight-bold" style="color:#9a55ff;">
                                {{ str_pad($antrian->nomor, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div class="font-weight-semibold">{{ $antrian->nama }}</div>
                            </td>
                            <td>
                                <span style="color:#7c3aed; font-size:0.85rem;">
                                    <i class="mdi mdi-store-outline"></i>
                                    {{ $antrian->vendor->nama_vendor ?? '-' }}
                                </span>
                            </td>
                            <td style="color:#6b7280; font-size:0.85rem;">
                                <i class="mdi mdi-clock-outline"></i>
                                {{ $antrian->created_at->format('H:i') }}
                            </td>
                            <td style="text-align:center;">
                                @if($antrian->status === 'menunggu')
                                    <span class="badge-status badge-menunggu">
                                        <i class="mdi mdi-clock-outline"></i> Menunggu
                                    </span>
                                @elseif($antrian->status === 'dipanggil')
                                    <span class="badge-status badge-dipanggil">
                                        <i class="mdi mdi-bullhorn"></i> Dipanggil
                                    </span>
                                @elseif($antrian->status === 'terlambat')
                                    <span class="badge-status badge-terlambat">
                                        <i class="mdi mdi-clock-alert-outline"></i> Terlambat
                                    </span>
                                @else
                                    <span class="badge-status badge-selesai">
                                        <i class="mdi mdi-check-circle-outline"></i> Selesai
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex; gap:6px; justify-content:center; align-items:center;">
                                    @if($antrian->status !== 'selesai')
                                        <form action="{{ route('antrian.admin.selesai', $antrian->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-aksi btn-aksi-selesai" title="Selesaikan">
                                                <i class="mdi mdi-check" style="font-size:14px;"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn-aksi" style="background:#f3f4f6; color:#d1d5db; cursor:default;" disabled>
                                            <i class="mdi mdi-check" style="font-size:14px;"></i>
                                        </button>
                                    @endif

                                    @if($antrian->status === 'dipanggil')
                                        <form action="{{ route('antrian.admin.terlambat', $antrian->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-aksi btn-aksi-terlambat" title="Tandai Terlambat">
                                                <i class="mdi mdi-clock-alert-outline" style="font-size:14px;"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn-aksi" style="background:#f3f4f6; color:#d1d5db; cursor:default;" disabled>
                                            <i class="mdi mdi-clock-alert-outline" style="font-size:14px;"></i>
                                        </button>
                                    @endif

                                    @if(in_array($antrian->status, ['terlambat', 'menunggu']))
                                        <form action="{{ route('antrian.admin.panggil-ulang', $antrian->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-aksi btn-aksi-panggil" title="Panggil Ulang">
                                                <i class="mdi mdi-play" style="font-size:14px;"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn-aksi" style="background:#f3f4f6; color:#d1d5db; cursor:default;" disabled>
                                            <i class="mdi mdi-play" style="font-size:14px;"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color:#9ca3af;">
                                <i class="mdi mdi-clipboard-text-outline" style="font-size:3rem; opacity:0.4;"></i>
                                <p class="mt-2 mb-0">Belum ada antrian hari ini</p>
                                <small>Antrian akan muncul setelah pembeli mendaftar</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script>
    const source = new EventSource('{{ route('antrian.sse') }}');

    source.addEventListener('queue-update', function(event) {
        const data = JSON.parse(event.data);

        if (data.stats) {
            document.getElementById('stat-menunggu').textContent  = data.stats.menunggu  ?? 0;
            document.getElementById('stat-dipanggil').textContent = data.stats.dipanggil ?? 0;
            document.getElementById('stat-terlambat').textContent = data.stats.terlambat ?? 0;
            document.getElementById('stat-selesai').textContent   = data.stats.selesai   ?? 0;
        }

        const dipanggilEl   = document.getElementById('nomor-dipanggil');
        const namaEl       = document.getElementById('nama-dipanggil');
        const vendorEl     = document.getElementById('vendor-dipanggil');

        if (data.dipanggil) {
            if (dipanggilEl) dipanggilEl.textContent = data.dipanggil.nomor;
            if (namaEl)      namaEl.textContent      = data.dipanggil.nama;
            if (vendorEl)    vendorEl.innerHTML      = '<i class="mdi mdi-store"></i> ' + (data.dipanggil.vendor ?? '-');
        }
    });

    source.onerror = function(error) {
        console.warn('SSE connection lost, will retry...');
    };

    // Auto-reload setiap 30 detik untuk sinkronisasi penuh
    setInterval(() => {
        if (!document.hidden) {
            location.reload();
        }
    }, 30000);
</script>
@endsection