{{-- resources/views/geolocation/barcode/print.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode - {{ $barcode->barcode }}</title>
    <style>
        @media print {
            .no-print { display: none; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .barcode-container {
            text-align: center;
            border: 2px solid #333;
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }
        .barcode-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .barcode-info {
            margin: 10px 0;
            font-size: 14px;
        }
        .barcode-image {
            margin: 20px 0;
        }
        .btn-print {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="barcode-container">
        <div class="barcode-title">Master Barcode Toko</div>

        <div class="barcode-info">
            <strong>Nama Toko:</strong> {{ $barcode->nama_toko }}
        </div>

        <div class="barcode-info">
            <strong>Kode Barcode:</strong> {{ $barcode->barcode }}
        </div>

        <div class="barcode-image">
            <svg id="barcode"></svg>
        </div>

        <div class="barcode-info">
            <strong>Lokasi:</strong><br>
            Lat: {{ $barcode->latitude }}, Lng: {{ $barcode->longitude }}<br>
            Accuracy: {{ $barcode->accuracy }} meter
        </div>

        <div class="barcode-info" style="font-size: 12px; color: #666;">
            Dicetak pada: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="no-print" style="text-align: center;">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Barcode
        </button>
        <button class="btn-print" style="background-color: #6c757d;" onclick="window.close()">
            Tutup
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        // Generate barcode menggunakan JsBarcode
        JsBarcode("#barcode", "{{ $barcode->barcode }}", {
            format: "CODE128",
            width: 2,
            height: 80,
            displayValue: true,
            fontSize: 16,
            margin: 10
        });
    </script>
</body>
</html>
