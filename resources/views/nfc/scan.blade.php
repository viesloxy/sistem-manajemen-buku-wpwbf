{{-- resources/views/nfc/scan.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span> Scan NFC Tag
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span class="badge badge-info">Mode Admin</span>
            </li>
        </ul>
    </nav>
</div>

{{-- Info Alert --}}
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="mdi mdi-information btn-icon-prepend"></i>
    Tempelkan NFC Tag pada perangkat untuk mencatat absensi masuk/keluar.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

{{-- NFC Reader Card --}}
<div class="row">
    <div class="col-lg-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">NFC Reader</h4>
                <div class="text-center py-4">
                    <div class="nfc-animation mb-4">
                        <i class="mdi mdi-nfc" style="font-size: 5rem; color: #2196F3;"></i>
                    </div>
                    <p class="text-muted mb-3">Tap NFC Tag untuk membaca</p>

                    {{-- Toggle Tipe Log --}}
                    <div class="btn-group mb-3" role="group">
                        <input type="radio" class="btn-check" name="tipe_log" id="tipe-masuk" value="masuk" checked>
                        <label class="btn btn-outline-primary" for="tipe-masuk">
                            <i class="mdi mdi-login"></i> MASUK
                        </label>
                        <input type="radio" class="btn-check" name="tipe_log" id="tipe-keluar" value="keluar">
                        <label class="btn btn-outline-danger" for="tipe-keluar">
                            <i class="mdi mdi-logout"></i> KELUAR
                        </label>
                    </div>

                    <br>
                    <button type="button" class="btn btn-gradient-primary btn-lg" id="startNfcBtn">
                        <i class="mdi mdi-nfc-search-variant btn-icon-prepend"></i>
                        Mulai Scan NFC
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Hasil Scan</h4>
                <div id="nfc-result" class="d-none">
                    <div id="success-alert" class="alert alert-success d-none">
                        <i class="mdi mdi-check-circle btn-icon-prepend"></i>
                        <span id="result-message">Berhasil!</span>
                    </div>
                    <div id="error-alert" class="alert alert-danger d-none">
                        <i class="mdi mdi-alert-circle btn-icon-prepend"></i>
                        <span id="error-message">Error!</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="35%">Serial Number</th>
                                <td id="result-serial">-</td>
                            </tr>
                            <tr>
                                <th>Nama Pemilik</th>
                                <td id="result-nama">-</td>
                            </tr>
                            <tr>
                                <th>Tipe</th>
                                <td id="result-tipe">-</td>
                            </tr>
                            <tr>
                                <th>Waktu Scan</th>
                                <td id="result-waktu">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="nfc-empty" class="text-center py-5">
                    <i class="mdi mdi-nfc-off" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Belum ada hasil scan</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Scans Card --}}
<div class="row mt-3">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Scan Terakhir</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Serial Number</th>
                                <th>Nama Pemilik</th>
                                <th>Tipe Log</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentScans as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><code>{{ $log->nfcTag->serial_number ?? '-' }}</code></td>
                                    <td>{{ $log->nfcTag->nama_pemilik ?? '-' }}</td>
                                    <td>
                                        @if($log->tipe_log == 'masuk')
                                            <span class="badge badge-success">MASUK</span>
                                        @else
                                            <span class="badge badge-danger">KELUAR</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->scanned_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="mdi mdi-clipboard-text-off" style="font-size: 2rem;"></i>
                                        <p class="mt-2">Belum ada scan terakhir</p>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startBtn = document.getElementById('startNfcBtn');
    const resultSection = document.getElementById('nfc-result');
    const emptySection = document.getElementById('nfc-empty');
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    const resultMessage = document.getElementById('result-message');
    const errorMessage = document.getElementById('error-message');

    function showResult(data) {
        document.getElementById('result-serial').textContent = data.serial;
        document.getElementById('result-nama').textContent = data.nama;
        document.getElementById('result-tipe').textContent = data.tipe;
        document.getElementById('result-waktu').textContent = data.waktu;
        resultSection.classList.remove('d-none');
        emptySection.classList.add('d-none');
        successAlert.classList.remove('d-none');
        errorAlert.classList.add('d-none');
        resultMessage.textContent = data.pesan;
    }

    function showError(message) {
        document.getElementById('error-message').textContent = message;
        resultSection.classList.remove('d-none');
        emptySection.classList.add('d-none');
        successAlert.classList.add('d-none');
        errorAlert.classList.remove('d-none');
    }

    startBtn.addEventListener('click', async function() {
        try {
            const ndef = new NDEFReader();
            await ndef.scan();

            startBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin btn-icon-prepend"></i> Mendeteksi NFC...';
            startBtn.disabled = true;

            ndef.onreading = async function(event) {
                const serial = event.serialNumber;
                const tipeLog = document.querySelector('input[name="tipe_log"]:checked').value;

                try {
                    const response = await fetch('{{ route('nfc.scan.proses') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            serial_number: serial,
                            tipe_log: tipeLog
                        })
                    });

                    const data = await response.json();

                    if (data.status === 'ok') {
                        showResult(data.data);
                    } else {
                        showError(data.pesan);
                    }
                } catch (err) {
                    showError('Gagal mengirim data: ' + err.message);
                }

                startBtn.innerHTML = '<i class="mdi mdi-nfc-search-variant btn-icon-prepend"></i> Mulai Scan NFC';
                startBtn.disabled = false;
            };

            ndef.onreadingerror = function() {
                showError('Gagal membaca NFC. Coba dekatkan kartu ke perangkat.');
                startBtn.innerHTML = '<i class="mdi mdi-nfc-search-variant btn-icon-prepend"></i> Mulai Scan NFC';
                startBtn.disabled = false;
            };
        } catch (error) {
            alert('NFC tidak tersedia di perangkat ini: ' + error.message);
            startBtn.innerHTML = '<i class="mdi mdi-nfc-search-variant btn-icon-prepend"></i> Mulai Scan NFC';
            startBtn.disabled = false;
        }
    });
});
</script>
@endpush