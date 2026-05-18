<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Nomor Antrian — Kantin Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ══ DESIGN TOKENS — SAMAKAN DENGAN customer/pos.blade.php ══ */
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --pa-purple:   #9a55ff;
            --pa-purple2:  #da8cff;
            --pa-dark:     #5b21b6;
            --pa-bg:       #f2edf3;
            --pa-card:     #ffffff;
            --pa-text:     #1e1b4b;
            --pa-muted:    #6b7280;
            --pa-border:   #e8dfff;
            --pa-green:    #00c194;
            --ease-spring: cubic-bezier(.34,1.56,.64,1);
            --ease-smooth: cubic-bezier(.4,0,.2,1);
            --dur:         .28s;
        }

        body {
            font-family: 'Montserrat', 'Segoe UI', sans-serif !important;
            background: var(--pa-bg) !important;
            color: var(--pa-text) !important;
            min-height: 100vh;
        }

        /* ── TOPBAR ──────────────────────────────── */
        .pos-topbar {
            background: linear-gradient(135deg, #6d28d9 0%, var(--pa-purple) 55%, var(--pa-purple2) 100%);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 24px rgba(154,85,255,.35);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .pos-brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -.4px;
        }
        .pos-brand-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,.18);
            backdrop-filter: blur(8px);
            border-radius: 11px;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            border: 1.5px solid rgba(255,255,255,.3);
        }

        /* ── HERO ──────────────────────────────── */
        .guest-hero {
            background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 50%, #a855f7 100%);
            color: #fff;
            padding: 3rem 2rem 3.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .guest-hero::before,
        .guest-hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            animation: floatBubble 6s ease-in-out infinite;
        }
        .guest-hero::before { width:260px;height:260px; top:-80px; left:-60px; }
        .guest-hero::after  { width:180px;height:180px; bottom:-50px;right:5%; animation-delay:3s; }
        @keyframes floatBubble {
            0%,100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-14px) scale(1.04); }
        }
        .guest-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 999px;
            padding: .35rem .9rem;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .guest-hero h1 {
            font-size: clamp(1.5rem, 4vw, 2.2rem);
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: .5rem;
            position: relative;
            z-index: 1;
            animation: fadeUp .6s var(--ease-smooth) .1s both;
        }
        .guest-hero p {
            font-size: .9rem;
            opacity: .85;
            position: relative;
            z-index: 1;
            max-width: 480px;
            margin: 0 auto;
            animation: fadeUp .6s var(--ease-smooth) .2s both;
        }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* ── MAIN WRAPPER ──────────────────────── */
        .guest-main {
            max-width: 520px;
            margin: -2rem auto 0;
            padding: 0 1.5rem 3rem;
            position: relative;
            z-index: 10;
        }

        /* ── CARD (extends Purple Admin) ───────── */
        .card {
            border-radius: 18px !important;
            border: 1px solid var(--pa-border) !important;
            box-shadow: 0 4px 28px rgba(109,40,217,.09) !important;
            transition: box-shadow var(--dur) var(--ease-smooth) !important;
        }

        /* ── PAGE HEADER ────────────────────────── */
        .pos-page-header {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--pa-border);
        }
        .pos-page-header .header-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 1rem;
        }
        .bg-icon-purple { background: #ede9fe; color: #7c3aed; }
        .pos-page-header h5 {
            font-size: .95rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -.2px;
        }

        /* ── STEP LABELS ────────────────────────── */
        .step-label {
            font-size: .72rem;
            font-weight: 700;
            color: var(--pa-purple);
            text-transform: uppercase;
            letter-spacing: .8px;
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-bottom: .45rem;
        }
        .step-num {
            width: 20px; height: 20px;
            background: var(--pa-purple);
            color: #fff;
            border-radius: 50%;
            font-size: .65rem;
            font-weight: 800;
            display: grid;
            place-items: center;
        }

        /* ── CUSTOM INPUT & SELECT ──────────────── */
        .pos-input {
            width: 100%;
            font-family: 'Montserrat', sans-serif !important;
            font-size: .85rem;
            font-weight: 600;
            color: var(--pa-text);
            background: #faf8ff;
            border: 1.5px solid var(--pa-border);
            border-radius: 12px;
            padding: .72rem 1rem;
            transition: all var(--dur) var(--ease-smooth);
        }
        .pos-input:focus {
            outline: none;
            border-color: var(--pa-purple);
            box-shadow: 0 0 0 3px rgba(154,85,255,.18);
            background: #fff;
        }
        .pos-input::placeholder { color: #c4b5fd; font-weight: 500; }

        .pos-select {
            width: 100%;
            font-family: 'Montserrat', sans-serif !important;
            font-size: .85rem;
            font-weight: 600;
            color: var(--pa-text);
            background: #faf8ff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%239a55ff'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E") no-repeat right .8rem center / 20px;
            border: 1.5px solid var(--pa-border);
            border-radius: 12px;
            padding: .72rem 2.4rem .72rem 1rem;
            appearance: none;
            cursor: pointer;
            transition: all var(--dur) var(--ease-smooth);
        }
        .pos-select:focus {
            outline: none;
            border-color: var(--pa-purple);
            box-shadow: 0 0 0 3px rgba(154,85,255,.18);
            background-color: #fff;
        }

        /* ── TOMBOL AMBIL ANTRIAN ──────────────── */
        .btn-antrian {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 800 !important;
            font-size: .92rem !important;
            letter-spacing: .5px;
            padding: .95rem 1rem !important;
            border-radius: 14px !important;
            margin-top: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: transform var(--dur) var(--ease-spring),
                        box-shadow var(--dur) var(--ease-smooth) !important;
        }
        .btn-antrian::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.15) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform .55s ease;
        }
        .btn-antrian:hover::after { transform: translateX(100%); }
        .btn-antrian:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 36px rgba(154,85,255,.45) !important;
        }
        .btn-antrian:disabled {
            cursor: not-allowed;
            opacity: .55;
        }

        /* ── INFO NOTE ─────────────────────────── */
        .info-note {
            background: #f5f0ff;
            border: 1px solid var(--pa-border);
            border-radius: 12px;
            padding: .9rem 1.1rem;
            margin-top: 1rem;
            display: flex;
            gap: .6rem;
            align-items: flex-start;
        }
        .info-note i { color: var(--pa-purple); font-size: 1.1rem; margin-top: 2px; }
        .info-note p {
            font-size: .78rem;
            color: var(--pa-muted);
            font-weight: 500;
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    {{-- ══ TOPBAR ══════════════════════════════════ --}}
    <div class="pos-topbar">
        <a href="{{ route('customer.pos') }}" class="pos-brand">
            <div class="pos-brand-icon">
                <i class="mdi mdi-book-open-page-variant"></i>
            </div>
            <span>Kantin&nbsp;<span style="opacity:.7;font-weight:400">Buku</span></span>
        </a>
        <div>
            <a href="{{ route('customer.pos') }}" class="btn-login-topbar"
               style="display:inline-flex;align-items:center;gap:.45rem;background:rgba(255,255,255,.15);
                      color:#fff;font-family:'Montserrat',sans-serif;font-weight:600;font-size:.8rem;
                      padding:.5rem 1.2rem;border-radius:999px;text-decoration:none;
                      border:1.5px solid rgba(255,255,255,.35);">
                <i class="mdi mdi-home"></i> Beranda
            </a>
        </div>
    </div>

    {{-- ══ HERO ════════════════════════════════════ --}}
    <div class="guest-hero">
        <div class="guest-hero-badge">
            <i class="mdi mdi-ticket-confirmation"></i> Sistem Antrian Digital
        </div>
        <h1><i class="mdi mdi-clipboard-list-outline"></i>&nbsp; Ambil Nomor Antrian</h1>
        <p>Pengambilan pesanan buku lebih terorganisir dengan nomor antrian digital.</p>
    </div>

    {{-- ══ FORM ══════════════════════════════════════ --}}
    <div class="guest-main">
        <div class="card">
            <div class="pos-page-header">
                <div class="header-icon bg-icon-purple">
                    <i class="mdi mdi-account-plus"></i>
                </div>
                <h5>Form Pendaftaran Antrian</h5>
            </div>
            <div class="card-body">

                {{-- Notifikasi berhasil --}}
                @if(session('success'))
                    <div class="alert alert-success mb-3" style="border-radius:12px;">
                        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- Validation errors --}}
                @if($errors->any())
                    <div class="alert alert-danger mb-3" style="border-radius:12px;font-size:.82rem;">
                        <i class="mdi mdi-alert-circle"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('antrian.daftar') }}" method="POST" id="form-antrian">
                    @csrf

                    {{-- Input Nama --}}
                    <div class="mb-3">
                        <div class="step-label">
                            <span class="step-num">1</span> Nama Lengkap
                        </div>
                        <input type="text"
                               name="nama"
                               id="nama"
                               class="pos-input @error('nama') is-invalid @enderror"
                               placeholder="Masukkan nama lengkap Anda..."
                               value="{{ old('nama') }}"
                               required
                               autofocus>
                        @error('nama')
                            <small class="text-danger" style="font-size:.78rem;">
                                <i class="mdi mdi-alert"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- Dropdown Vendor --}}
                    <div class="mb-3">
                        <div class="step-label">
                            <span class="step-num">2</span> Vendor Tujuan
                            <span style="color:#dc2626;font-weight:800;">*</span>
                        </div>
                        <select name="vendor_id"
                                id="vendor_id"
                                class="pos-select @error('vendor_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}"
                                    {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <small class="text-danger" style="font-size:.78rem;">
                                <i class="mdi mdi-alert"></i> {{ $message }}
                            </small>
                        @enderror
                        <small class="d-block mt-1" style="font-size:.75rem;color:var(--pa-muted);">
                            Pilih vendor tempat Anda mengambil pesanan buku.
                        </small>
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="btn btn-gradient-primary btn-antrian" id="btn-daftar">
                        <i class="mdi mdi-ticket-confirmation"></i>
                        Ambil Nomor Antrian
                    </button>
                </form>

                {{-- Info note --}}
                <div class="info-note">
                    <i class="mdi mdi-information-outline"></i>
                    <p>
                        Setelah mengisi formulir dan menekan tombol, tab baru akan terbuka secara otomatis
                        menampilkan nomor antrian serta posisi Anda.
                    </p>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
