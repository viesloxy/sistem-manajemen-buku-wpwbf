<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pesanan - Kantin Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 50%, #a855f7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .verify-card {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
            text-align: center;
        }
        .verify-icon {
            width: 90px; height: 90px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .verify-icon.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        }
        .verify-icon.error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
        }
        .verify-icon i {
            font-size: 3rem;
        }
        .verify-icon.success i { color: #059669; }
        .verify-icon.error i { color: #dc2626; }

        .verify-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e1b4b;
            margin-bottom: .5rem;
        }
        .verify-sub {
            font-size: .9rem;
            color: #6b7280;
            margin-bottom: 2rem;
        }

        .order-detail {
            background: #faf8ff;
            border: 1px solid #e8dfff;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .order-detail-row {
            display: flex;
            justify-content: space-between;
            padding: .6rem 0;
            border-bottom: 1px dashed #e8dfff;
        }
        .order-detail-row:last-child { border-bottom: none; }
        .order-detail-label {
            font-size: .82rem;
            color: #6b7280;
            font-weight: 500;
        }
        .order-detail-value {
            font-size: .85rem;
            color: #1e1b4b;
            font-weight: 700;
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: .3rem 1rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .status-badge.lunas {
            background: #d1fae5;
            color: #059669;
        }
        .status-badge.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .items-list {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e8dfff;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: .4rem 0;
            font-size: .82rem;
        }
        .item-row .item-name { color: #374151; }
        .item-row .item-qty { color: #9ca3af; }
        .item-row .item-price { color: #1e1b4b; font-weight: 600; }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: linear-gradient(135deg, #6d28d9, #9a55ff);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: .85rem;
            padding: .85rem 2rem;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            cursor: pointer;
            transition: all .25s ease;
            box-shadow: 0 6px 20px rgba(154,85,255,.4);
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(154,85,255,.5);
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="verify-card">
        @if($status === 'success' && $pesanan)
            <div class="verify-icon success">
                <i class="mdi mdi-check-circle"></i>
            </div>
            <h2 class="verify-title">Pesanan Ditemukan!</h2>
            <p class="verify-sub">Detail pesanan #{{ $pesanan->id }}</p>

            <div class="order-detail">
                <div class="order-detail-row">
                    <span class="order-detail-label">ID Pesanan</span>
                    <span class="order-detail-value">#{{ $pesanan->id }}</span>
                </div>
                <div class="order-detail-row">
                    <span class="order-detail-label">Nama Pemesan</span>
                    <span class="order-detail-value">{{ $pesanan->nama }}</span>
                </div>
                <div class="order-detail-row">
                    <span class="order-detail-label">Total Bayar</span>
                    <span class="order-detail-value">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                </div>
                <div class="order-detail-row">
                    <span class="order-detail-label">Metode Bayar</span>
                    <span class="order-detail-value">{{ strtoupper($pesanan->metode_bayar ?? '-') }}</span>
                </div>
                <div class="order-detail-row">
                    <span class="order-detail-label">Status</span>
                    <span class="order-detail-value">
                        <span class="status-badge {{ strtolower($pesanan->status_bayar) }}">
                            {{ $pesanan->status_bayar }}
                        </span>
                    </span>
                </div>

                @if($pesanan->details && $pesanan->details->count() > 0)
                <div class="items-list">
                    <div class="item-row" style="font-weight:700; color:#6d28d9; margin-bottom:.5rem;">
                        <span>Item Pesanan</span>
                    </div>
                    @foreach($pesanan->details as $detail)
                    <div class="item-row">
                        <span class="item-name">{{ $detail->menu->nama_menu ?? 'Menu' }}</span>
                        <span class="item-qty">×{{ $detail->jumlah }}</span>
                        <span class="item-price">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <a href="{{ route('customer.pos') }}" class="btn-back">
                <i class="mdi mdi-arrow-left"></i> Kembali ke Beranda
            </a>
        @else
            <div class="verify-icon error">
                <i class="mdi mdi-alert-circle"></i>
            </div>
            <h2 class="verify-title">Pesanan Tidak Ditemukan</h2>
            <p class="verify-sub">{{ $message ?? 'ID pesanan tidak valid atau sudah dihapus.' }}</p>

            <a href="{{ route('customer.pos') }}" class="btn-back">
                <i class="mdi mdi-arrow-left"></i> Kembali ke Beranda
            </a>
        @endif
    </div>
</body>
</html>
