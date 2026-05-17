<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomor Antrian Saya — Kantin Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ══ DESIGN TOKENS ══ */
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
            --pa-gold:     #fbbf24;
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
        .saya-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }
        .saya-card {
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
        .info-box .info-value.text-red    { color: #f87171; }

        /* Status box */
        .status-box {
            background: rgba(255,255,255,.06);
            border-radius: 16px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            margin: .5rem 0 1.5rem;
            font-size: .9rem;
            font-weight: 700;
            border: 1px solid rgba(255,255,255,.1);
        }
        .status-box .dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--pa-gold);
            animation: blinkDot 1.5s ease-in-out infinite;
        }
        @keyframes blinkDot {
            0%,100% { opacity:1; }
            50%      { opacity:.3; }
        }
        .status-box.dipanggil {
            background: rgba(251,191,36,.15);
            border-color: rgba(251,191,36,.3);
            color: var(--pa-gold);
        }

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
        }
        .instruksi-box i { color: var(--pa-purple2); margin-top: 2px; font-size: 1rem; }
        .instruksi-box.success {
            background: rgba(16,185,129,.15);
            border-color: rgba(16,185,129,.3);
        }
        .instruksi-box.success i { color: #10b981; }

        .divider-line {
            height: 1px;
            background: rgba(255,255,255,.1);
            margin: 1.5rem 0;
        }

        /* Vendor info */
        .vendor-line {
            font-size: .8rem;
            color: var(--pa-purple2);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .vendor-line i { margin-right: 4px; }

        /* Nomor dipanggil saat ini */
        .now-calling {
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.25);
            border-radius: 12px;
            padding: 10px 16px;
            font-size: .78rem;
            color: rgba(255,255,255,.6);
            font-weight: 600;
            letter-spacing: .5px;
        }
        .now-calling .nc-nomor {
            font-size: 1.2rem;
            font-weight: 900;
            color: #f87171;
            letter-spacing: 2px;
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
    <div class="saya-wrapper">
        <div class="saya-card">
            {{-- Badge atas --}}
            <div class="badge-top">
                <i class="mdi mdi-ticket-confirmation"></i>
                Nomor Antrian Anda
            </div>

            {{-- Nomor besar gold --}}
            <div class="nomor-display" id="nomor-antrian-display">
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
                    <div class="info-label">Posisi</div>
                    <div class="info-value text-purple" id="posisi-display">{{ $posisi }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Status</div>
                    <div class="info-value text-purple" id="status-display">
                        <i class="mdi mdi-clock-outline"></i> Menunggu
                    </div>
                </div>
                <div class="info-box" style="grid-column:1/-1;">
                    <div class="info-label">Nomor Sedang Dipanggil</div>
                    <div class="now-calling">
                        <span id="nomor-dipanggil-display">—</span>
                    </div>
                </div>
            </div>

            {{-- Status box --}}
            <div class="status-box" id="status-box">
                <div class="dot"></div>
                <span id="status-text">Mohon tunggu, kami akan memanggil Anda...</span>
            </div>

            {{-- Instruksi --}}
            <div class="instruksi-box" id="instruksi-box">
                <i class="mdi mdi-information-outline"></i>
                <span>Halaman ini otomatis memperbarui. Pastikan volume suara perangkat Anda aktif agar notifikasi panggilan terdengar.</span>
            </div>
        </div>
    </div>

</body>

{{-- ══ AUDIO — Dingdong sound ══ --}}
<audio id="audio-dingdong" src="{{ asset('sounds/dingdong.mp3') }}" preload="auto"></audio>

<script>
    // ================================================================
    // SSE: Real-time Update
    // ================================================================
    const source = new EventSource('{{ route('antrian.sse') }}');

    const myId    = {{ $antrian->id }};
    const myNomor = '{{ $antrian->nomor }}';
    let isCalled  = false;

    source.addEventListener('queue-update', function(event) {
        const data = JSON.parse(event.data);

        // 1. Update status bar — hitung posisi
        if (data.stats && data.stats.menunggu !== undefined) {
            // Posisi = jumlah menunggu dengan nomor <= nomor kita
            // (Untuk simplicity, tampilkan total menunggu)
            const el = document.getElementById('posisi-display');
            if (el) el.textContent = data.stats.menunggu;
        }

        // 2. Update nomor yang sedang dipanggil (box kanan)
        if (data.dipanggil && data.dipanggil.nomor) {
            const el = document.getElementById('nomor-dipanggil-display');
            if (el) el.textContent = data.dipanggil.nomor;
        }

        // 3. Cek apakah ANDA yang dipanggil → trigger audio + speech
        if (data.dipanggil && data.dipanggil.id === myId && !isCalled) {
            isCalled = true;
            updateToDipanggilState(data.dipanggil.nomor);
            playSoundAndSpeech(data.dipanggil.nomor, data.dipanggil.nama, data.dipanggil.vendor);
        }

        // 4. Jika dipanggil tapi belum dipanggil (ada orang lain dipanggil, posisi berubah)
        if (data.dipanggil && data.dipanggil.id !== myId) {
            const el = document.getElementById('nomor-dipanggil-display');
            if (el) el.textContent = data.dipanggil.nomor;
        }
    });

    source.onerror = function(error) {
        console.warn('SSE error, will retry...');
    };

    // ================================================================
    // Update UI saat dipanggil
    // ================================================================
    function updateToDipanggilState(nomor) {
        const statusBox = document.getElementById('status-box');
        const statusText = document.getElementById('status-text');
        const instruksiBox = document.getElementById('instruksi-box');

        statusBox.classList.add('dipanggil');
        statusBox.innerHTML = '<i class="mdi mdi-bullhorn" style="font-size:1.1rem;"></i><span>Nomor Anda sedang dipanggil!</span>';

        instruksiBox.classList.add('success');
        instruksiBox.innerHTML = '<i class="mdi mdi-check-circle"></i><span>Segera menuju loket pengambilan buku.</span>';
    }

    // ================================================================
    // Audio dingdong + Web Speech API
    // ================================================================
    function playSoundAndSpeech(nomor, nama, vendorNama) {
        const audio = document.getElementById('audio-dingdong');
        if (!audio) return;

        audio.currentTime = 0;
        audio.play().catch(err => {
            // Jika autoplay diblokir browser, fallback langsung ke speech
            console.warn('Audio autoplay blocked, using speech only:', err);
            triggerWebSpeech(nomor, nama, vendorNama);
        });

        audio.onended = function() {
            triggerWebSpeech(nomor, nama, vendorNama);
        };
    }

    function triggerWebSpeech(nomor, nama, vendorNama) {
        if (!('speechSynthesis' in window)) return;
        window.speechSynthesis.cancel();

        const vendorText = vendorNama ? ` di ${vendorNama}` : ' ke loket';
        const utterance = new SpeechSynthesisUtterance(
            `Nomor antrian ${nomor}. ${nama}. Silakan menuju${vendorText}.`
        );
        utterance.lang   = 'id-ID';
        utterance.rate   = 0.85;
        utterance.pitch  = 1.0;
        utterance.volume = 1.0;
        window.speechSynthesis.speak(utterance);
    }
</script>
</html>