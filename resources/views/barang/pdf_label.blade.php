<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            /* Kertas TnJ 108: 21cm x 17cm */
            size: 210mm 170mm;
            margin: 0;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 170mm;
            font-family: 'Helvetica', sans-serif;
            background-color: #fff;
        }

        /* Tabel Utama */
        .main-table {
            /* 5 kolom x 41mm (pitch) = 205mm. Sisa 5mm dibagi untuk margin kiri 3mm & kanan 2mm */
            width: 205mm; 
            border-collapse: collapse;
            table-layout: fixed; 
            margin-top: 3mm;   /* Top Margin 0.3cm */
            margin-left: 3mm;  /* Side Margin 0.3cm */
            border: none;
        }

        .pitch-cell {
            width: 41mm;        /* Horizontal Pitch 4.1cm */
            height: 20mm;       /* Vertical Pitch 2.0cm */
            padding: 0;
            margin: 0;
            vertical-align: top;
            overflow: hidden;
            border: none;
        }

        /* Kotak Label Asli (3.8cm x 1.8cm) */
        .label-content {
            width: 38mm;
            height: 18mm;
            text-align: center;
            box-sizing: border-box;
            /* Border sangat tipis hanya untuk panduan, bisa dihapus jika sudah yakin */
            border: 0.1pt solid #f0f0f0; 
            padding-top: 1.5mm;
            overflow: hidden;
        }

        /* Styling teks agar pas di kotak kecil */
        .item-name {
            font-size: 7.5pt;
            font-weight: bold;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            margin-bottom: 0.5mm;
            padding: 0 2mm;
            color: #000;
        }

        .line {
            border-top: 0.5pt solid #000;
            width: 85%;
            margin: 0.5mm auto;
        }

        .item-price {
            font-size: 10pt;
            font-weight: bold;
            display: block;
            margin-top: 1mm;
            color: #000;
        }

        .item-id {
            font-size: 6pt;
            color: #555;
            display: block;
            margin-top: 0.5mm;
        }
    </style>
</head>
<body>
    <table class="main-table">
        @foreach($rows as $row)
        <tr>
            @foreach($row as $item)
            <td class="pitch-cell">
                @if($item)
                <div class="label-content">
                    <div class="item-name">{{ strtoupper(substr($item->nama, 0, 24)) }}</div>
                    <div class="line"></div>
                    <div class="item-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                    <div class="item-id">{{ $item->id_barang }}</div>
                </div>
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </table>
</body>
</html>