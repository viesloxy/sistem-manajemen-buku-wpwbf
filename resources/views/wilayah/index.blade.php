@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker-radius"></i>
        </span> Wilayah Administrasi
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Data Indonesia <i class="mdi mdi-check-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pilih Wilayah</h4>
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-gradient-primary text-white">
                                        <i class="mdi mdi-map-marker"></i>
                                    </span>
                                </div>
                                <select class="form-select border-primary" id="provinsi" style="height: 2.875rem;">
                                    <option value="0">-- Pilih Provinsi --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kabupaten">Kota / Kabupaten</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-gradient-primary text-white">
                                        <i class="mdi mdi-city"></i>
                                    </span>
                                </div>
                                <select class="form-select border-primary" id="kabupaten" disabled style="height: 2.875rem;">
                                    <option value="0">-- Pilih Kota --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-gradient-primary text-white">
                                        <i class="mdi mdi-home-modern"></i>
                                    </span>
                                </div>
                                <select class="form-select border-primary" id="kecamatan" disabled style="height: 2.875rem;">
                                    <option value="0">-- Pilih Kecamatan --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kelurahan">Kelurahan / Desa</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-gradient-primary text-white">
                                        <i class="mdi mdi-home-variant"></i>
                                    </span>
                                </div>
                                <select class="form-select border-primary" id="kelurahan" disabled style="height: 2.875rem;">
                                    <option value="0">-- Pilih Kelurahan --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="status-display" class="mt-4 p-3 bg-light rounded d-none border border-primary">
                    <p class="mb-1 text-muted"><i class="mdi mdi-information-outline"></i> Lokasi Terpilih:</p>
                    <h5 id="text-result" class="text-primary font-weight-bold mb-0"></h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
$(document).ready(function() {
    
    // 1. AJAX JQUERY - LOAD PROVINSI
    function loadProvinsi() {
        $.ajax({
            method: "GET",
            url: "{{ route('api.provinsi') }}",
            success: function(data) {
                let html = '<option value="0">-- Pilih Provinsi --</option>';
                data.forEach(function(item) {
                    html += `<option value="${item.id}">${item.name}</option>`;
                });
                $('#provinsi').html(html);
            }
        });
    }

    loadProvinsi();

    // 2. AXIOS - LOAD KABUPATEN
    $('#provinsi').on('change', function() {
        let id = $(this).val();
        resetDropdown(['#kabupaten', '#kecamatan', '#kelurahan']);
        
        if (id != "0") {
            axios.get(`/api-wilayah/kabupaten/${id}`)
                .then(function (response) {
                    let html = '<option value="0">-- Pilih Kota --</option>';
                    response.data.forEach(res => {
                        html += `<option value="${res.id}">${res.name}</option>`;
                    });
                    $('#kabupaten').html(html).removeAttr('disabled');
                    updateLabel();
                });
        } else {
            updateLabel();
        }
    });

    // 3. AXIOS - LOAD KECAMATAN
    $('#kabupaten').on('change', function() {
        let id = $(this).val();
        resetDropdown(['#kecamatan', '#kelurahan']);
        
        if (id != "0") {
            axios.get(`/api-wilayah/kecamatan/${id}`)
                .then(function (response) {
                    let html = '<option value="0">-- Pilih Kecamatan --</option>';
                    response.data.forEach(res => {
                        html += `<option value="${res.id}">${res.name}</option>`;
                    });
                    $('#kecamatan').html(html).removeAttr('disabled');
                    updateLabel();
                });
        } else {
            updateLabel();
        }
    });

    // 4. AXIOS - LOAD KELURAHAN
    $('#kecamatan').on('change', function() {
        let id = $(this).val();
        resetDropdown(['#kelurahan']);
        
        if (id != "0") {
            axios.get(`/api-wilayah/kelurahan/${id}`)
                .then(function (response) {
                    let html = '<option value="0">-- Pilih Kelurahan --</option>';
                    response.data.forEach(res => {
                        html += `<option value="${res.id}">${res.name}</option>`;
                    });
                    $('#kelurahan').html(html).removeAttr('disabled');
                    updateLabel();
                });
        } else {
            updateLabel();
        }
    });

    $('#kelurahan').on('change', function() {
        updateLabel();
    });

    function resetDropdown(ids) {
        ids.forEach(id => {
            let placeholder = "";
            if(id == '#kabupaten') placeholder = "Kota";
            else if(id == '#kecamatan') placeholder = "Kecamatan";
            else if(id == '#kelurahan') placeholder = "Kelurahan";
            
            $(id).html(`<option value="0">-- Pilih ${placeholder} --</option>`).attr('disabled', true);
        });
    }

    function updateLabel() {
        let p = $("#provinsi option:selected").val() != "0" ? $("#provinsi option:selected").text() : "";
        let k = $("#kabupaten option:selected").val() != "0" ? " > " + $("#kabupaten option:selected").text() : "";
        let c = $("#kecamatan option:selected").val() != "0" ? " > " + $("#kecamatan option:selected").text() : "";
        let l = $("#kelurahan option:selected").val() != "0" ? " > " + $("#kelurahan option:selected").text() : "";

        if(p != "") {
            $('#status-display').removeClass('d-none');
            $('#text-result').text(p + k + c + l);
        } else {
            $('#status-display').addClass('d-none');
        }
    }
});
</script>