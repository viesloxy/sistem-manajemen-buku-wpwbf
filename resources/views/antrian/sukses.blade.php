<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil — Kantin Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ══ DESIGN TOKENS ══ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --pa-purple:   #9a55ff;
            --pa-purple2:  #da8cff;
            --pa-dark:     #5b21b6;
            --pa-gold:     #fbbf24;
            --pa-green:    #10b981;
            --ease-spring: cubic-bezier(.34,1.56,.64,1);
            --ease-smooth: cubic-bezier(.4,0,.2,1);
        }

        body {
            font-family: 'Montserrat', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #3b1d8a 45%, #4c1d95 100%);
            min-height: 100vh;
            color: #fff;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ──────────────────── */
        .pos-topbar {
            background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 55%, #a855f7 100%);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 24px rgba(0,0,0,.3);
        }
        .pos-brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.1rem;
        }
        .pos-brand-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,.18);
            border-radius: 11px;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            border: 1.5px solid rgba(255,255,255,.3);
        }

        /* ── CARD UTAMA ──────────────── */
        .sukses-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }
        .sukses-card {
            background: rgba(255,255,255,.08);
            border-radius: 24px;
            backdrop-filter: blur(16px);
            border: 1.5px solid rgba(255,255,255,.15);
            padding: 40px 36px;
            text-align: center;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 24px 64px rgba(0,0,0,.25);
            animation: fadeUp .5s ease both;
        }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Success Icon */
        .success-icon {
            width: 64px;
            height: 64px;
            background: var(--pa-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: scaleIn .4s ease .2s both;
        }
        .success-icon i {
            font-size: 2rem;
            color: #fff;
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to   { transform: scale(1); }
        }

        .badge-top {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(154,85,255,.35);
            border: 1px solid rgba(154,85,255,.45);
            border-radius: 999px;
            padding: .35rem 1rem;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            color: #fff;
        }

        .sukses-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1.5rem;
        }

        /* Nomor Display */
        .nomor-display {
            font-size: clamp(80px, 20vw, 120px);
            font-weight: 900;
            line-height: 1;
            color: var(--pa-gold);
            text-shadow: 0 0 40px rgba(251,191,36,.45), 0 0 80px rgba(251,191,36,.2);
            margin-bottom: .5rem;
            animation: goldPulse 2.2s ease-in-out infinite;
            letter-spacing: 4px;
        }
        @keyframes goldPulse {
            0%,100% { transform: scale(1); }
            50%      { transform: scale(1.04); }
        }

        .divider-line {
            height: 1px;
            background: rgba(255,255,255,.1);
            margin: 1.5rem 0;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 1.5rem 0;
        }
        .info-box {
            background: rgba(255,255,255,.07);
            border-radius: 14px;
            padding: 14px;
            border: 1px solid rgba(255,255,255,.1);
        }
        .info-box .info-label {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,.45);
            font-weight: 700;
            margin-bottom: 4px;
        }
        .info-box .info-value {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
        }
        .info-box .info-value.text-purple { color: var(--pa-purple2); }

        /* Vendor info */
        .vendor-line {
            font-size: .9rem;
            color: var(--pa-purple2);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .vendor-line i { margin-right: 4px; }

        /* Instruksi */
        .instruksi-box {
            background: rgba(154,85,255,.15);
            border: 1px solid rgba(154,85,255,.3);
            border-radius: 14px;
            padding: .9rem 1.1rem;
            font-size: .8rem;
            color: rgba(255,255,255,.8);
            line-height: 1.5;
            display: flex;
            gap: .6rem;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        .instruksi-box i { color: var(--pa-purple2); margin-top: 2px; font-size: 1rem; }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: .88rem;
            padding: .85rem 1rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all .3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #9a55ff, #7c3aed);
            color: #fff;
            box-shadow: 0 4px 15px rgba(154,85,255,.35);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(154,85,255,.5);
            color: #fff;
        }
        .btn-secondary {
            background: rgba(255,255,255,.1);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,.3);
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,.15);
            color: #fff;
            border-color: rgba(255,255,255,.5);
        }
        .btn-outline {
            background: transparent;
            color: rgba(255,255,255,.7);
            border: 1.5px solid rgba(255,255,255,.2);
        }
        .btn-outline:hover {
            background: rgba(255,255,255,.05);
            color: #fff;
            border-color: rgba(255,255,255,.4);
        }
    </style>
</head>
<body>

    {{-- ══ TOPBAR ══ --}}
    <div class="pos-topbar">
        <a href="{{ route('customer.pos') }}" class="pos-brand">
            <div class="pos-brand-icon">
                <i class="mdi mdi-book-open-page-variant"></i>
            </div>
            <span>Kantin&nbsp;<span style="opacity:.7;font-weight:400">Buku</span></span>
        </a>
    </div>

    {{-- ══ CARD UTAMA ══ --}}
    <div class="sukses-wrapper">
        <div class="sukses-card">
            {{-- Success Icon --}}
            <div class="success-icon">
                <i class="mdi mdi-check"></i>
            </div>

            {{-- Badge atas --}}
            <div class="badge-top">
                <i class="mdi mdi-ticket-confirmation"></i>
                Pendaftaran Berhasil
            </div>

            {{-- Nomor besar gold --}}
            <div class="nomor-display">
                {{ str_pad($antrian->nomor, 3, '0', STR_PAD_LEFT) }}
            </div>

            {{-- Nama --}}
            <div style="font-size:1.1rem;font-weight:700;margin-bottom:.5rem;">
                {{ $antrian->nama }}
            </div>

            {{-- Vendor --}}
            @if($antrian->vendor)
                <div class="vendor-line">
                    <i class="mdi mdi-store-outline"></i>
                    {{ $antrian->vendor->nama_vendor }}
                </div>
            @endif

            <div class="divider-line"></div>

            {{-- Info grid --}}
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">Waktu Daftar</div>
                    <div class="info-value text-purple">{{ $antrian->created_at->format('H:i') }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Tanggal</div>
                    <div class="info-value text-purple">{{ $antrian->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            {{-- Instruksi --}}
            <div class="instruksi-box">
                <i class="mdi mdi-information-outline"></i>
                <span>Harap menunggu. Nomor akan dipanggil melalui pengeras suara dan papan antrian.</span>
            </div>

            {{-- Action Buttons --}}
            <div class="action-buttons">
                <a href="{{ route('antrian.cetak-pdf', $antrian->id) }}" class="btn-action btn-primary" target="_blank">
                    <i class="mdi mdi-printer"></i> Cetak
                </a>
                <a href="{{ route('antrian.guest') }}" class="btn-action btn-secondary">
                    <i class="mdi mdi-refresh"></i> Daftar Lagi
                </a>
                <a href="{{ route('antrian.papan') }}" class="btn-action btn-outline" target="_blank">
                    <i class="mdi mdi-monitor-multiple"></i> Lihat Papan Antrian
                </a>
            </div>
        </div>
    </div>

</body>
</html>
