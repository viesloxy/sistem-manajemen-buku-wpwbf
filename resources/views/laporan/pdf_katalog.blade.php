<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Katalog Buku - Purple Premium</title>
    <style>
        @page { 
            size: a4 landscape; 
            margin: 0; 
        }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: #f4f7fa;
            color: #333;
        }

        .header-banner {
            background-color: #9a55ff;
            background-image: linear-gradient(to right, #da8cff, #9a55ff);
            color: #ffffff;
            padding: 40px 60px;
            text-align: left;
        }

        .header-banner h1 {
            margin: 0;
            padding: 0;
            font-size: 34pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #ffffff !important; /* Memastikan teks tetap putih */
        }

        .header-banner p {
            margin: 8px 0 0 0;
            font-size: 14pt;
            color: #ffffff !important;
            opacity: 0.9;
        }

        .content-wrapper {
            padding: 30px 50px;
        }

        .data-card {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 25px;
            border: 1px solid #dee2e6;
            min-height: 400px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom: 3px solid #9a55ff;
            color: #495057;
            font-size: 10pt;
            text-transform: uppercase;
        }

        tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 11pt;
            color: #3e4b5b;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .code-tag {
            background-color: #9a55ff;
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 4px;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            font-size: 10pt;
        }

        .book-title {
            font-weight: bold;
            color: #111111;
        }

        .category-name {
            color: #9a55ff;
            font-weight: bold;
        }

        .footer-banner {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eeeeee;
        }

        .footer-left {
            float: left;
            font-size: 9pt;
            color: #6c757d;
        }

        .footer-right {
            float: right;
            font-size: 11pt;
            font-weight: bold;
            color: #9a55ff;
            letter-spacing: 1px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="header-banner">
        <h1>Katalog Buku</h1>
        <p>Koleksi Perpustakaan Digital &bull; Tahun {{ date('Y') }}</p>
    </div>

    <div class="content-wrapper">
        <div class="data-card">
            <table>
                <thead>
                    <tr>
                        <th width="15%">KODE</th>
                        <th width="40%">JUDUL BUKU</th>
                        <th width="25%">PENGARANG</th>
                        <th width="20%">KATEGORI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataBuku as $buku)
                    <tr>
                        <td>
                            <span class="code-tag">{{ $buku->kode }}</span>
                        </td>
                        <td>
                            <span class="book-title">{{ $buku->judul }}</span>
                        </td>
                        <td>{{ $buku->pengarang }}</td>
                        <td>
                            <span class="category-name">{{ $buku->kategori->nama_kategori }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="footer-banner clearfix">
                <div class="footer-left">
                    Dicetak secara otomatis pada {{ date('d F Y, H:i') }}
                </div>
                <div class="footer-right">
                    VITO ADITYA COLLECTION
                </div>
            </div>
        </div>
    </div>

</body>
</html>