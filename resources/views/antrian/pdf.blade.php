<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomor Antrian {{ $antrian->nomor }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            background: #fff;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            border: 3px solid #3b5998;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        .header {
            background: #3b5998;
            color: #fff;
            padding: 15px;
            margin: -20px -20px 20px;
            border-radius: 9px 9px 0 0;
        }
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        .nomor-box {
            background: #fef3c7;
            border: 3px dashed #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        .nomor-label {
            font-size: 11px;
            font-weight: bold;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .nomor-besar {
            font-size: 72px;
            font-weight: bold;
            color: #1e40af;
            line-height: 1;
            letter-spacing: 4px;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table td:first-child {
            font-weight: bold;
            color: #6b7280;
            text-align: left;
            width: 40%;
        }
        .info-table td:last-child {
            color: #1e1b4b;
            text-align: right;
        }
        .instruksi {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 12px;
            font-size: 11px;
            color: #1e40af;
            line-height: 1.5;
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

<div class="container">
    {{-- Header --}}
    <div class="header">
        <h1>KANTIN BUKU</h1>
        <p>Sistem Antrian Digital</p>
    </div>

    {{-- Nomor Antrian --}}
    <div class="nomor-box">
        <div class="nomor-label">Nomor Antrian Anda</div>
        <div class="nomor-besar">{{ str_pad($antrian->nomor, 3, '0', STR_PAD_LEFT) }}</div>
    </div>

    {{-- Info Table --}}
    <table class="info-table">
        <tr>
            <td>Nama</td>
            <td>{{ $antrian->nama }}</td>
        </tr>
        <tr>
            <td>Vendor</td>
            <td>{{ $antrian->vendor->nama_vendor ?? '-' }}</td>
        </tr>
        <tr>
            <td>Waktu Daftar</td>
            <td>{{ $antrian->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    {{-- Instruksi --}}
    <div class="instruksi">
        Harap menunggu. Nomor akan dipanggil melalui pengeras suara dan papan antrian. Pastikan Anda berada di area tunggu.
    </div>

    {{-- Footer --}}
    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</div>

</body>
</html>
