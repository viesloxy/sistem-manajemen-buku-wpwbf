@extends('layouts.app')

@section('content')
    {{-- PAGE HEADER --}}
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-multiple"></i>
            </span> Manajemen Antrian
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Dashboard Admin <i class="mdi mdi-shield-check icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- CARD KONTROL ANTRIAN --}}
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kontrol Antrian</h4>
                    <div class="d-flex gap-2 flex-wrap">
                        <form action="{{ route('antrian.admin.panggil') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-gradient-primary btn-icon-text">
                                <i class="mdi mdi-bullhorn btn-icon-prepend"></i> Panggil Nomor Berikutnya
                            </button>
                        </form>

                        <form action="{{ route('antrian.admin.reset') }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin mereset semua antrian hari ini? Data akan dihapus permanently.');">
                            @csrf
                            <button type="submit" class="btn btn-gradient-danger btn-icon-text">
                                <i class="mdi mdi-refresh btn-icon-prepend"></i> Reset Antrian Hari Ini
                            </button>
                        </form>

                        <a href="{{ route('antrian.papan') }}" target="_blank" class="btn btn-gradient-info btn-icon-text">
                            <i class="mdi mdi-monitor-multiple btn-icon-prepend"></i> Buka Papan Antrian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATUS BAR - 4 CARD TERPISAH BERJEJER --}}
    <div class="row">
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-warning card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">
                        <i class="mdi mdi-clock-outline mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-3" id="stat-menunggu">{{ $stats['menunggu'] }}</h2>
                    <h6 class="card-text">Menunggu</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-primary card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">
                        <i class="mdi mdi-bullhorn mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-3" id="stat-dipanggil">{{ $stats['dipanggil'] }}</h2>
                    <h6 class="card-text">Dipanggil</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">
                        <i class="mdi mdi-clock-alert-outline mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-3" id="stat-terlambat">{{ $stats['terlambat'] }}</h2>
                    <h6 class="card-text">Terlambat</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">
                        <i class="mdi mdi-check-circle-outline mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-3" id="stat-selesai">{{ $stats['selesai'] }}</h2>
                    <h6 class="card-text">Selesai</h6>
                </div>
            </div>
        </div>
    </div>

    {{-- KOTAK NOMOR DIPANGGIL --}}
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px;">
                    <h4 class="text-white mb-3">Sedang Dipanggil</h4>
                    @if($dipanggil)
                        <h1 class="display-1 font-weight-bold mb-3" id="nomor-dipanggil"
                            style="font-size: 5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                            {{ str_pad($dipanggil->nomor, 3, '0', STR_PAD_LEFT) }}
                        </h1>
                        <h3 class="text-white mb-2" id="nama-dipanggil">{{ $dipanggil->nama }}</h3>
                        <p class="text-white-50 mb-3" id="vendor-dipanggil">
                            <i class="mdi mdi-store"></i> {{ $dipanggil->vendor->nama_vendor ?? '-' }}
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <form action="{{ route('antrian.admin.terlambat', $dipanggil->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-icon-text">
                                    <i class="mdi mdi-clock-alert-outline btn-icon-prepend"></i> Terlambat
                                </button>
                            </form>
                            <form action="{{ route('antrian.admin.selesai', $dipanggil->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-icon-text">
                                    <i class="mdi mdi-check-circle btn-icon-prepend"></i> Selesai
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="py-5">
                            <i class="mdi mdi-bullhorn-outline" style="font-size: 5rem; opacity: 0.3;"></i>
                            <p class="text-white-50 mt-3">Belum ada nomor yang dipanggil</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL DAFTAR ANTRIAN HARI INI --}}
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Antrian Hari Ini</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-gradient-primary text-white text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pembeli</th>
                                    <th>Nama Vendor</th>
                                    <th>Jam Daftar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-antrian">
                                @forelse($antrians as $antrian)
                                    <tr id="row-{{ $antrian->id }}"
                                        class="{{ $antrian->status === 'dipanggil' ? 'table-primary' : '' }}">
                                        <td class="text-center font-weight-bold">
                                            {{ str_pad($antrian->nomor, 3, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td>{{ $antrian->nama }}</td>
                                        <td>
                                            <span class="badge badge-outline-primary">
                                                <i class="mdi mdi-store-outline"></i>
                                                {{ $antrian->vendor->nama_vendor ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <i class="mdi mdi-clock-outline"></i>
                                            {{ $antrian->created_at->format('H:i') }}
                                        </td>
                                        <td class="text-center">
                                            @if($antrian->status === 'menunggu')
                                                <span class="badge badge-warning">
                                                    <i class="mdi mdi-clock-outline"></i> Menunggu
                                                </span>
                                            @elseif($antrian->status === 'dipanggil')
                                                <span class="badge badge-primary">
                                                    <i class="mdi mdi-bullhorn"></i> Dipanggil
                                                </span>
                                            @elseif($antrian->status === 'terlambat')
                                                <span class="badge badge-danger">
                                                    <i class="mdi mdi-clock-alert-outline"></i> Terlambat
                                                </span>
                                            @else
                                                <span class="badge badge-success">
                                                    <i class="mdi mdi-check-circle-outline"></i> Selesai
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                @if($antrian->status !== 'selesai')
                                                    <form action="{{ route('antrian.admin.selesai', $antrian->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm btn-icon"
                                                            title="Selesaikan">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-light btn-sm btn-icon" disabled>
                                                        <i class="mdi mdi-check"></i>
                                                    </button>
                                                @endif

                                                @if($antrian->status === 'dipanggil')
                                                    <form action="{{ route('antrian.admin.terlambat', $antrian->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm btn-icon"
                                                            title="Tandai Terlambat">
                                                            <i class="mdi mdi-clock-alert-outline"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-light btn-sm btn-icon" disabled>
                                                        <i class="mdi mdi-clock-alert-outline"></i>
                                                    </button>
                                                @endif

                                                @if(in_array($antrian->status, ['terlambat']))
                                                    <form action="{{ route('antrian.admin.panggil-ulang', $antrian->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-sm btn-icon"
                                                            title="Panggil Ulang">
                                                            <i class="mdi mdi-play"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-light btn-sm btn-icon" disabled>
                                                        <i class="mdi mdi-play"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="mdi mdi-clipboard-text-outline"
                                                style="font-size: 3rem; opacity: 0.4;"></i>
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
        </div>
    </div>
@endsection

@section('javascript-page')
    <script>
        // ── SSE: Real-time update (long-polling style) ──
        (function () {
            var tabId = Date.now() + Math.random().toString(36).substr(2, 5);
            var sseUrl = '/project-workshop-pwbf/public/antrian/sse';
            var reconnectDelay = 3000; // 3 detik sebelum reconnect
            var source = null;

            function connectSSE() {
                source = new EventSource(sseUrl);

                source.onopen = function () {
                    console.log('[SSE] Koneksi terbuka, menunggu data...');
                };

                source.addEventListener('queue-update', function (event) {
                    var data = JSON.parse(event.data);
                    console.log('SSE Data received:', data);

                    // Update statistik
                    if (data.stats) {
                        document.getElementById('stat-menunggu').textContent = data.stats.menunggu ?? 0;
                        document.getElementById('stat-dipanggil').textContent = data.stats.dipanggil ?? 0;
                        document.getElementById('stat-terlambat').textContent = data.stats.terlambat ?? 0;
                        document.getElementById('stat-selesai').textContent = data.stats.selesai ?? 0;
                    }

                    // Update nomor yang sedang dipanggil
                    var dipanggilEl = document.getElementById('nomor-dipanggil');
                    var namaEl = document.getElementById('nama-dipanggil');
                    var vendorEl = document.getElementById('vendor-dipanggil');

                    if (data.dipanggil) {
                        if (dipanggilEl) dipanggilEl.textContent = String(data.dipanggil.nomor).padStart(3, '0');
                        if (namaEl) namaEl.textContent = data.dipanggil.nama;
                        if (vendorEl) vendorEl.innerHTML = '<i class="mdi mdi-store"></i> ' + (data.dipanggil.vendor ?? '-');
                    }

                    // Update tabel antrian secara real-time
                    if (data.antrians) {
                        updateTabelAntrian(data.antrians);
                    }
                });

                // Saat error, tunggu sebelum reconnect
                source.onerror = function () {
                    console.warn('[SSE] Koneksi terputus, reconnect dalam', reconnectDelay / 1000, 'detik...');
                    source.close();
                    setTimeout(connectSSE, reconnectDelay);
                };
            }

            connectSSE();

            // Tutup koneksi SSE saat tab di-close
            window.addEventListener('beforeunload', function () { if (source) source.close(); });
            window.addEventListener('pagehide', function () { if (source) source.close(); });

            // Fungsi untuk update tabel antrian
            function updateTabelAntrian(antrians) {
                var tbody = document.getElementById('tbody-antrian');
                if (!tbody) return;

                if (antrians.length === 0) {
                    tbody.innerHTML = `
                                                                            <tr>
                                                                                <td colspan="6" class="text-center py-5" style="color:#9ca3af;">
                                                                                    <i class="mdi mdi-clipboard-text-outline" style="font-size:3rem; opacity:0.4;"></i>
                                                                                    <p class="mt-2 mb-0">Belum ada antrian hari ini</p>
                                                                                    <small>Antrian akan muncul setelah pembeli mendaftar</small>
                                                                                </td>
                                                                            </tr>
                                                                        `;
                    return;
                }

                var html = '';
                antrians.forEach(function (antrian) {
                    var statusBadge = '';
                    var statusClass = '';

                    if (antrian.status === 'menunggu') {
                        statusBadge = '<span class="badge badge-warning">Menunggu</span>';
                    } else if (antrian.status === 'dipanggil') {
                        statusBadge = '<span class="badge badge-primary">Dipanggil</span>';
                        statusClass = 'table-primary';
                    } else if (antrian.status === 'terlambat') {
                        statusBadge = '<span class="badge badge-danger">Terlambat</span>';
                    } else if (antrian.status === 'selesai') {
                        statusBadge = '<span class="badge badge-success">Selesai</span>';
                    }

                    html += `
                                                                            <tr class="${statusClass}">
                                                                                <td class="text-center font-weight-bold">${String(antrian.nomor).padStart(3, '0')}</td>
                                                                                <td>${antrian.nama}</td>
                                                                                <td><i class="mdi mdi-store-outline"></i> ${antrian.vendor || '-'}</td>
                                                                                <td><i class="mdi mdi-clock-outline"></i> ${antrian.created_at}</td>
                                                                                <td class="text-center">${statusBadge}</td>
                                                                                <td class="text-center">
                                                                                    <div class="btn-group" role="group">
                                                                                        ${antrian.status !== 'selesai' ? `
                                                                                            <form action="/antrian/admin/selesai/${antrian.id}" method="POST" class="d-inline">
                                                                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content || ''}">
                                                                                                <button type="submit" class="btn btn-sm btn-success" title="Selesaikan">
                                                                                                    <i class="mdi mdi-check"></i>
                                                                                                </button>
                                                                                    </form>
                                                                                        ` : `
                                                                                            <button class="btn btn-sm btn-secondary" disabled>
                                                                                                <i class="mdi mdi-check"></i>
                                                                                            </button>
                                                                                        `}

                                                                                        ${antrian.status === 'dipanggil' ? `
                                                                                            <form action="/antrian/admin/terlambat/${antrian.id}" method="POST" class="d-inline">
                                                                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content || ''}">
                                                                                                <button type="submit" class="btn btn-sm btn-warning" title="Tandai Terlambat">
                                                                                                    <i class="mdi mdi-clock-alert-outline"></i>
                                                                                                </button>
                                                                                            </form>
                                                                                        ` : `
                                                                                            <button class="btn btn-sm btn-secondary" disabled>
                                                                                                <i class="mdi mdi-clock-alert-outline"></i>
                                                                                            </button>
                                                                                        `}

                                                                                        ${antrian.status === 'terlambat' ? `
                                                                                            <form action="/antrian/admin/panggil-ulang/${antrian.id}" method="POST" class="d-inline">
                                                                                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content || ''}">
                                                                                                <button type="submit" class="btn btn-sm btn-info" title="Panggil Ulang">
                                                                                                    <i class="mdi mdi-play"></i>
                                                                                                </button>
                                                                                            </form>
                                                                                        ` : `
                                                                                            <button class="btn btn-sm btn-secondary" disabled>
                                                                                                <i class="mdi mdi-play"></i>
                                                                                            </button>
                                                                                        `}
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        `;
                });

                tbody.innerHTML = html;
            }
        })();
    </script>
@endsection