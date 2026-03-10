@extends('layouts.app')

@section('style-page')
<style>
    /* Task 3.a: Pointer saat hover row */
    #tabelProduk tbody tr { 
        cursor: pointer; 
        transition: 0.2s;
    }
    #tabelProduk tbody tr:hover { 
        background-color: rgba(182, 109, 255, 0.1) !important; 
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-layers"></i>
        </span> Simulasi Produk (HTML Table)
    </h3>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Produk</h4>
                <form id="formProduk">
                    <div class="form-group">
                        <label for="nama_barang">Nama barang :</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama" required>
                    </div>
                    <div class="form-group">
                        <label for="harga_barang">Harga barang :</label>
                        <input type="number" class="form-control" id="harga_barang" name="harga_barang" placeholder="Masukkan harga" required>
                    </div>
                    <button type="button" class="btn btn-gradient-primary w-100" id="btnSubmit">
                        <span class="btn-text">submit</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Produk (Klik baris untuk Edit/Hapus)</h4>
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="tabelProduk">
                        <thead>
                            <tr class="bg-light font-weight-bold">
                                <th width="30%">ID barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="empty-row">
                                <td colspan="3" class="text-center text-muted italic">Belum ada data. Silakan input di form kiri.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAction" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit / Hapus Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formModal">
                    <div class="form-group">
                        <label>ID barang :</label>
                        <input type="text" class="form-control" id="modal_id" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama barang :</label>
                        <input type="text" class="form-control" id="modal_nama" name="edit_nama" required>
                    </div>
                    <div class="form-group">
                        <label>Harga barang :</label>
                        <input type="number" class="form-control" id="modal_harga" name="edit_harga" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm px-4" id="btnHapus">
                    <span class="btn-text">Hapus</span>
                </button>
                <button type="button" class="btn btn-success btn-sm px-4" id="btnUbah">
                    <span class="btn-text">Ubah</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    let counter = 1;
    let selectedRow = null;

    $('#btnSubmit').on('click', function() {
        let formElement = document.getElementById('formProduk');
        let btn = $(this);

        if (formElement.checkValidity()) {
            let nama = $('#nama_barang').val();
            let harga = $('#harga_barang').val();
            
            let originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Memproses...');

            setTimeout(function() {
                $('#empty-row').remove();
                let id = "BRG-" + Math.floor(Math.random() * 1000) + counter;
                let newRow = `
                    <tr>
                        <td class="col-id">${id}</td>
                        <td class="col-nama">${nama}</td>
                        <td class="col-harga">Rp ${Number(harga).toLocaleString('id-ID')}</td>
                    </tr>
                `;
                $('#tabelProduk tbody').append(newRow);
                formElement.reset();
                btn.prop('disabled', false).html(originalHtml);
                counter++;
            }, 600);
        } else {
            formElement.reportValidity();
        }
    });

    $('#tabelProduk tbody').on('click', 'tr', function() {
        if ($(this).attr('id') === 'empty-row') return;
        selectedRow = $(this);
        let id = selectedRow.find('.col-id').text();
        let nama = selectedRow.find('.col-nama').text();
        let harga = selectedRow.find('.col-harga').text().replace(/[^0-9]/g, '');

        $('#modal_id').val(id);
        $('#modal_nama').val(nama);
        $('#modal_harga').val(harga);

        let modalAction = new bootstrap.Modal(document.getElementById('modalAction'));
        modalAction.show();
    });

    $('#btnHapus').on('click', function() {
        let btn = $(this);
        let originalHtml = btn.html();
        btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i>...');

        setTimeout(() => {
            if (selectedRow) {
                selectedRow.remove();
                if ($('#tabelProduk tbody tr').length === 0) {
                    $('#tabelProduk tbody').append('<tr id="empty-row"><td colspan="3" class="text-center text-muted italic">Belum ada data. Silakan input di form kiri.</td></tr>');
                }
                bootstrap.Modal.getInstance(document.getElementById('modalAction')).hide();
                btn.prop('disabled', false).html(originalHtml);
            }
        }, 500);
    });

    $('#btnUbah').on('click', function() {
        let modalForm = document.getElementById('formModal');
        let btn = $(this);
        
        if (modalForm.checkValidity()) {
            let originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i>...');

            setTimeout(() => {
                let namaBaru = $('#modal_nama').val();
                let hargaBaru = $('#modal_harga').val();

                selectedRow.find('.col-nama').text(namaBaru);
                selectedRow.find('.col-harga').text("Rp " + Number(hargaBaru).toLocaleString('id-ID'));

                bootstrap.Modal.getInstance(document.getElementById('modalAction')).hide();
                btn.prop('disabled', false).html(originalHtml);
            }, 600);
        } else {
            modalForm.reportValidity();
        }
    });
});
</script>
@endsection