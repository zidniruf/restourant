<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Scanner Check-in</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        :root {
            --orange: #f97316;
            --orange-dark: #ea6e0c;
            --green: #16a34a;
            --red: #dc2626;
            --yellow: #d97706;
            --blue: #2563eb;
            --bg: #f1f5f9;
            --card: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --light: #f8fafc;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            min-height: 100vh;
            color: var(--text);
        }

        /* ── NAVBAR ── */
        nav {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-icon {
            width: 28px;
            height: 28px;
            background: var(--orange);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .nav-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .nav-back {
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid var(--border);
            border-radius: 6px;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-back:hover { background: var(--light); color: var(--text); }

        /* ── LAYOUT ── */
        .container {
            max-width: 440px;
            margin: 0 auto;
            padding: 16px;
        }

        /* ── STATUS PILL ── */
        .status-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 14px;
            transition: all 0.3s;
        }

        .sp-idle    { background:#eff6ff; color:#3b82f6; border:1px solid #bfdbfe; }
        .sp-active  { background:#f0fdf4; color:#16a34a; border:1px solid #86efac; }
        .sp-success { background:#f0fdf4; color:#16a34a; border:1px solid #86efac; }
        .sp-warning { background:#fffbeb; color:#d97706; border:1px solid #fcd34d; }
        .sp-error   { background:#fef2f2; color:#dc2626; border:1px solid #fca5a5; }

        .sp-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }

        .sp-dot.pulse { animation: dotPulse 1.5s ease-in-out infinite; }

        @keyframes dotPulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.3; transform:scale(0.7); }
        }

        /* ── SCANNER CARD ── */
        .scanner-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .scanner-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .scanner-header-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--orange);
        }

        .scanner-header span {
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        #reader { width: 100%; }

        /* Override html5-qrcode default styles */
        #reader__header_message,
        #reader__filescan_input { display: none !important; }

        #reader > div:first-child { border: none !important; }
        #reader video { border-radius: 0 !important; width: 100% !important; }
        #reader__scan_region { background: #000 !important; min-height: 200px; }

        #reader__dashboard {
            background: var(--card) !important;
            padding: 14px 16px !important;
            border-top: 1px solid var(--border) !important;
        }

        #reader__dashboard_section_csr button {
            background: var(--orange) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 10px 0 !important;
            width: 100% !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            letter-spacing: 0.03em !important;
        }

        #reader__dashboard_section_csr button:hover {
            background: var(--orange-dark) !important;
        }

        #reader__dashboard_section_swaplink {
            color: var(--muted) !important;
            font-size: 11px !important;
            display: block !important;
            text-align: center !important;
            margin-top: 8px !important;
        }

        #reader__status_span {
            color: var(--muted) !important;
            font-size: 11px !important;
        }

        /* ── LOADING ── */
        .loading-box {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 36px 20px;
            text-align: center;
            margin-bottom: 14px;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .spinner {
            width: 36px;
            height: 36px;
            border: 3px solid var(--border);
            border-top-color: var(--orange);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin: 0 auto 14px;
        }

        @keyframes spin { to { transform:rotate(360deg); } }

        .loading-box p {
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
        }

        /* ── RESULT CARD ── */
        .result-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 14px;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .result-card.show { display: block; }

        .result-top {
            padding: 28px 20px 20px;
            text-align: center;
            border-bottom: 1px solid var(--border);
        }

        .result-icon { font-size: 48px; margin-bottom: 10px; line-height: 1; }

        .result-label {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .rl-success { color: var(--green); }
        .rl-warning { color: var(--yellow); }
        .rl-error   { color: var(--red); }

        /* ── DETAIL ── */
        .detail-box {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: none;
        }

        .detail-heading {
            font-size: 10px;
            font-weight: 800;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 10px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid var(--light);
            font-size: 13px;
            gap: 10px;
        }

        .detail-item:last-child { border-bottom: none; }
        .di-label { color: var(--muted); flex-shrink: 0; }
        .di-value { color: var(--text); font-weight: 600; text-align: right; }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .chip-green { background:#dcfce7; color:#16a34a; }
        .chip-blue  { background:#dbeafe; color:#1d4ed8; }

        /* ── ACTIONS ── */
        .action-box {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
            letter-spacing: 0.01em;
        }

        .btn-primary { background:var(--orange); color:#fff; }
        .btn-primary:hover { background:var(--orange-dark); }
        .btn-ghost { background:transparent; color:var(--muted); border:1px solid var(--border); }
        .btn-ghost:hover { background:var(--light); color:var(--text); }

        /* ── TIPS ── */
        .tips-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .tips-heading {
            font-size: 10px;
            font-weight: 800;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 10px;
        }

        .tips-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .tips-list li {
            font-size: 12px;
            color: var(--muted);
            display: flex;
            gap: 7px;
            align-items: flex-start;
            line-height: 1.4;
        }

        .tips-list li::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--orange);
            flex-shrink: 0;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand">
        <div class="nav-icon">📷</div>
        <span class="nav-title">Scanner Check-in</span>
    </div>
    <a href="/admin" class="nav-back">← Dashboard</a>
</nav>

<div class="container">

    {{-- STATUS --}}
    <div class="status-pill sp-idle" id="statusPill">
        <div class="sp-dot" id="spDot"></div>
        <span id="spText">Siap memulai scan</span>
    </div>

    {{-- SCANNER --}}
    <div class="scanner-card" id="scannerCard">
        <div class="scanner-header">
            <div class="scanner-header-dot"></div>
            <span>Kamera Scanner</span>
        </div>
        <div id="reader"></div>
    </div>

    {{-- LOADING --}}
    <div class="loading-box" id="loadingBox">
        <div class="spinner"></div>
        <p>Memverifikasi QR Code...</p>
    </div>

    {{-- RESULT --}}
    <div class="result-card" id="resultCard">
        <div class="result-top">
            <div class="result-icon" id="rIcon"></div>
            <div class="result-label" id="rLabel"></div>
        </div>
        <div class="detail-box" id="detailBox">
            <div class="detail-heading">Detail Reservasi</div>
            <div id="detailContent"></div>
        </div>
        <div class="action-box">
            <button class="btn btn-primary" onclick="resetScanner()">
                📷 Scan Berikutnya
            </button>
            <button class="btn btn-ghost" onclick="location.href='/admin/bookings'">
                📋 Lihat Semua Booking
            </button>
        </div>
    </div>

    {{-- TIPS --}}
    <div class="tips-card" id="tipsCard">
        <div class="tips-heading">Panduan Penggunaan</div>
        <ul class="tips-list">
            <li>Klik "Start Scanning" lalu izinkan akses kamera</li>
            <li>Arahkan kamera ke QR Code pada struk pelanggan</li>
            <li>Sistem otomatis memverifikasi dan mencatat check-in</li>
            <li>Pastikan QR Code terlihat jelas dan pencahayaan cukup</li>
        </ul>
    </div>

</div>

<script>
let scanner, isProcessing = false;

function startScanner() {
    document.getElementById('scannerCard').style.display = 'block';
    document.getElementById('resultCard').classList.remove('show');
    document.getElementById('loadingBox').style.display = 'none';
    document.getElementById('tipsCard').style.display = 'block';
    setStatus('active', 'Scanner aktif — arahkan ke QR Code');

    scanner = new Html5QrcodeScanner('reader', {
        fps: 10,
        qrbox: { width: 220, height: 220 },
        rememberLastUsedCamera: true,
        showTorchButtonIfSupported: true,
    });

    scanner.render(
        function(decoded) {
            if (isProcessing) return;
            isProcessing = true;
            scanner.clear();
            processQr(decoded);
        },
        function() {}
    );
}

async function processQr(url) {
    document.getElementById('scannerCard').style.display = 'none';
    document.getElementById('loadingBox').style.display = 'block';
    document.getElementById('tipsCard').style.display = 'none';
    setStatus('active', 'Memverifikasi...');

    // Ekstrak token dari URL
    let token = url;
    try {
        const u = new URL(url);
        const parts = u.pathname.split('/').filter(Boolean);
        token = parts[parts.length - 1];
    } catch(e) {
        token = url.split('/').pop().split('?')[0];
    }

    try {
        const res  = await fetch('/admin/booking/checkin/' + token, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        const data = await res.json();
        document.getElementById('loadingBox').style.display = 'none';
        showResult(data);
    } catch(e) {
        document.getElementById('loadingBox').style.display = 'none';
        showResult({ status:'error', message:'Gagal terhubung ke server.' });
    }
}

function showResult(data) {
    const card   = document.getElementById('resultCard');
    const icon   = document.getElementById('rIcon');
    const label  = document.getElementById('rLabel');
    const detail = document.getElementById('detailBox');
    const content= document.getElementById('detailContent');

    card.classList.add('show');

    // Notifikasi browser + suara
    if (typeof window.notifyCheckin === 'function') {
        window.notifyCheckin(data.status === 'success', data.message || '');
    }

    const cfg = {
        success: { icon:'✅', label:'Check-in Berhasil!',       cls:'rl-success', status:'success', msg:'✅ Check-in berhasil dicatat' },
        already: { icon:'⚠️', label:'Sudah Check-in Sebelumnya', cls:'rl-warning', status:'warning', msg:'⚠️ QR sudah pernah digunakan' },
        error:   { icon:'❌', label: data.message||'Gagal',      cls:'rl-error',   status:'error',   msg:'❌ Verifikasi gagal' },
    };

    const c = cfg[data.status] || cfg.error;
    icon.textContent  = c.icon;
    label.textContent = c.label;
    label.className   = 'result-label ' + c.cls;
    setStatus(c.status, c.msg);

    if (data.booking) {
        const b = data.booking;
        detail.style.display = 'block';
        content.innerHTML = [
            ['Nama',        b.name],
            ['WhatsApp',    b.phone],
            ['Tempat',        b.table],
            ['Sesi',        b.session],
            ['Tanggal',     b.booking_date],
            ['Tamu',        b.guest_count + ' orang'],
            ['Pembayaran',  `<span class="chip chip-green">✅ Lunas DP</span>`],
            b.checked_in_at ? ['Check-in', `<span class="chip chip-blue">🕐 ${b.checked_in_at}</span>`] : null,
        ].filter(Boolean).map(([l,v]) => `
            <div class="detail-item">
                <span class="di-label">${l}</span>
                <span class="di-value">${v}</span>
            </div>
        `).join('');
    } else {
        detail.style.display = 'none';
    }
}

function resetScanner() {
    isProcessing = false;
    document.getElementById('resultCard').classList.remove('show');
    startScanner();
}

function setStatus(type, text) {
    const pill = document.getElementById('statusPill');
    const dot  = document.getElementById('spDot');
    pill.className = 'status-pill sp-' + type;
    document.getElementById('spText').textContent = text;
    dot.classList.toggle('pulse', type === 'active');
}

startScanner();
</script>
</body>
</html>