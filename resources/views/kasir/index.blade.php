@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-cash-register"></i>
        </span> Transaksi Kasir (POS)
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Checkout <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <!-- Form Input -->
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Data Barang</h4>
                <hr>
                <form id="formInputKasir">
                    <div class="form-group">
                        <label>Kode Barang <small class="text-primary">(Enter untuk cari)</small></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white"><i class="mdi mdi-barcode-scan"></i></span>
                            </div>
                            <input type="text" class="form-control border-primary" id="kode_barang" placeholder="Masukkan Kode..." autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control bg-light" id="nama_barang" readonly>
                    </div>

                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-gradient-primary text-white">Rp</span>
                            </div>
                            <input type="text" class="form-control bg-light" id="harga_barang" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jumlah (Qty)</label>
                        <input type="number" class="form-control border-primary" id="jumlah_barang" value="1" min="1">
                    </div>

                    <button type="button" class="btn btn-gradient-primary w-100 py-3" id="btnTambah" disabled>
                        <span class="btn-text">TAMBAHKAN</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Transaksi (Urutan: Kode, Nama, Harga, Jumlah, Subtotal) -->
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Transaksi</h4>
                <div class="table-responsive" style="min-height: 280px;">
                    <table class="table table-bordered table-hover" id="tblKasir">
                        <thead class="bg-light text-center font-weight-bold">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th width="40">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data JS -->
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Total Tagihan:</p>
                        <h2 class="text-primary font-weight-bold">Rp <span id="labelTotal">0</span></h2>
                    </div>
                    <button class="btn btn-gradient-success btn-lg px-5 py-3" id="btnBayar" disabled>
                        <span class="btn-text">BAYAR SEKARANG</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let keranjang = [];

    // 1. CARI BARANG
    function cariBarang() {
        let kode = $('#kode_barang').val().trim();
        if(!kode) return;

        axios.get(`/api-kasir/barang/${kode}`)
            .then(res => {
                let d = res.data.data;
                $('#nama_barang').val(d.nama);
                $('#harga_barang').val(d.harga);
                $('#jumlah_barang').val(1);
                $('#btnTambah').prop('disabled', false);
            })
            .catch(() => {
                Swal.fire('Oops!', 'Kode barang tidak terdaftar', 'warning');
                $('#nama_barang, #harga_barang').val('');
                $('#btnTambah').prop('disabled', true);
            });
    }

    $('#kode_barang').on('keypress', function(e) { if(e.which == 13) { e.preventDefault(); cariBarang(); } });

    // 2. TAMBAH KE TABEL (LOADER)
    $('#btnTambah').on('click', function() {
        let btn = $(this);
        let btnText = btn.find('.btn-text');
        let oldHtml = btnText.html();

        btn.prop('disabled', true);
        btnText.html('<i class="mdi mdi-loading mdi-spin me-1"></i> Memproses...');

        setTimeout(() => {
            let kode = $('#kode_barang').val();
            let harga = parseInt($('#harga_barang').val());
            let qty = parseInt($('#jumlah_barang').val());

            let idx = keranjang.findIndex(x => x.id_barang === kode);
            if(idx !== -1) {
                keranjang[idx].jumlah += qty;
                keranjang[idx].subtotal = keranjang[idx].jumlah * keranjang[idx].harga;
            } else {
                keranjang.push({
                    id_barang: kode,
                    nama: $('#nama_barang').val(),
                    harga: harga,
                    jumlah: qty,
                    subtotal: harga * qty
                });
            }
            render();
            clear();
            btnText.html(oldHtml);
        }, 400);
    });

    function render() {
        let html = '';
        let total = 0;
        keranjang.forEach((item, i) => {
            total += item.subtotal;
            html += `
                <tr>
                    <td class="text-center">${item.id_barang}</td>
                    <td>${item.nama}</td>
                    <td class="text-end">${item.harga.toLocaleString('id-ID')}</td>
                    <td class="text-center"><input type="number" class="form-control form-control-sm upd-qty text-center" data-i="${i}" value="${item.jumlah}" min="1"></td>
                    <td class="text-end font-weight-bold">${item.subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-center"><button class="btn btn-sm btn-link text-danger btn-del" data-i="${i}"><i class="mdi mdi-delete-variant"></i></button></td>
                </tr>`;
        });
        $('#tblKasir tbody').html(html || '<tr><td colspan="6" class="text-center text-muted">Keranjang Kosong</td></tr>');
        $('#labelTotal').text(total.toLocaleString('id-ID'));
        $('#btnBayar').prop('disabled', keranjang.length === 0);
    }

    $(document).on('change', '.upd-qty', function() {
        let i = $(this).data('i');
        let v = parseInt($(this).val()) || 1;
        keranjang[i].jumlah = v;
        keranjang[i].subtotal = keranjang[i].harga * v;
        render();
    });

    $(document).on('click', '.btn-del', function() {
        keranjang.splice($(this).data('i'), 1);
        render();
    });

    function clear() {
        $('#kode_barang').val('').focus();
        $('#nama_barang, #harga_barang').val('');
        $('#btnTambah').prop('disabled', true);
    }

    // 3. BAYAR (LOADER)
    $('#btnBayar').on('click', function() {
        let btn = $(this);
        let btnText = btn.find('.btn-text');
        let oldHtml = btnText.html();
        let totalVal = parseInt($('#labelTotal').text().replace(/\./g, ''));

        Swal.fire({
            title: 'Konfirmasi Bayar',
            text: "Simpan transaksi?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.prop('disabled', true);
                btnText.html('<i class="mdi mdi-loading mdi-spin me-1"></i> Menyimpan...');

                setTimeout(() => {
                    axios.post("{{ route('api.kasir.simpan') }}", {
                        total: totalVal,
                        items: keranjang,
                        _token: "{{ csrf_token() }}"
                    })
                    .then(() => {
                        Swal.fire('Sukses!', 'Transaksi berhasil disimpan.', 'success');
                        keranjang = []; render(); clear();
                    })
                    .catch(() => Swal.fire('Error', 'Gagal simpan database.', 'error'))
                    .finally(() => { btn.prop('disabled', false); btnText.html(oldHtml); });
                }, 800);
            }
        });
    });
});
</script>
@endsection