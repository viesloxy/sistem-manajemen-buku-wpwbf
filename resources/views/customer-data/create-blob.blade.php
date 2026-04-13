@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-camera"></i>
        </span> Tambah Customer 1 (Blob)
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer-data.index') }}">Customer</a></li>
            <li class="breadcrumb-item active">Tambah (Blob)</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ambil Foto dengan Kamera</h4>
                <p class="card-description">Foto akan disimpan sebagai data Blob (Base64) dalam database</p>

                <form action="{{ route('customer-data.store-blob') }}" method="POST" id="formBlob">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama"
                               placeholder="Masukkan nama customer" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="no_hp">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp"
                               placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="email@example.com">
                    </div>

                    <div class="form-group mb-3">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2"
                                  placeholder="Masukkan alamat"></textarea>
                    </div>

                    <input type="hidden" name="foto" id="fotoBlob">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gradient-primary" id="btnSimpanBlob" disabled>
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                        <a href="{{ route('customer-data.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Preview Foto</h4>

                <div class="text-center mb-3">
                    <video id="video" width="100%" autoplay playsinline
                           style="border: 2px solid #ddd; border-radius: 8px; background: #000;"></video>
                </div>

                <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>

                <div class="text-center">
                    <div id="previewContainer" class="mb-3" style="display: none;">
                        <img id="previewImage" width="100%"
                             style="border: 2px solid #28a745; border-radius: 8px;">
                        <p class="text-success mt-2 mb-0">
                            <i class="mdi mdi-check-circle"></i> Foto berhasil di-capture!
                        </p>
                    </div>

                    <button type="button" class="btn btn-success btn-lg me-2" id="btnCapture">
                        <i class="mdi mdi-camera"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" id="btnReset" style="display: none;">
                        <i class="mdi mdi-refresh"></i> Ambil Ulang
                    </button>
                </div>

                <div id="cameraError" class="alert alert-warning mt-3" style="display: none;">
                    <i class="mdi mdi-alert"></i>
                    <span id="errorMessage">Tidak dapat mengakses kamera</span>
                </div>

                <p class="text-muted mt-3" style="font-size: 12px;">
                    <i class="mdi mdi-information"></i>
                    Foto disimpan dalam format Base64. Cocok untuk data kecil.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script>
$(document).ready(function() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');
    const btnCapture = document.getElementById('btnCapture');
    const btnReset = document.getElementById('btnReset');
    const fotoBlob = document.getElementById('fotoBlob');
    const btnSimpan = document.getElementById('btnSimpanBlob');
    const cameraError = document.getElementById('cameraError');

    let stream = null;

    // Start camera
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: 320, height: 240 }
            });
            video.srcObject = stream;
            cameraError.style.display = 'none';
        } catch (err) {
            console.error('Camera error:', err);
            cameraError.style.display = 'block';
            document.getElementById('errorMessage').textContent =
                'Tidak dapat mengakses kamera: ' + err.message;
        }
    }

    // Capture photo
    btnCapture.addEventListener('click', function() {
        if (!stream) {
            // Try to restart camera
            startCamera().then(() => {
                if (stream) {
                    captureFrame();
                }
            });
            return;
        }
        captureFrame();
    });

    function captureFrame() {
        canvas.width = video.videoWidth || 320;
        canvas.height = video.videoHeight || 240;

        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataURL = canvas.toDataURL('image/jpeg', 0.8);
        fotoBlob.value = dataURL;

        previewImage.src = dataURL;
        previewContainer.style.display = 'block';
        btnReset.style.display = 'inline-block';
        btnSimpan.disabled = false;

        // Stop video
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.style.display = 'none';
    }

    // Reset
    btnReset.addEventListener('click', function() {
        previewContainer.style.display = 'none';
        btnReset.style.display = 'none';
        fotoBlob.value = '';
        btnSimpan.disabled = true;
        startCamera();
        video.style.display = 'block';
    });

    // Form submit
    $('#formBlob').on('submit', function(e) {
        if (!$('#fotoBlob').val()) {
            e.preventDefault();
            alert('Ambil foto terlebih dahulu!');
            return false;
        }
    });

    // Init
    startCamera();

    // Cleanup on page leave
    window.addEventListener('beforeunload', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
});
</script>
@endsection
