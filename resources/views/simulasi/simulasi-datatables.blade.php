@extends('layouts.app')

@section('style-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* Task 3.a: Mouse berubah menjadi pointer saat hover baris tabel */
    #tabelDT tbody tr { cursor: pointer; transition: 0.2s; }
    #tabelDT tbody tr:hover { background-color: rgba(182, 109, 255, 0.1) !important; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-table-large"></i>
        </span> Simulasi Produk (DataTables)
    </h3>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Input DT</h4>
                <form id="formDT">
                    <div class="form-group">
                        <label for="dt_nama">Nama barang :</label>
                        <input type="text" class="form-control" id="dt_nama" name="dt_nama" placeholder="Nama produk" required>
                    </div>
                    <div class="form-group">
                        <label for="dt_harga">Harga barang :</label>
                        <input type="number" class="form-control" id="dt_harga" name="dt_harga" placeholder="Harga produk" required>
                    </div>
                    <button type="button" class="btn btn-gradient-primary w-100" id="btnSubmitDT">
                        <span class="btn-text">submit</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Produk DT (Klik baris untuk Edit/Hapus)</h4>
                <div class="table-responsive">
                    <table class="table table-striped" id="tabelDT">
                        <thead>
                            <tr>
                                <th>ID barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDT" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit / Hapus Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formModalDT">
                    <div class="form-group">
                        <label>ID barang :</label>
                        <input type="text" class="form-control" id="m_id" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama barang :</label>
                        <input type="text" class="form-control" id="m_nama" name="m_nama" required>
                    </div>
                    <div class="form-group">
                        <label>Harga barang :</label>
                        <input type="number" class="form-control" id="m_harga" name="m_harga" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm px-4" id="btnHapusDT">
                    <span class="btn-text">Hapus</span>
                </button>
                <button type="button" class="btn btn-success btn-sm px-4" id="btnUbahDT">
                    <span class="btn-text">Ubah</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    let apiTabel = $('#tabelDT').DataTable({ language: { search: "Cari:" } });
    let indexTerpilih = null;

    $('#btnSubmitDT').click(function() {
        const formElement = document.getElementById('formDT');
        const btn = $(this);

        if (formElement.checkValidity()) {
            let nama = $('#dt_nama').val();
            let hargaValue = $('#dt_harga').val();
            let id = "DT-" + Math.floor(Math.random() * 1000);
            let hargaFormatted = "Rp " + Number(hargaValue).toLocaleString('id-ID');

            const originalContent = btn.html();
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Memproses...');
            
            setTimeout(() => {
                apiTabel.row.add([id, nama, hargaFormatted]).draw();
                formElement.reset();
                btn.prop('disabled', false).html(originalContent);
            }, 800);
        } else {
            formElement.reportValidity();
        }
    });

    $('#tabelDT tbody').on('click', 'tr', function() {
        if (apiTabel.rows().count() === 0) return;
        indexTerpilih = apiTabel.row(this).index();
        let dataBaris = apiTabel.row(this).data();

        $('#m_id').val(dataBaris[0]);
        $('#m_nama').val(dataBaris[1]);
        $('#m_harga').val(dataBaris[2].replace(/[^0-9]/g, ''));
        
        new bootstrap.Modal(document.getElementById('modalDT')).show();
    });

    $('#btnUbahDT').click(function() {
        const formModal = document.getElementById('formModalDT');
        const btn = $(this);

        if (formModal.checkValidity()) {
            const originalContent = btn.html();
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i>...');

            setTimeout(() => {
                let id = $('#m_id').val();
                let nama = $('#m_nama').val();
                let harga = "Rp " + Number($('#m_harga').val()).toLocaleString('id-ID');
                
                apiTabel.row(indexTerpilih).data([id, nama, harga]).draw();
                btn.prop('disabled', false).html(originalContent);
                bootstrap.Modal.getInstance(document.getElementById('modalDT')).hide();
            }, 600);
        } else {
            formModal.reportValidity();
        }
    });

    $('#btnHapusDT').click(function() {
        const btn = $(this);
        const originalContent = btn.html();
        btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i>...');

        setTimeout(() => {
            apiTabel.row(indexTerpilih).remove().draw();
            btn.prop('disabled', false).html(originalContent);
            bootstrap.Modal.getInstance(document.getElementById('modalDT')).hide();
        }, 500);
    });
});
</script>
@endsection