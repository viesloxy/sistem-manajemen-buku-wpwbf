{{-- resources/views/vendor/nfc/scan.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-warning text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span> Scan NFC Vendor
    </h3>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">Scan Kartu NFC</h4>
                <p class="text-muted">Tempelkan kartu NFC ke perangkat untuk absensi vendor</p>

                <div id="nfc-reader-area" class="mt-4 mb-4 p-5 border rounded bg-light">
                    <i class="mdi mdi-nfc-search-variant" style="font-size: 5rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">Aktifkan NFC di perangkat Anda dan tempelkan kartu</p>
                </div>

                <div id="nfc-result" class="d-none">
                    <div class="alert alert-info">
                        <h5 id="result-status"></h5>
                        <p id="result-message"></p>
                        <small id="result-time"></small>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" id="btn-start-nfc" class="btn btn-gradient-warning btn-lg">
                        <i class="mdi mdi-nfc btn-icon-prepend"></i> Mulai Scan NFC
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnStart = document.getElementById('btn-start-nfc');

    btnStart.addEventListener('click', async function() {
        try {
            const ndef = new NDEFReader();
            await ndef.scan();

            const readerArea = document.getElementById('nfc-reader-area');
            readerArea.innerHTML = '<i class="mdi mdi-loading mdi-spin" style="font-size: 3rem; color: #ffc107;"></i><p class="mt-3 text-warning">Mendeteksi NFC...</p>';

            ndef.onreading = async function(event) {
                const serialNumber = event.serialNumber;
                readerArea.innerHTML = '<i class="mdi mdi-check-circle text-success" style="font-size: 4rem;"></i><p class="mt-3">NFC Terdeteksi!</p><code>' + serialNumber + '</code>';

                // Kirim ke server
                try {
                    const response = await fetch('/api/nfc/scan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ serial_number: serialNumber })
                    });
                    const data = await response.json();

                    const resultDiv = document.getElementById('nfc-result');
                    document.getElementById('result-status').textContent = data.success ? 'Berhasil!' : 'Gagal';
                    document.getElementById('result-message').textContent = data.message || '';
                    document.getElementById('result-time').textContent = 'Waktu: ' + new Date().toLocaleString();
                    resultDiv.classList.remove('d-none');
                } catch (error) {
                    console.error('Error:', error);
                }
            };

            ndef.onreadingerror = function() {
                readerArea.innerHTML = '<i class="mdi mdi-alert-circle text-danger" style="font-size: 4rem;"></i><p class="mt-3 text-danger">Gagal membaca NFC</p>';
            };
        } catch (error) {
            alert('NFC tidak tersedia: ' + error.message);
        }
    });
});
</script>
@endpush
@endsection