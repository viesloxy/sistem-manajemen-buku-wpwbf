<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Pesanan #{{ $pesanan_id }} - Kantin Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 50%, #a855f7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .qr-page-card {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
            text-align: center;
        }
        .qr-header {
            margin-bottom: 1.5rem;
        }
        .qr-header i {
            font-size: 3rem;
            color: #9a55ff;
        }
        .qr-header h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e1b4b;
            margin-top: .5rem;
        }
        .qr-header p {
            color: #6b7280;
            font-size: .9rem;
            margin-top: .25rem;
        }
        .qr-display {
            background: #faf8ff;
            border: 2px solid #e8dfff;
            border-radius: 16px;
            padding: 1.5rem;
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        .qr-display img {
            width: 250px;
            height: 250px;
            display: block;
        }
        .qr-info {
            background: #f5f0ff;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .qr-info h4 {
            color: #6d28d9;
            font-weight: 800;
        }
        .qr-info p {
            color: #6b7280;
            font-size: .85rem;
            margin-top: .25rem;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: linear-gradient(135deg, #6d28d9, #9a55ff);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: .9rem;
            padding: .85rem 2rem;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            cursor: pointer;
            transition: all .25s ease;
            box-shadow: 0 6px 20px rgba(154,85,255,.4);
            margin-right: .5rem;
            margin-bottom: .5rem;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(154,85,255,.5);
            color: #fff;
        }
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: #fff;
            color: #6d28d9;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: .9rem;
            padding: .85rem 1.5rem;
            border: 2px solid #e8dfff;
            border-radius: 12px;
            cursor: pointer;
            transition: all .25s ease;
        }
        .btn-print:hover {
            background: #f5f0ff;
            transform: translateY(-2px);
        }
        .qr-note {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: #ede9fe;
            color: #6d28d9;
            font-size: .8rem;
            padding: .6rem 1rem;
            border-radius: 999px;
            margin-top: 1rem;
        }
        .qr-note i {
            font-size: 1rem;
        }
        @media print {
            body { background: #fff; }
            .qr-page-card { box-shadow: none; border: 1px solid #ddd; }
            .btn-home, .btn-print, .qr-note { display: none; }
        }
    </style>
</head>
<body>
    <div class="qr-page-card">
        <div class="qr-header">
            <i class="mdi mdi-qrcode-scan"></i>
            <h2>QR Code Pesanan</h2>
            <p>Tunjukkan QR Code ini ke vendor saat pengambilan pesanan</p>
        </div>

        <div class="qr-display">
            <img src="{{ route('customer.qrcode', $pesanan_id) }}" alt="QR Code Pesanan #{{ $pesanan_id }}">
        </div>

        <div class="qr-info">
            <h4>Pesanan #{{ $pesanan_id }}</h4>
            <p>Tunjukkan ini ke vendor untuk verifikasi pesanan</p>
        </div>

        <div class="d-flex justify-content-center flex-wrap">
            <a href="{{ route('customer.pos') }}" class="btn-home">
                <i class="mdi mdi-home"></i> Kembali ke Beranda
            </a>
            <button onclick="window.print()" class="btn-print">
                <i class="mdi mdi-printer"></i> Cetak QR
            </button>
        </div>

        <div class="qr-note">
            <i class="mdi mdi-information"></i>
            <span>Simpan QR Code ini untuk verifikasi pesanan saat pengambilan</span>
        </div>
    </div>
</body>
</html>