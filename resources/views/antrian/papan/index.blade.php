<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian — Kantin Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <style>
        /* ══ DESIGN TOKENS — THEME UNGU KANTIN BUKU ══ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --pa-purple:   #9a55ff;
            --pa-purple2:  #da8cff;
            --pa-dark:     #4c1d95;
            --pa-gold:     #fbbf24;
            --pa-green:    #00c194;
            --pa-text:     #1e1b4b;
            --pa-muted:    rgba(255,255,255,0.55);
            --pa-border:   rgba(255,255,255,0.15);
            --ease-spring: cubic-bezier(.34,1.56,.64,1);
            --ease-smooth: cubic-bezier(.4,0,.2,1);
        }

        body {
            font-family: 'Montserrat', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #3b1d8a 45%, #4c1d95 70%, #7c3aed 100%);
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
        }

        /* ── WRAPPER UTAMA ──────────────────────── */
        .papan-wrapper {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            padding: 20px 24px;
            min-height: 100vh;
            max-width: 1400px;
            margin: 0 auto;
            align-items: start;
        }

        /* ── HEADER ─────────────────────────────── */
        .papan-header {
            grid-column: 1 / -1;
            text-align: center;
            padding: 14px 20px;
            background: rgba(255,255,255,0.08);
            border-radius: 18px;
            backdrop-filter: blur(12px);
            border: 1px solid var(--pa-border);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        .header-icon-wrap {
            width: 44px; height: 44px;
            background: rgba(154,85,255,.35);
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            border: 1.5px solid rgba(255,255,255,.2);
        }
        .header-icon-wrap i { color: #fff; }
        .header-title {
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 1px;
            color: #fff;
        }
        .header-subtitle {
            font-size: .72rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(255,255,255,.6);
            font-weight: 600;
        }

        /* ── KOTAK NOMOR UTAMA ─────────────────── */
        .kotak-dipanggil {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.06);
            border-radius: 24px;
            backdrop-filter: blur(16px);
            border: 2px solid var(--pa-border);
            padding: 48px 28px;
            text-align: center;
            min-height: 480px;
        }

        .label-dipanggil {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .78rem;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: rgba(255,255,255,.6);
            font-weight: 700;
            margin-bottom: 20px;
            animation: fadeUp .4s ease both;
        }
        .label-dipanggil i { font-size: 1.1rem; color: var(--pa-purple2); }

        .nomor-besar {
            font-size: clamp(100px, 18vw, 180px);
            font-weight: 900;
            color: var(--pa-gold);
            text-shadow:
                0 0 40px rgba(251,191,36,.45),
                0 0 80px rgba(251,191,36,.2);
            line-height: 1;
            margin-bottom: 20px;
            animation: goldPulse 2.2s ease-in-out infinite, fadeUp .5s ease .1s both;
            letter-spacing: 6px;
        }
        @keyframes goldPulse {
            0%, 100% { transform: scale(1); text-shadow: 0 0 40px rgba(251,191,36,.45); }
            50%       { transform: scale(1.04); text-shadow: 0 0 60px rgba(251,191,36,.6); }
        }

        .nama-dipanggil {
            font-size: clamp(1.5rem, 3.5vw, 2.5rem);
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
            animation: fadeUp .5s ease .2s both;
            letter-spacing: -.5px;
        }

        .vendor-dipanggil {
            font-size: 1.1rem;
            color: var(--pa-purple2);
            font-weight: 600;
            margin-bottom: 6px;
            animation: fadeUp .5s ease .25s both;
        }

        .loket-text {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: 1rem;
            font-weight: 700;
            color: #00d2ff;
            margin-top: 16px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            animation: fadeUp .5s ease .3s both;
        }

        /* ── STATE KOSONG ─────────────────────── */
        .state-kosong {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 60px 24px;
            color: rgba(255,255,255,.35);
            min-height: 400px;
        }
        .state-kosong i { font-size: 5rem; }
        .state-kosong p { font-size: 1rem; font-weight: 500; }

        /* ── PANEL KANAN ─────────────────────── */
        .panel-kanan {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .stat-card {
            background: rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 18px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid var(--pa-border);
            animation: fadeUp .5s ease both;
        }
        .stat-card:nth-child(1) { animation-delay: .1s; }
        .stat-card:nth-child(2) { animation-delay: .2s; }
        .stat-card:nth-child(3) { animation-delay: .3s; }
        .stat-card:nth-child(4) { animation-delay: .4s; }

        .stat-card .stat-angka {
            font-size: 3rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-card .stat-label {
            font-size: .68rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,.55);
            font-weight: 700;
        }
        .stat-card.cyan    .stat-angka { color: #00d2ff; }
        .stat-card.yellow .stat-angka { color: var(--pa-gold); }
        .stat-card.green  .stat-angka { color: var(--pa-green); }
        .stat-card.purple .stat-angka { color: var(--pa-purple2); }

        /* ── DAFTAR SELANJUTNYA ───────────────── */
        .daftar-card {
            background: rgba(255,255,255,0.06);
            border-radius: 18px;
            padding: 18px;
            backdrop-filter: blur(10px);
            border: 1px solid var(--pa-border);
            animation: fadeUp .5s ease .4s both;
        }
        .daftar-header {
            font-size: .72rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255,255,255,.5);
            font-weight: 700;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .nomor-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 0;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .nomor-item:last-child { border-bottom: none; }
        .nomor-badge {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: rgba(154,85,255,.25);
            border: 1.5px solid rgba(154,85,255,.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: .85rem;
            flex-shrink: 0;
            color: var(--pa-purple2);
        }
        .nomor-item-nama {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
        }
        .nomor-item-vendor {
            font-size: .72rem;
            color: rgba(255,255,255,.45);
            font-weight: 500;
        }

        /* ── FOOTER ───────────────────────────── */
        .papan-footer {
            grid-column: 1 / -1;
            text-align: center;
            padding: 10px;
            color: rgba(255,255,255,.2);
            font-size: .72rem;
            letter-spacing: 1.5px;
        }

        /* ── ANIMASI ──────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn .45s ease-in both;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ─────────────────────── */
        @media (max-width: 900px) {
            .papan-wrapper { grid-template-columns: 1fr; }
            .nomor-besar { font-size: 90px; }
        }
        @media (max-width: 600px) {
            .nomor-besar { font-size: 72px; }
            .nama-dipanggil { font-size: 1.4rem; }
            .stat-card .stat-angka { font-size: 2.2rem; }
        }
    </style>
</head>
<body>

<div class="papan-wrapper">

    {{-- ══ HEADER ════════════════════════════════════════ --}}
    <header class="papan-header">
        <div class="header-icon-wrap">
            <i class="mdi mdi-book-open-page-variant"></i>
        </div>
        <div>
            <div class="header-title">KANTIN BUKU</div>
            <div class="header-subtitle">Sistem Antrian Pengambilan Pesanan</div>
        </div>
    </header>

    {{-- ══ KOTAK NOMOR DIPANGGIL ════════════════════════ --}}
    <section class="kotak-dipanggil" id="section-dipanggil">
        @if($dipanggil)
            <div class="label-dipanggil">
                <i class="mdi mdi-bullhorn"></i> NOMOR YANG DIPANGGIL
            </div>
            <div class="nomor-besar fade-in" id="nomor-dipanggil">
                {{ $dipanggil->nomor }}
            </div>
            <div class="nama-dipanggil fade-in" id="nama-dipanggil">
                {{ $dipanggil->nama }}
            </div>
            <div class="vendor-dipanggil fade-in" id="vendor-dipanggil">
                {{ $dipanggil->vendor->nama_vendor ?? '-' }}
            </div>
            <div class="loket-text fade-in">
                <i class="mdi mdi-store"></i> SILAKAN KE LOKET PENGAMBILAN
            </div>
        @else
            <div class="state-kosong">
                <i class="mdi mdi-animation-outline"></i>
                <p>Belum ada nomor yang dipanggil</p>
            </div>
        @endif
    </section>

    {{-- ══ PANEL KANAN ═══════════════════════════════════ --}}
    <aside class="panel-kanan">

        {{-- Stat: Menunggu --}}
        <div class="stat-card cyan">
            <div class="stat-angka" id="stat-menunggu">{{ $stats['menunggu'] }}</div>
            <div class="stat-label">Sedang Menunggu</div>
        </div>

        {{-- Stat: Terlambat --}}
        <div class="stat-card yellow">
            <div class="stat-angka" id="stat-terlambat">{{ $stats['terlambat'] }}</div>
            <div class="stat-label">Terlambat</div>
        </div>

        {{-- Stat: Selesai --}}
        <div class="stat-card green">
            <div class="stat-angka" id="stat-selesai">{{ $stats['selesai'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>

        {{-- Stat: Total Hari Ini --}}
        <div class="stat-card purple">
            <div class="stat-angka" id="stat-total">
                {{ $stats['menunggu'] + $stats['dipanggil'] + $stats['terlambat'] + $stats['selesai'] }}
            </div>
            <div class="stat-label">Total Hari Ini</div>
        </div>

        {{-- Daftar nomor selanjutnya --}}
        <div class="daftar-card">
            <div class="daftar-header">
                <i class="mdi mdi-format-list-numbered"></i> NOMOR SELANJUTNYA
            </div>
            <div id="daftar-selanjutan">
                @forelse($menunggu as $antrian)
                    <div class="nomor-item">
                        <div class="nomor-badge">{{ $antrian->nomor }}</div>
                        <div>
                            <div class="nomor-item-nama">{{ $antrian->nama }}</div>
                            <div class="nomor-item-vendor">
                                {{ $antrian->vendor->nama_vendor ?? '-' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center" style="color:rgba(255,255,255,.3);font-size:.82rem;padding:.5rem 0;">
                        Tidak ada antrian
                    </p>
                @endforelse
            </div>
        </div>

    </aside>

    {{-- ══ FOOTER ════════════════════════════════════════ --}}
    <footer class="papan-footer">
        Kantin Buku — Powered by Server-Sent Events (SSE) & Laravel
    </footer>

</div>

{{-- ══ AUDIO & JAVASCRIPT ════════════════════════════════ --}}
<audio id="audio-dingdong" src="{{ asset('sounds/dingdong.mp3') }}" preload="auto"></audio>

<script>
    /* ── SSE Real-time Update ──────────────────────── */
    (function() {
        var lastDipanggilId = null;
        var tabId  = Date.now() + Math.random().toString(36).substr(2, 5);
        var sseUrl = '{{ route('antrian.sse') }}?tab=papan&id=' + tabId;
        var source = new EventSource(sseUrl);

        source.addEventListener('queue-update', function(event) {
            var data = JSON.parse(event.data);

            // Update statistik
            if (data.stats) {
                var elMenunggu  = document.getElementById('stat-menunggu');
                var elTerlambat = document.getElementById('stat-terlambat');
                var elSelesai   = document.getElementById('stat-selesai');
                var elTotal     = document.getElementById('stat-total');
                if (elMenunggu)  elMenunggu.textContent  = data.stats.menunggu  ?? 0;
                if (elTerlambat) elTerlambat.textContent = data.stats.terlambat ?? 0;
                if (elSelesai)   elSelesai.textContent   = data.stats.selesai   ?? 0;
                if (elTotal) {
                    var total = (data.stats.menunggu  ?? 0)
                                + (data.stats.dipanggil ?? 0)
                                + (data.stats.terlambat ?? 0)
                                + (data.stats.selesai   ?? 0);
                    elTotal.textContent = total;
                }
            }

            // Cek apakah ada nomor baru yang dipanggil
            if (data.dipanggil) {
                var idSekarang = data.dipanggil.id;

                if (idSekarang !== lastDipanggilId) {
                    lastDipanggilId = idSekarang;

                    updateDipanggilDisplay(
                        data.dipanggil.nomor,
                        data.dipanggil.nama,
                        data.dipanggil.vendor
                    );

                    playSoundAndSpeech(
                        data.dipanggil.nomor,
                        data.dipanggil.nama,
                        data.dipanggil.vendor
                    );
                }
            }

            // Update daftar nomor selanjutnya
            if (data.menunggu && data.menunggu.length > 0) {
                updateDaftarSelanjutan(data.menunggu);
            }
        });

        source.onerror = function() {
            console.warn('SSE connection lost, browser will auto-reconnect...');
            // Browser EventSource otomatis reconnect, tidak perlu reload page
        };

        // Tutup koneksi SSE saat tab di-close
        window.addEventListener('beforeunload', function() { source.close(); });
        window.addEventListener('pagehide',    function() { source.close(); });
    };

    /* ── Update Tampilan Nomor Dipanggil ─────────── */
    function updateDipanggilDisplay(nomor, nama, vendorNama) {
        const section = document.getElementById('section-dipanggil');
        const elNomor  = document.getElementById('nomor-dipanggil');
        const elNama   = document.getElementById('nama-dipanggil');
        const elVendor = document.getElementById('vendor-dipanggil');

        if (!elNomor) {
            section.innerHTML = `
                <div class="label-dipanggil fade-in">
                    <i class="mdi mdi-bullhorn"></i> NOMOR YANG DIPANGGIL
                </div>
                <div class="nomor-besar fade-in" id="nomor-dipanggil">${nomor}</div>
                <div class="nama-dipanggil fade-in" id="nama-dipanggil">${nama}</div>
                <div class="vendor-dipanggil fade-in" id="vendor-dipanggil">${vendorNama || '-'}</div>
                <div class="loket-text fade-in">
                    <i class="mdi mdi-store"></i> SILAKAN KE LOKET PENGAMBILAN
                </div>
            `;
        } else {
            elNomor.textContent   = nomor;
            elNama.textContent   = nama;
            elVendor.textContent = vendorNama || '-';

            [elNomor, elNama, elVendor].forEach(el => {
                if (el) {
                    el.classList.remove('fade-in');
                    void el.offsetWidth;
                    el.classList.add('fade-in');
                }
            });
        }
    }

    /* ── Update Daftar Nomor Selanjutnya ─────────── */
    function updateDaftarSelanjutan(menungguList) {
        const container = document.getElementById('daftar-selanjutan');
        if (!container) return;

        let html = '';
        (menungguList || []).slice(0, 6).forEach(item => {
            html += `
                <div class="nomor-item">
                    <div class="nomor-badge">${item.nomor}</div>
                    <div>
                        <div class="nomor-item-nama">${item.nama}</div>
                        <div class="nomor-item-vendor">${item.vendor || '-'}</div>
                    </div>
                </div>
            `;
        });

        if (!html) {
            html = `<p class="text-center" style="color:rgba(255,255,255,.3);font-size:.82rem;padding:.5rem 0;">
                Tidak ada antrian
            </p>`;
        }
        container.innerHTML = html;
    }

    /* ── Audio + Web Speech API ────────────────────��� */
    function playSoundAndSpeech(nomor, nama, vendorNama) {
        const audio = document.getElementById('audio-dingdong');
        if (audio) {
            audio.currentTime = 0;
            audio.play().catch(err => console.warn('Audio play failed:', err));
            audio.onended = function() {
                triggerWebSpeech(nomor, nama, vendorNama);
            };
        } else {
            triggerWebSpeech(nomor, nama, vendorNama);
        }
    }

    function triggerWebSpeech(nomor, nama, vendorNama) {
        if (!('speechSynthesis' in window)) return;
        window.speechSynthesis.cancel();

        const utterance = new SpeechSynthesisUtterance(
            `Nomor antrian ${nomor}. ${nama}. ${vendorNama ? 'Silakan menuju ' + vendorNama : 'Silakan menuju loket'}.`
        );
        utterance.lang   = 'id-ID';
        utterance.rate   = 0.85;
        utterance.pitch  = 1.0;
        utterance.volume = 1.0;
        window.speechSynthesis.speak(utterance);
    }
</script>

</body>
</html>