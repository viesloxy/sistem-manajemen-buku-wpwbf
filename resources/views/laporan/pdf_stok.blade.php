<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Resmi</title>
    <style>
        @page { size: a4 portrait; margin: 1.5cm; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; line-height: 1.4; }
        .header-kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header-kop h2 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header-kop p { margin: 2px; font-size: 10pt; }
        .title { text-align: center; font-weight: bold; font-size: 14pt; margin-bottom: 20px; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 6px; }
        th { background-color: #f2f2f2; text-align: center; }
        .signature { margin-top: 50px; float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>
    <div class="header-kop">
        <h2>Perpustakaan Universitas Airlangga</h2>
        <p>Jl. Mulyorejo, Kampus C UNAIR, Surabaya 60115</p>
        <p>Email: info@lib.unair.ac.id | Website: lib.unair.ac.id</p>
    </div>

    <div class="title">LAPORAN DATA INVENTARIS BUKU</div>

    <p>Dicetak oleh: <strong>{{ Auth::user()->name }}</strong><br>
    Tanggal Cetak: {{ date('d F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th>Judul Buku</th>
                <th width="25%">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataBuku as $index => $buku)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $buku->kode }}</td>
                <td>{{ $buku->judul }}</td>
                <td>{{ $buku->kategori->nama_kategori }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        Surabaya, {{ date('d F Y') }}<br>
        Kepala Bagian Arsip,
        <br><br><br><br>
        <strong>__________________________</strong><br>
        NIP. 198605122015043101
    </div>
</body>
</html>