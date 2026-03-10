@extends('layouts.app')

@section('style-page')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .input-group-text {
        border-radius: 5px 0 0 5px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker"></i>
        </span> Wilayah Pengiriman
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftarkan Kota Baru</h4>
                <form id="formKota">
                    <div class="form-inline d-flex gap-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white">Kota:</span>
                            </div>
                            <input type="text" class="form-control" id="in_kota" name="in_kota" placeholder="Nama Kota" required>
                        </div>
                        <button type="button" class="btn btn-gradient-primary" id="btnTambahKota">
                            <span class="btn-text">Tambahkan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-header bg-white font-weight-bold">Select</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="sel_biasa">Select Kota :</label>
                    <select class="form-control border-dark" id="sel_biasa">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>
                <hr>
                <p>Kota Terpilih: <span id="txt_biasa" class="text-primary font-weight-bold">-</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-header bg-white font-weight-bold">select 2</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="sel_select2">Select Kota :</label>
                    <select class="form-control" id="sel_select2">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>
                <hr>
                <p>Kota Terpilih: <span id="txt_select2" class="text-success font-weight-bold">-</span></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#sel_select2').select2({ theme: 'bootstrap-5' });

    $('#btnTambahKota').on('click', function() {
        let inputKota = $('#in_kota');
        let kotaValue = inputKota.val();
        let btn = $(this);
        let form = document.getElementById('formKota');

        if (form.checkValidity()) {
            let originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Memproses...');

            setTimeout(() => {
                $('#sel_biasa').append(`<option value="${kotaValue}">${kotaValue}</option>`);

                let newOption = new Option(kotaValue, kotaValue, false, false);
                $('#sel_select2').append(newOption).trigger('change');

 
                btn.prop('disabled', false).html(originalHtml);
            }, 600);
        } else {
            form.reportValidity();
        }
    });

    $('#sel_biasa').on('change', function() {
        $('#txt_biasa').text($(this).val() || "-");
    });

    $('#sel_select2').on('change', function() {
        $('#txt_select2').text($(this).val() || "-");
    });
});
</script>
@endsection