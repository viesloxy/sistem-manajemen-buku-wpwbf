@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-info text-white me-2">
            <i class="mdi mdi-camera"></i>
        </span> Tambah Customer 2 (File)
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer-data.index') }}">Customer</a></li>
            <li class="breadcrumb-item active">Tambah (File)</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ambil Foto dengan Kamera</h4>
                <p class="card-description">Foto akan disimpan sebagai file di storage, path disimpan dalam database</p>

                <form action="{{ route('customer-data.store-file') }}" method="POST"
                      enctype="multipart/form-data" id="formFile">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                               id="nama" name="nama" placeholder="Masukkan nama customer"
                               value="{{ old('nama') }}" required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="no_hp">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp"
                               placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="email@example.com" value="{{ old('email') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2"
                                  placeholder="Masukkan alamat">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="fotoInput">Foto Customer</label>
                        <input type="file" class="form-control" id="fotoInput" name="foto" accept="image/*">
                        <small class="text-muted">Format: JPEG, PNG, JPG, GIF. Maks: 2MB</small>
                        @error('foto')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gradient-info">
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
                <h4 class="card-title">Preview & Camera Capture</h4>

                <div class="text-center mb-3">
                    <video id="videoFile" width="100%" autoplay playsinline
                           style="border: 2px solid #ddd; border-radius: 8px; background: #000;"></video>
                </div>

                <canvas id="canvasFile" width="320" height="240" style="display: none;"></canvas>

                <div class="text-center">
                    <div id="previewContainerFile" class="mb-3" style="display: none;">
                        <img id="previewImageFile" width="100%"
                             style="border: 2px solid #28a745; border-radius: 8px;">
                        <p class="text-success mt-2 mb-0">
                            <i class="mdi mdi-check-circle"></i> Foto berhasil di-capture!
                        </p>
                    </div>

                    <button type="button" class="btn btn-success btn-lg me-2" id="btnCaptureFile">
                        <i class="mdi mdi-camera"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" id="btnResetFile" style="display: none;">
                        <i class="mdi mdi-refresh"></i> Ambil Ulang
                    </button>
                </div>

                <div id="cameraErrorFile" class="alert alert-warning mt-3" style="display: none;">
                    <i class="mdi mdi-alert"></i>
                    <span id="errorMessageFile">Tidak dapat mengakses kamera</span>
                </div>

                <p class="text-muted mt-3" style="font-size: 12px;">
                    <i class="mdi mdi-information"></i>
                    Foto disimpan sebagai file. Lebih hemat ruang database.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script>
$(document).ready(function() {
    const video = document.getElementById('videoFile');
    const canvas = document.getElementById('canvasFile');
    const previewContainer = document.getElementById('previewContainerFile');
    const previewImage = document.getElementById('previewImageFile');
    const btnCapture = document.getElementById('btnCaptureFile');
    const btnReset = document.getElementById('btnResetFile');
    const fotoInput = document.getElementById('fotoInput');
    const cameraError = document.getElementById('cameraErrorFile');

    let stream = null;
    let capturedData = null;

    // Start camera
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: 320, height: 240 }
            });
            video.srcObject = stream;
            cameraError.style.display = 'none';
        } catch (err) {
            console.error('Camera error:', err);
            cameraError.style.display = 'block';
            document.getElementById('errorMessageFile').textContent =
                'Tidak dapat mengakses kamera: ' + err.message;
        }
    }

    // Capture photo
    btnCapture.addEventListener('click', function() {
        if (!stream) {
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

        // Convert to blob/file
        canvas.toBlob(function(blob) {
            if (blob) {
                capturedData = blob;
                const file = new File([blob], 'camera_capture.jpg', {
                    type: 'image/jpeg',
                    lastModified: new Date().getTime()
                });

                // Create DataTransfer and set to input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fotoInput.files = dataTransfer.files;

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                    btnReset.style.display = 'inline-block';
                };
                reader.readAsDataURL(blob);
            }
        }, 'image/jpeg', 0.8);

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
        fotoInput.value = '';
        capturedData = null;
        startCamera();
        video.style.display = 'block';
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
