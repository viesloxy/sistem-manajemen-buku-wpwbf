<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Buku Online</title>
    <!-- Purple Admin Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Google Fonts – Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <style>
        /* ═══════════════════════════════════════════
           ROOT OVERRIDES – keep Purple Admin palette
           but add Montserrat & animation layer
        ═══════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --pa-purple:  #9a55ff;
            --pa-purple2: #da8cff;
            --pa-dark:    #5b21b6;
            --pa-bg:      #f2edf3;
            --pa-card:    #ffffff;
            --pa-text:    #1e1b4b;
            --pa-muted:   #6b7280;
            --pa-border:  #e8dfff;
            --pa-green:   #00c194;
            --pa-green2:  #0ac484;

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
            animation: slideDown .5s var(--ease-smooth) both;
        }
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
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
            transition: transform var(--dur) var(--ease-spring);
        }
        .pos-brand:hover .pos-brand-icon { transform: rotate(-8deg) scale(1.08); }

        .btn-login-topbar {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: rgba(255,255,255,.15);
            color: #fff !important;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: .8rem;
            padding: .5rem 1.2rem;
            border-radius: 999px;
            text-decoration: none;
            border: 1.5px solid rgba(255,255,255,.35);
            transition: all var(--dur) var(--ease-smooth);
            letter-spacing: .2px;
            backdrop-filter: blur(6px);
        }
        .btn-login-topbar:hover {
            background: rgba(255,255,255,.28);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,.15);
            color: #fff !important;
        }

        /* ── HERO BANNER ─────────────────────────── */
        .pos-hero {
            background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 50%, #a855f7 100%);
            color: #fff;
            padding: 3rem 2rem 4.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        /* Floating circles decoration */
        .pos-hero::before,
        .pos-hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            animation: floatBubble 6s ease-in-out infinite;
        }
        .pos-hero::before { width:260px;height:260px; top:-80px; left:-60px; animation-delay:0s; }
        .pos-hero::after  { width:180px;height:180px; bottom:-50px;right:5%; animation-delay:3s; }
        @keyframes floatBubble {
            0%,100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-14px) scale(1.04); }
        }

        .pos-hero-badge {
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
            backdrop-filter: blur(4px);
            animation: fadeUp .6s var(--ease-smooth) .1s both;
        }
        .pos-hero h1 {
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            font-weight: 800;
            letter-spacing: -1.5px;
            margin-bottom: .6rem;
            position: relative;
            z-index: 1;
            animation: fadeUp .6s var(--ease-smooth) .2s both;
        }
        .pos-hero p {
            font-size: .92rem;
            opacity: .85;
            font-weight: 400;
            position: relative;
            z-index: 1;
            max-width: 520px;
            margin: 0 auto;
            animation: fadeUp .6s var(--ease-smooth) .3s both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── MAIN WRAPPER ────────────────────────── */
        .pos-main {
            max-width: 1280px;
            margin: -2rem auto 0;
            padding: 0 1.5rem 3rem;
            position: relative;
            z-index: 10;
        }

        /* ── CARD OVERRIDES (extend Purple Admin) ── */
        .card {
            border-radius: 18px !important;
            border: 1px solid var(--pa-border) !important;
            box-shadow: 0 4px 28px rgba(109,40,217,.09) !important;
            transition: box-shadow var(--dur) var(--ease-smooth), transform var(--dur) var(--ease-smooth) !important;
        }
        .card:hover { box-shadow: 0 12px 42px rgba(109,40,217,.15) !important; }

        /* Card entrance animations */
        .card-animate {
            animation: riseCard .5s var(--ease-smooth) both;
        }
        .card-animate.delay-1 { animation-delay: .1s; }
        @keyframes riseCard {
            from { opacity:0; transform:translateY(24px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* ── PAGE HEADER ─────────────────────────── */
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
        .bg-icon-green   { background: #d1fae5; color: #059669; }

        .pos-page-header h5 {
            font-size: .95rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -.2px;
        }

        /* ── STEP LABELS ─────────────────────────── */
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

        /* ── CUSTOM SELECT ───────────────────────── */
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
        .pos-select:disabled {
            opacity: .42;
            cursor: not-allowed;
        }

        /* ── MENU PREVIEW ────────────────────────── */
        #preview_menu {
            display: none;
            margin-top: 1.5rem;
        }

        .preview-card {
            background: linear-gradient(135deg, #faf5ff, #f0ebff);
            border: 1.5px solid var(--pa-border);
            border-radius: 16px;
            padding: 1.25rem;
            animation: springIn .4s var(--ease-spring) both;
        }
        @keyframes springIn {
            from { opacity:0; transform:scale(.88) translateY(10px); }
            to   { opacity:1; transform:scale(1) translateY(0); }
        }

        .preview-body {
            display: flex;
            gap: 1.25rem;
            align-items: center;
        }
        @media(max-width:540px){ .preview-body{flex-direction:column;text-align:center;} }

        #prev_gambar {
            width: 104px; height: 104px;
            object-fit: cover;
            border-radius: 14px;
            border: 3px solid #fff;
            box-shadow: 0 8px 24px rgba(154,85,255,.22);
            flex-shrink: 0;
            transition: transform var(--dur) var(--ease-spring);
        }
        #prev_gambar:hover { transform: scale(1.07) rotate(-3deg); }

        .preview-info .book-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--pa-text);
            margin-bottom: .2rem;
        }
        .preview-info .book-price {
            font-size: 1.35rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--pa-purple), var(--pa-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: .9rem;
        }

        /* Qty control */
        .qty-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .9rem;
        }
        .qty-btn {
            width: 34px; height: 34px;
            border-radius: 50%;
            border: 2px solid var(--pa-purple);
            background: transparent;
            color: var(--pa-purple);
            font-size: 1rem;
            font-weight: 700;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: all var(--dur) var(--ease-spring);
            line-height: 1;
        }
        .qty-btn:hover {
            background: var(--pa-purple);
            color: #fff;
            transform: scale(1.15);
            box-shadow: 0 4px 14px rgba(154,85,255,.4);
        }
        .qty-input {
            width: 54px;
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: .95rem;
            border: 1.5px solid var(--pa-border);
            border-radius: 9px;
            padding: .4rem;
            background: #fff;
            color: var(--pa-text);
        }
        .qty-input:focus { outline: none; border-color: var(--pa-purple); }

        /* Add to cart btn – use Purple Admin gradient + enhancements */
        .btn-add-cart {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 700 !important;
            font-size: .85rem !important;
            letter-spacing: .3px;
            border-radius: 12px !important;
            padding: .75rem 1rem !important;
            position: relative;
            overflow: hidden;
            transition: transform var(--dur) var(--ease-spring), box-shadow var(--dur) var(--ease-smooth) !important;
        }
        .btn-add-cart::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.15), transparent);
            opacity: 0;
            transition: opacity var(--dur);
        }
        .btn-add-cart:hover { transform: translateY(-2px) !important; box-shadow: 0 8px 28px rgba(154,85,255,.45) !important; }
        .btn-add-cart:hover::after { opacity: 1; }
        .btn-add-cart:active { transform: scale(.97) !important; }

        /* ── CART SECTION ────────────────────────── */
        .cart-scroll {
            max-height: 340px;
            overflow-y: auto;
            padding-right: .25rem;
        }
        .cart-scroll::-webkit-scrollbar { width: 4px; }
        .cart-scroll::-webkit-scrollbar-track { background: transparent; }
        .cart-scroll::-webkit-scrollbar-thumb { background: var(--pa-border); border-radius: 4px; }

        /* Empty state */
        .cart-empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--pa-muted);
        }
        .cart-empty i { font-size: 2.8rem; opacity: .2; display: block; margin-bottom: .5rem; }
        .cart-empty p { font-size: .82rem; font-weight: 500; }

        /* Cart item rows */
        .cart-item-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .7rem 0;
            border-bottom: 1px dashed var(--pa-border);
            animation: itemSlide .25s var(--ease-smooth) both;
        }
        @keyframes itemSlide {
            from { opacity:0; transform:translateX(-12px); }
            to   { opacity:1; transform:translateX(0); }
        }
        .cart-item-row:last-child { border-bottom: none; }
        .cart-item-info { flex: 1; min-width: 0; }
        .cart-item-name {
            font-weight: 600;
            font-size: .83rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cart-item-unit { font-size: .72rem; color: var(--pa-muted); font-weight: 500; }
        .cart-qty-pill {
            background: #ede9fe;
            color: var(--pa-purple);
            border-radius: 6px;
            padding: .18rem .6rem;
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .cart-item-sub {
            font-weight: 700;
            font-size: .82rem;
            text-align: right;
            min-width: 72px;
            white-space: nowrap;
            color: var(--pa-text);
        }
        .btn-remove-item {
            background: none;
            border: none;
            color: var(--pa-muted);
            cursor: pointer;
            padding: .2rem;
            border-radius: 50%;
            display: grid;
            place-items: center;
            transition: all var(--dur) var(--ease-smooth);
        }
        .btn-remove-item:hover {
            color: #ef4444;
            background: #fee2e2;
            transform: scale(1.15) rotate(90deg);
        }

        /* ── ORDER SUMMARY BOX ───────────────────── */
        .order-summary-box {
            background: linear-gradient(135deg, #f5f0ff, #ede9fe);
            border: 1px solid var(--pa-border);
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-top: 1rem;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .3rem 0;
            font-size: .82rem;
        }
        .summary-row .s-label { color: var(--pa-muted); font-weight: 500; }
        .summary-row .s-val   { font-weight: 600; color: var(--pa-text); }
        .summary-divider { border: none; border-top: 1px solid var(--pa-border); margin: .5rem 0; }
        .summary-total-label { font-weight: 700; color: var(--pa-text); font-size: .9rem; }
        .summary-total-val {
            font-weight: 800;
            font-size: 1.25rem;
            background: linear-gradient(135deg, var(--pa-purple), var(--pa-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── CHECKOUT BUTTON ─────────────────────── */
        .btn-checkout-main {
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
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
            transition: transform var(--dur) var(--ease-spring), box-shadow var(--dur) var(--ease-smooth) !important;
        }
        .btn-checkout-main::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.15) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform .55s ease;
        }
        .btn-checkout-main:hover:not(:disabled)::after { transform: translateX(100%); }
        .btn-checkout-main:hover:not(:disabled) {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 36px rgba(0,193,148,.45) !important;
        }
        .btn-checkout-main:disabled { cursor: not-allowed; opacity: .55; }
        .btn-checkout-main:active:not(:disabled) { transform: scale(.98) !important; }

        /* ── CART BADGE ──────────────────────────── */
        .cart-count-badge {
            background: linear-gradient(135deg, var(--pa-purple), var(--pa-dark));
            color: #fff;
            border-radius: 999px;
            padding: .1rem .55rem;
            font-size: .68rem;
            font-weight: 800;
            min-width: 22px;
            text-align: center;
            display: inline-block;
            margin-left: .3rem;
            transition: transform var(--dur) var(--ease-spring);
        }
        .cart-count-badge.pulse {
            animation: badgePulse .35s var(--ease-spring);
        }
        @keyframes badgePulse {
            0%  { transform: scale(1); }
            50% { transform: scale(1.5); }
            100%{ transform: scale(1); }
        }

        /* ── FOOTER FEATURES STRIP ───────────────── */
        .features-row {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 1rem;
            max-width: 1280px;
            margin: 0 auto 3rem;
            padding: 0 1.5rem;
        }
        @media(max-width:700px){ .features-row { grid-template-columns:1fr; } }

        .feature-tile {
            background: #fff;
            border: 1px solid var(--pa-border);
            border-radius: 16px;
            padding: 1.15rem 1.35rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform var(--dur) var(--ease-smooth), box-shadow var(--dur) var(--ease-smooth);
            animation: riseCard .5s var(--ease-smooth) both;
        }
        .feature-tile:nth-child(1){ animation-delay:.35s; }
        .feature-tile:nth-child(2){ animation-delay:.45s; }
        .feature-tile:nth-child(3){ animation-delay:.55s; }
        .feature-tile:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 32px rgba(109,40,217,.13);
        }
        .feature-tile-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .fi-orange { background:#fff7ed; color:#f97316; }
        .fi-blue   { background:#eff6ff; color:#3b82f6; }
        .fi-purple { background:#faf5ff; color:var(--pa-purple); }
        .feature-tile h6 { font-size:.85rem; font-weight:700; margin:0 0 .15rem; }
        .feature-tile p  { font-size:.73rem; color:var(--pa-muted); margin:0; }

        /* ── MICRO ANIMATIONS ────────────────────── */
        .spin { animation: spin .7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Shimmer on select when loading */
        @keyframes shimmer {
            0%   { background-position: -400px 0; }
            100% { background-position: 400px 0; }
        }
        .loading-shimmer {
            background: linear-gradient(90deg, #ede9fe 25%, #f5f3ff 50%, #ede9fe 75%);
            background-size: 800px 100%;
            animation: shimmer 1.4s infinite;
        }

        /* ── RESPONSIVE GRID ─────────────────────── */
        @media(max-width:900px){
            .pos-grid { display: block !important; }
            .pos-grid > * { margin-bottom: 1.25rem; }
        }
        
        /* Purple Admin grid-margin override for closer spacing */
        .grid-margin { margin-bottom: 1.5rem !important; }

        /* ── QR CODE MODAL ─────────────────────── */
        .qr-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all .3s ease;
        }
        .qr-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .qr-modal-box {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            max-width: 380px;
            width: 90%;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
            transform: scale(.85) translateY(20px);
            transition: transform .4s cubic-bezier(.34,1.56,.64,1);
        }
        .qr-modal-overlay.active .qr-modal-box {
            transform: scale(1) translateY(0);
        }
        .qr-modal-icon {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .qr-modal-icon i {
            font-size: 2rem;
            color: #059669;
        }
        .qr-modal-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e1b4b;
            margin-bottom: .35rem;
        }
        .qr-modal-sub {
            font-size: .85rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }
        .qr-modal-code {
            background: #fff;
            border: 2px solid #e8dfff;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.25rem;
        }
        .qr-modal-code img {
            width: 180px;
            height: 180px;
            display: block;
            margin: 0 auto;
        }
        .qr-modal-orderid {
            font-size: .75rem;
            color: #9ca3af;
            margin-top: .5rem;
        }
        .qr-modal-btn {
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
            cursor: pointer;
            transition: all .25s ease;
            box-shadow: 0 6px 20px rgba(154,85,255,.4);
        }
        .qr-modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(154,85,255,.5);
        }
        .qr-modal-note {
            font-size: .72rem;
            color: #9ca3af;
            margin-top: 1rem;
        }
    </style>
</head>
<body>

    <!-- ══ TOPBAR ══════════════════════════════════ -->
    <div class="pos-topbar">
        <a href="#" class="pos-brand">
            <div class="pos-brand-icon">
                <i class="mdi mdi-book-open-page-variant"></i>
            </div>
            <span>Kantin&nbsp;<span style="opacity:.7;font-weight:400">Buku</span></span>
        </a>
        <a href="{{ route('login') }}" class="btn-login-topbar">
            <i class="mdi mdi-login-variant"></i> Login
        </a>
    </div>

    <!-- ══ HERO ════════════════════════════════════ -->
    <div class="pos-hero">
        <div class="pos-hero-badge">
            <i class="mdi mdi-bookmark-check"></i> Kantin Buku Digital
        </div>
        <h1><i class="mdi mdi-bookshelf"></i>&nbsp; Beli Buku Langsung di Sini</h1>
        <p>Temukan buku favoritmu dari berbagai vendor terpercaya, tambahkan ke keranjang, dan bayar dengan mudah via QRIS atau Virtual Account.</p>
    </div>

    <!-- ══ MAIN CONTENT ════════════════════════════ -->
    <div class="pos-main">
        <div class="pos-grid" style="display:grid;grid-template-columns:1fr 400px;gap:1.5rem;align-items:start;">

            <!-- ── LEFT: PILIH BUKU ─────────────── -->
            <div>
                <div class="card card-animate" style="height:100%;">
                    <div class="pos-page-header">
                        <div class="header-icon bg-icon-purple">
                            <i class="mdi mdi-book-search-outline"></i>
                        </div>
                        <h5>Pilih Buku</h5>
                    </div>
                    <div class="card-body">

                        <!-- Step selects -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="step-label"><span class="step-num">1</span> Pilih Vendor / Toko</div>
                                <select class="pos-select" id="select_vendor">
                                    <option value="" disabled selected>— Pilih Vendor —</option>
                                    @foreach($vendors as $v)
                                        <option value="{{ $v->id }}">{{ $v->nama_vendor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="step-label"><span class="step-num">2</span> Pilih Buku / Judul</div>
                                <select class="pos-select" id="select_menu" disabled>
                                    <option value="" disabled selected>— Pilih Vendor Dulu —</option>
                                </select>
                            </div>
                        </div>

                        <!-- Book Preview -->
                        <div id="preview_menu">
                            <div class="preview-card">
                                <div class="preview-body">
                                    <img id="prev_gambar" src="" alt="Cover Buku"
                                         onerror="this.src='https://placehold.co/104x104/ede9fe/9a55ff?text=Buku'">
                                    <div class="preview-info" style="flex:1;">
                                        <div class="book-title" id="prev_nama">Judul Buku</div>
                                        <div class="book-price" id="prev_harga">Rp 0</div>
                                        <div class="qty-row">
                                            <button class="qty-btn" id="btn_min" type="button">−</button>
                                            <input type="number" id="prev_qty" class="qty-input" value="1" min="1" readonly>
                                            <button class="qty-btn" id="btn_plus" type="button">+</button>
                                        </div>
                                        <button class="btn btn-gradient-primary btn-add-cart" id="btn_tambah_keranjang" type="button">
                                            <i class="mdi mdi-cart-plus"></i> Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty hint when nothing selected -->
                        <div id="empty_hint" class="text-center mt-4 py-3" style="color:var(--pa-muted);">
                            <i class="mdi mdi-gesture-tap mdi-36px" style="opacity:.25;display:block;margin-bottom:.4rem;"></i>
                            <span style="font-size:.82rem;font-weight:500;">Pilih vendor dan judul buku untuk mulai memesan</span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ── RIGHT: KERANJANG ─────────────── -->
            <div>
                <div class="card card-animate delay-1">
                    <div class="pos-page-header">
                        <div class="header-icon bg-icon-green">
                            <i class="mdi mdi-cart-outline"></i>
                        </div>
                        <h5>Keranjang Belanja</h5>
                        <span class="cart-count-badge" id="cartBadge">0</span>
                    </div>
                    <div class="card-body" style="padding-top:.75rem;">

                        <!-- Cart list -->
                        <div class="cart-scroll" id="cartList">
                            <div class="cart-empty">
                                <i class="mdi mdi-cart-off"></i>
                                <p>Keranjang masih kosong.<br>Tambahkan buku favoritmu!</p>
                            </div>
                        </div>

                        <!-- Order summary -->
                        <div class="order-summary-box">
                            <div class="summary-row">
                                <span class="s-label">Subtotal</span>
                                <span class="s-val">Rp <span id="labelSubtotal">0</span></span>
                            </div>
                            <div class="summary-row">
                                <span class="s-label">Biaya Admin</span>
                                <span class="s-val" style="color:var(--pa-green);font-weight:700;">Gratis</span>
                            </div>
                            <hr class="summary-divider">
                            <div class="summary-row">
                                <span class="summary-total-label">Total Bayar</span>
                                <span class="summary-total-val">Rp <span id="labelTotal">0</span></span>
                            </div>
                        </div>

                        <button class="btn btn-gradient-success btn-checkout-main" id="btnBayarMidtrans" disabled type="button">
                            <i class="mdi mdi-qrcode-scan"></i>
                            BAYAR SEKARANG · QRIS / VA
                        </button>

                    </div>
                </div>
            </div>

        </div><!-- /pos-grid -->
    </div><!-- /pos-main -->

    <!-- ══ FEATURES ═══════════════════════════════ -->
    <div class="features-row">
        <div class="feature-tile">
            <div class="feature-tile-icon fi-purple"><i class="mdi mdi-book-check-outline"></i></div>
            <div>
                <h6>Koleksi Lengkap</h6>
                <p>Ribuan judul buku dari berbagai kategori tersedia</p>
            </div>
        </div>
        <div class="feature-tile">
            <div class="feature-tile-icon fi-blue"><i class="mdi mdi-shield-check-outline"></i></div>
            <div>
                <h6>Pembayaran Aman</h6>
                <p>Didukung Midtrans QRIS & Virtual Account terpercaya</p>
            </div>
        </div>
        <div class="feature-tile">
            <div class="feature-tile-icon fi-orange"><i class="mdi mdi-lightning-bolt-outline"></i></div>
            <div>
                <h6>Proses Instan</h6>
                <p>Konfirmasi pesanan langsung diterima vendor real-time</p>
            </div>
        </div>
    </div>

    <!-- ══ SCRIPTS ═════════════════════════════════ -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- QR Code Modal -->
    <div class="qr-modal-overlay" id="qrModal">
        <div class="qr-modal-box">
            <div class="qr-modal-icon">
                <i class="mdi mdi-check-circle"></i>
            </div>
            <h3 class="qr-modal-title">Pembayaran Berhasil!</h3>
            <p class="qr-modal-sub">Simpan QR Code ini untuk verifikasi pesanan</p>
            <div class="qr-modal-code">
                <img id="qrCodeImage" src="" alt="QR Code Pesanan">
            </div>
            <p class="qr-modal-orderid">Order ID: <strong id="qrOrderId">#0</strong></p>
            <button class="qr-modal-btn" id="btnCloseQrModal">
                <i class="mdi mdi-close"></i> Tutup
            </button>
            <p class="qr-modal-note">Tunjukkan QR Code ini ke vendor saat pengambilan buku</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function () {
        let keranjang = [];
        let menuAktif = null;

        /* ── helpers ───────────────────────────── */
        function formatRp(n) { return parseInt(n).toLocaleString('id-ID'); }

        /* ── 1. SELECT VENDOR ──────────────────── */
        $('#select_vendor').on('change', function () {
            const vendor_id = $(this).val();
            const $sel = $('#select_menu');

            $sel.prop('disabled', true).html('<option>Memuat...</option>').addClass('loading-shimmer');
            $('#preview_menu').fadeOut(200);
            $('#empty_hint').show();

            axios.get(`/customer/api/menu-by-vendor/${vendor_id}`)
                .then(res => {
                    let html = '<option value="" disabled selected>— Pilih Judul Buku —</option>';
                    res.data.data.forEach(m => {
                        html += `<option value="${m.id}">${m.nama_menu} — Rp ${formatRp(m.harga)}</option>`;
                    });
                    $sel.html(html).prop('disabled', false).removeClass('loading-shimmer');
                })
                .catch(() => {
                    $sel.html('<option>Gagal memuat. Coba lagi.</option>').removeClass('loading-shimmer');
                });
        });

        /* ── 2. SELECT MENU → PREVIEW ──────────── */
        $('#select_menu').on('change', function () {
            const menu_id = $(this).val();
            if (!menu_id) return;

            axios.get(`/customer/api/menu-detail/${menu_id}`)
                .then(res => {
                    menuAktif = res.data.data;
                    $('#prev_nama').text(menuAktif.nama_menu);
                    $('#prev_harga').text('Rp ' + formatRp(menuAktif.harga));
                    $('#prev_gambar').attr('src', menuAktif.path_gambar
                        ? '/' + menuAktif.path_gambar
                        : 'https://placehold.co/104x104/ede9fe/9a55ff?text=Buku');
                    $('#prev_qty').val(1);
                    $('#empty_hint').hide();
                    $('#preview_menu').stop(true).fadeIn(250);
                });
        });

        /* ── 3. QTY ────────────────────────────── */
        $('#btn_plus').on('click', () => {
            $('#prev_qty').val(parseInt($('#prev_qty').val()) + 1);
        });
        $('#btn_min').on('click', () => {
            const v = parseInt($('#prev_qty').val());
            if (v > 1) $('#prev_qty').val(v - 1);
        });

        /* ── 4. TAMBAH KE KERANJANG ────────────── */
        $('#btn_tambah_keranjang').on('click', function () {
            const qty = parseInt($('#prev_qty').val());
            const idx = keranjang.findIndex(x => x.id_menu === menuAktif.id);

            if (idx !== -1) {
                keranjang[idx].jumlah += qty;
                keranjang[idx].subtotal = keranjang[idx].jumlah * keranjang[idx].harga;
            } else {
                keranjang.push({
                    id_menu:  menuAktif.id,
                    nama:     menuAktif.nama_menu,
                    harga:    menuAktif.harga,
                    jumlah:   qty,
                    subtotal: menuAktif.harga * qty
                });
            }

            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: `<span style="font-family:Montserrat,sans-serif;font-weight:700;">Buku ditambahkan!</span>`,
                showConfirmButton: false, timer: 1700
            });

            renderKeranjang();

            // Reset
            $('#select_menu').val('');
            $('#preview_menu').fadeOut(200);
            $('#empty_hint').show();
            menuAktif = null;
        });

        /* ── 5. RENDER KERANJANG ───────────────── */
        function renderKeranjang() {
            let total = 0;

            if (keranjang.length === 0) {
                $('#cartList').html(`
                    <div class="cart-empty">
                        <i class="mdi mdi-cart-off"></i>
                        <p>Keranjang masih kosong.<br>Tambahkan buku favoritmu!</p>
                    </div>`);
            } else {
                let html = '';
                keranjang.forEach((item, i) => {
                    total += item.subtotal;
                    html += `
                        <div class="cart-item-row">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${item.nama}</div>
                                <div class="cart-item-unit">Rp ${formatRp(item.harga)} / buku</div>
                            </div>
                            <span class="cart-qty-pill">×${item.jumlah}</span>
                            <div class="cart-item-sub">Rp ${formatRp(item.subtotal)}</div>
                            <button class="btn-remove-item btn-del-item" data-i="${i}" title="Hapus">
                                <i class="mdi mdi-close-circle mdi-18px"></i>
                            </button>
                        </div>`;
                });
                $('#cartList').html(html);
            }

            // badge
            const badge = $('#cartBadge');
            badge.text(keranjang.length);
            badge.addClass('pulse');
            setTimeout(() => badge.removeClass('pulse'), 400);

            // totals
            $('#labelSubtotal').text(formatRp(total));
            $('#labelTotal').text(formatRp(total));

            $('#btnBayarMidtrans').prop('disabled', keranjang.length === 0);
        }

        $(document).on('click', '.btn-del-item', function () {
            keranjang.splice($(this).data('i'), 1);
            renderKeranjang();
        });

        /* ── 6. CHECKOUT MIDTRANS ──────────────── */
        $('#btnBayarMidtrans').on('click', function () {
            const totalVal = parseInt($('#labelTotal').text().replace(/\./g, ''));
            const btn = $(this);

            btn.prop('disabled', true).html(`<i class="mdi mdi-loading spin"></i>&nbsp; Memproses Pesanan...`);

            axios.post("{{ route('customer.checkout') }}", {
                total:   totalVal,
                items:   keranjang,
                _token:  "{{ csrf_token() }}"
            })
            .then(res => {
                if (res.data.status === 'success') {
                    const pesananId = res.data.pesanan_id;

                    window.snap.pay(res.data.snap_token, {
                        onSuccess: result => {
                            // Tampilkan modal QR Code
                            $('#qrOrderId').text('#' + pesananId);
                            $('#qrCodeImage').attr('src', '/qrcode/' + pesananId);
                            $('#qrModal').addClass('active');
                        },
                        onPending: result => {
                            Swal.fire('Menunggu Pembayaran', 'Silakan selesaikan pembayaran Anda segera.', 'info')
                                .then(() => window.location.reload());
                        },
                        onError: () => Swal.fire('Pembayaran Gagal', 'Silakan coba lagi.', 'error'),
                        onClose: () => {
                            Swal.fire('Info', 'Anda menutup jendela pembayaran sebelum menyelesaikan transaksi.', 'warning');
                        }
                    });
                }
            })
            .catch(err => {
                Swal.fire('Terjadi Kesalahan', 'Sistem mengalami gangguan. Silakan coba lagi.', 'error');
                console.error(err);
            })
            .finally(() => {
                btn.prop('disabled', false).html(`<i class="mdi mdi-qrcode-scan"></i> BAYAR SEKARANG · QRIS / VA`);
            });
        });

        // Tutup modal QR Code
        $('#btnCloseQrModal').on('click', function () {
            $('#qrModal').removeClass('active');
            // Tunggu animasi selesai sebelum reload
            setTimeout(() => {
                window.location.reload();
            }, 300);
        });

        // Tutup modal saat klik overlay
        $('#qrModal').on('click', function (e) {
            if (e.target === this) {
                $(this).removeClass('active');
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            }
        });
    });
    </script>
</body>
</html>