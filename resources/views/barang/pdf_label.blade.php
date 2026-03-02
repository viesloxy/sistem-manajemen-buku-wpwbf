<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            size: 21cm 17cm;
            margin: 0;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 21cm;
            height: 17cm;
            font-family: 'Helvetica', sans-serif;
            background-color: #fff;
        }

        /* Tabel Utama */
        .main-table {
            width: 21cm;
            border-collapse: collapse;
            table-layout: fixed; /* Kunci lebar kolom agar tidak bergeser */
            margin-top: 0.3cm;   /* Top Margin */
            margin-left: 0.3cm;  /* Side Margin */
        }

        /* Sel untuk Pitch (Ruang total per label termasuk celah) */
        .pitch-cell {
            width: 4.1cm;        /* Horizontal Pitch */
            height: 2.0cm;       /* Vertical Pitch */
            padding: 0;
            margin: 0;
            vertical-align: top;
            overflow: hidden;
        }

        /* Kotak Label Asli (3.8cm x 1.8cm) */
        .label-content {
            width: 3.8cm;
            height: 1.8cm;
            text-align: center;
            box-sizing: border-box;
            /* Border tipis untuk membantu saat testing, hapus jika sudah fix */
            border: 0.1pt solid #f0f0f0; 
            padding-top: 5px;
            overflow: hidden;
        }

        /* Styling teks */
        .item-name {
            font-size: 7.5pt;
            font-weight: bold;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            margin-bottom: 1px;
            padding: 0 3px;
        }

        .line {
            border-top: 0.5pt solid #000;
            width: 80%;
            margin: 1px auto;
        }

        .item-price {
            font-size: 10pt;
            font-weight: bold;
            display: block;
            margin-top: 2px;
        }

        .item-id {
            font-size: 6pt;
            color: #555;
            display: block;
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