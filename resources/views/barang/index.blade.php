@extends('layouts.app')

@section('style-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .page-title-icon { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .label-preview-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px; margin-top: 15px; background: #f3f3f3; padding: 8px; border-radius: 8px; }
    .preview-cell { aspect-ratio: 2/1; background: white; border: 1px solid #dee2e6; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #ced4da; height: 25px; }
    .preview-cell.start { background: #fe7c96; color: white; font-weight: bold; border-color: #fe7c96; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-tag-multiple"></i>
        </span> Master Barang UMKM
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <button type="button" class="btn btn-gradient-info btn-icon-text btn-sm me-2" id="btn-open-modal">
                    <i class="mdi mdi-printer btn-icon-prepend"></i> PDF 
                </button>
                <a href="{{ route('barang.create') }}" class="btn btn-gradient-primary btn-icon-text btn-sm">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Barang
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Kelola Stok & Label</h4>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="barang-table" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%"><input type="checkbox" id="check-all"></th>
                                <th> UID </th>
                                <th> Nama Barang </th>
                                <th> Harga </th>
                                <th class="text-end"> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $item)
                            <tr>
                                <td><input type="checkbox" value="{{ $item->id_barang }}" class="item-checkbox"></td>
                                <td><label class="badge badge-outline-dark">{{ $item->id_barang }}</label></td>
                                <td>{{ $item->nama }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('barang.edit', $item->id_barang) }}" class="btn btn-gradient-warning btn-sm btn-icon-text">
                                            Edit <i class="mdi mdi-pencil btn-icon-append"></i>
                                        </a>
                                        <button type="submit" class="btn btn-gradient-danger btn-sm btn-icon-text" onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                            Hapus <i class="mdi mdi-delete btn-icon-append"></i>
                                        </button>
                                    </form>
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

<!-- Modal Posisi Cetak -->
<div class="modal fade" id="modalCetak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="form-cetak" method="POST" action="{{ route('barang.cetakLabel') }}" target="_blank">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Konfigurasi Label</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Placeholder untuk ID Barang yang dipilih -->
                    <div id="id-container"></div>
                    
                    <div class="row">
                        <div class="col-6">
                            <label class="small">Kolom X</label>
                            <input type="number" name="start_x" id="input_x" class="form-control" value="1" min="1" max="5">
                        </div>
                        <div class="col-6">
                            <label class="small">Baris Y</label>
                            <input type="number" name="start_y" id="input_y" class="form-control" value="1" min="1" max="8">
                        </div>
                    </div>
                    <div class="label-preview-grid" id="preview-grid"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-gradient-primary w-100">Buka PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#barang-table').DataTable({ "language": { "search": "Cari:", "lengthMenu": "Tampilkan _MENU_" } });

        $('#check-all').click(function() { $('.item-checkbox').prop('checked', this.checked); });

        $('#btn-open-modal').click(function() {
            const selected = $('.item-checkbox:checked');
            if (selected.length === 0) { alert('Pilih minimal satu barang!'); return; }

            // Masukkan ID ke form cetak secara dinamis
            let html = '';
            selected.each(function() {
                html += `<input type="hidden" name="ids[]" value="${$(this).val()}">`;
            });
            $('#id-container').html(html);

            new bootstrap.Modal(document.getElementById('modalCetak')).show();
            renderPreview();
        });

        function renderPreview() {
            let x = parseInt($('#input_x').val()) || 1;
            let y = parseInt($('#input_y').val()) || 1;
            let grid = '';
            for (let r = 1; r <= 8; r++) {
                for (let c = 1; c <= 5; c++) {
                    let cls = (r === y && c === x) ? 'preview-cell start' : 'preview-cell';
                    grid += `<div class="${cls}">${(r === y && c === x) ? 'X' : ''}</div>`;
                }
            }
            $('#preview-grid').html(grid);
        }

        $('#input_x, #input_y').on('input change', renderPreview);
    });
</script>
@endsection