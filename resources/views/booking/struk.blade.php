<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Reservasi #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Lato', sans-serif;
            background: #FAF6F0;
            color: #1C1007;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .wrap { width: 100%; max-width: 400px; }

        /* ── STRUK KASIR — tetap putih untuk cetak/download ── */
        .receipt-container {
            padding: 20px;
            background: #FAF6F0;
        }

        .receipt {
            background: #ffffff;
            color: #111;
            padding: 32px 24px;
            position: relative;
            box-shadow: 0 8px 32px rgba(28,16,7,0.12);
            font-family: 'Courier Prime', monospace;
        }

        .receipt::before, .receipt::after {
            content: "";
            position: absolute;
            left: 0; right: 0; height: 6px;
            background-size: 12px 12px;
        }
        .receipt::before {
            top: -6px;
            background-image: radial-gradient(circle at 6px 0, transparent 6px, #ffffff 7px);
        }
        .receipt::after {
            bottom: -6px;
            background-image: radial-gradient(circle at 6px 12px, transparent 6px, #ffffff 7px);
        }

        .receipt-header { text-align: center; margin-bottom: 10px; }
        .receipt-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 20px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 3px;
            margin-bottom: 4px; color: #1C1007;
        }
        .receipt-header p {
            font-size: 11px; color: #6B5444;
            text-transform: uppercase; letter-spacing: 1px;
        }

        .divider {
            border: none;
            border-top: 1.5px dashed #C8965A;
            margin: 16px 0; opacity: 0.4;
        }

        .row {
            display: flex; justify-content: space-between;
            align-items: flex-start; font-size: 12px;
            margin-bottom: 6px; line-height: 1.4;
        }
        .row .label { color: #6B5444; min-width: 100px; }
        .row .value { color: #1C1007; font-weight: 700; text-align: right; }

        .total-row {
            display: flex; justify-content: space-between;
            font-size: 13px; margin-bottom: 6px;
        }
        .total-row.dp { font-weight: 700; color: #16a34a; }
        .total-row.sisa { font-weight: 700; font-size: 14px; margin-top: 8px; color: #c88e5b; }

        .paid-date {
            font-size: 10px; color: #6B5444;
            text-align: center; margin-top: 12px;
        }

        .qr-section {
            text-align: center; padding-top: 16px; margin-top: 16px;
        }
        .qr-section p {
            font-size: 10px; color: #6B5444; margin-bottom: 12px;
            font-family: 'Lato', sans-serif; text-transform: uppercase;
        }
        .qr-box { display: inline-block; padding: 8px; background: #fff; }
        .qr-box img { width: 140px; height: 140px; display: block; }
        .qr-token {
            font-size: 10px; color: #1C1007; margin-top: 8px;
            word-break: break-all; letter-spacing: 1px;
        }
        .barcode-dummy {
            font-size: 28px; letter-spacing: 2px; margin-top: 8px;
            color: #1C1007; opacity: 0.6; user-select: none;
        }

        .checkin-badge {
            background: transparent; border: 1.5px dashed #c88e5b;
            padding: 8px; text-align: center; color: #c88e5b;
            font-size: 11px; font-weight: 700;
            margin-top: 16px; text-transform: uppercase;
        }

        .receipt-footer {
            text-align: center; margin-top: 24px;
            font-size: 10px; color: #6B5444;
        }

        /* ── INFO CARD — light cream theme ── */
        .info-card {
            background: #fff;
            border: 1px solid #E2D0BA;
            border-radius: 24px;
            padding: 40px 24px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(28,16,7,0.08);
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: "";
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #c88e5b, transparent);
        }

        .info-card .icon { font-size: 48px; margin-bottom: 16px; }

        .info-card h1 {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 700;
            margin-bottom: 8px; letter-spacing: 1px;
            color: #c88e5b; text-transform: uppercase;
        }

        .info-card p {
            font-size: 13px; color: #6B5444;
            font-weight: 300; line-height: 1.6;
        }

        .alert-box {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 12px; padding: 12px 16px;
            font-size: 12px; color: #b91c1c;
            margin: 16px 0; font-weight: 300;
        }

        .pending-box {
            background: #F0E8DC;
            border: 1px solid #E2D0BA;
            border-radius: 12px; padding: 16px;
            font-size: 13px; color: #1C1007;
            margin: 16px 0; font-family: 'Lato', monospace;
        }

        /* ── TOMBOL ── */
        .btn-action {
            display: flex; align-items: center;
            justify-content: center; gap: 8px;
            width: 100%; margin-top: 20px;
            padding: 14px;
            background: #c88e5b; color: #fff;
            font-family: 'Lato', sans-serif;
            font-size: 13px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            border: none; border-radius: 12px;
            cursor: pointer; text-decoration: none;
            transition: background .3s, transform .2s;
            box-shadow: 0 4px 16px rgba(200,142,91,0.25);
        }
        .btn-action:hover { background: #b07a4a; transform: translateY(-1px); }

        .btn-back {
            display: block; text-align: center;
            margin-top: 14px; color: #6B5444;
            font-size: 11px; text-decoration: none;
            text-transform: uppercase; letter-spacing: 1px;
            font-family: 'Lato', sans-serif; font-weight: 300;
            transition: color .2s;
        }
        .btn-back:hover { color: #c88e5b; }
    </style>
</head>

<body>
<div class="wrap">

{{-- ── SUDAH BAYAR → Struk ── --}}
@if($booking->payment_status === 'paid' || in_array($booking->status, ['approved', 'confirmed']))

<div class="receipt-container" id="receipt-container">
<div class="receipt" id="receipt">

    <div class="receipt-header">
        <h1>{{ strtoupper(isset($setting) && $setting->site_name ? $setting->site_name : 'RESTAURANT') }}</h1>
        <p>Reservation Receipt</p>
    </div>

    <div class="divider"></div>

    {{-- INFO CUSTOMER --}}
    <div class="row">
        <span class="label">ORDER ID</span>
        <span class="value">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="row">
        <span class="label">CUSTOMER</span>
        <span class="value">{{ $booking->name }}</span>
    </div>
    <div class="row">
        <span class="label">PHONE</span>
        <span class="value">{{ $booking->phone }}</span>
    </div>

    <div class="divider"></div>

    {{-- DETAIL RESERVASI --}}
    <div class="row">
        <span class="label">TANGGAL</span>
        <span class="value">{{ $booking->booking_date->translatedFormat('d M Y') }}</span>
    </div>
    <div class="row">
        <span class="label">TEMPAT</span>
        <span class="value">{{ $booking->table->name ?? '-' }}</span>
    </div>
    @if($booking->session)
    <div class="row">
        <span class="label">SESI</span>
        <span class="value">
            {{ $booking->session->name }}
            ({{ substr($booking->session->start_time, 0, 5) }}–{{ substr($booking->session->end_time, 0, 5) }})
        </span>
    </div>
    @endif
    @if($booking->event_time)
    <div class="row">
        <span class="label">JAM ACARA</span>
        <span class="value">{{ substr($booking->event_time, 0, 5) }} WIB</span>
    </div>
    @endif
    @if($booking->food_ready_time)
    <div class="row">
        <span class="label">MAKAN SIAP</span>
        <span class="value">{{ substr($booking->food_ready_time, 0, 5) }} WIB</span>
    </div>
    @endif
    <div class="row">
        <span class="label">JUMLAH TAMU</span>
        <span class="value">{{ $booking->guest_count }} Orang</span>
    </div>
    @if($booking->note)
    <div class="row">
        <span class="label">CATATAN</span>
        <span class="value" style="max-width:200px; word-break:break-word; white-space:normal;">{{ $booking->note }}</span>
    </div>
    @endif

    <div class="divider"></div>

    {{-- MENU --}}
    @if($booking->items && $booking->items->count() > 0)
    <div style="font-size:11px; color:#6B5444; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
        Pesanan Menu
    </div>
    @foreach($booking->items as $item)
    <div class="row">
        <span class="label" style="min-width:130px;">
            {{ $item->item->title ?? $item->title ?? '-' }}
            <span style="color:#c88e5b;"> x{{ $item->qty }}</span>
        </span>
        <span class="value">Rp {{ number_format(($item->price ?? 0) * $item->qty, 0, ',', '.') }}</span>
    </div>
    @endforeach

    <div class="divider"></div>

<div class="total-row" style="font-weight:700; font-size:14px;">
    <span>TOTAL</span>
    <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
</div>

@if($booking->dp_amount)
<div class="total-row dp">
    <span>DP DIBAYAR</span>
    <span>Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
</div>
<div class="total-row sisa">
    <span>SISA BAYAR</span>
    <span>Rp {{ number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') }}</span>
</div>
@endif

@else
    <div style="font-size:11px; color:#B5A090; text-align:center; padding:8px 0;">
        (Tidak memesan menu)
    </div>
    @endif

    <div class="divider"></div>


    {{-- QR CODE (hanya muncul setelah lunas) --}}
    @if($booking->qr_code_path)
    <div class="qr-section">
        <p>Tunjukkan QR ini saat tiba</p>
        <div class="qr-box">
            {!! file_get_contents(storage_path('app/public/' . $booking->qr_code_path)) !!}
        </div>
        <div class="qr-token">{{ strtoupper(substr($booking->unique_token, 0, 16)) }}</div>
    </div>
    @endif

    @if($booking->checkin_status === 'checked_in')
    <div class="checkin-badge">
        [ CHECKED IN: {{ $booking->checked_in_at?->format('d M Y, H:i') }} ]
    </div>
    @endif

    <div class="receipt-footer">
        Harap tiba 15 menit sebelum acara dimulai.<br>
        Terima kasih telah memilih {{ isset($setting) ? $setting->site_name : 'kami' }}.
    </div>

</div>
</div>

<button class="btn-action btn-download" onclick="downloadPNG()">
    ⬇ Download Struk (PNG)
</button>
<a href="/" class="btn-back">← Kembali ke Beranda</a>


{{-- ── DITOLAK ── --}}
@elseif($booking->status === 'rejected')

    <div class="info-card">
        <div class="icon">❌</div>
        <h1 style="color:#dc2626;">Reservasi Ditolak</h1>
        <p>Mohon maaf, reservasi Anda tidak dapat kami proses untuk saat ini.</p>

        @if($booking->reject_reason)
        <div class="alert-box">
            <strong>Alasan:</strong> {{ $booking->reject_reason }}
        </div>
        @endif

        <a href="/" class="btn-action">Coba Reservasi Lagi</a>
    </div>


{{-- ── DISETUJUI, BELUM BAYAR ── --}}
@elseif($booking->status === 'approved')

    <div class="info-card">
        <div class="icon">🥂</div>
        <h1>Reservasi Disetujui</h1>
        <p>Tempat Anda telah tersedia. Selesaikan pembayaran DP untuk mengkonfirmasi reservasi.</p>

        <div class="pending-box">
            <div style="font-size:10px;color:#c88e5b;margin-bottom:4px;text-transform:uppercase;letter-spacing:1px;">Total DP (50%)</div>
            <div style="font-size:22px;font-weight:700;font-family:'Playfair Display',serif;color:#1C1007;">
                Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
            </div>
        </div>

        @if($booking->payment_link)
        <a href="{{ $booking->payment_link }}" class="btn-action">
            💳 &nbsp; Bayar Sekarang
        </a>
        @endif

        <a href="/" class="btn-back">← Kembali ke Beranda</a>
    </div>


{{-- ── PENDING ── --}}
@else

    <div class="info-card">
        <div class="icon">⏳</div>
        <h1>Menunggu Konfirmasi</h1>
        <p>Reservasi Anda sedang ditinjau oleh tim kami untuk memastikan ketersediaan tempat.</p>

        <div class="pending-box">
            <div style="font-weight:700;color:#c88e5b;margin-bottom:4px;font-family:'Playfair Display',serif;">
                {{ strtoupper($booking->name) }}
            </div>
            <div style="font-size:12px;color:#6B5444;font-weight:300;">
                {{ $booking->booking_date->format('d M Y') }}
                @if($booking->session) — {{ $booking->session->name }} @endif
            </div>
        </div>

        <p style="font-size:11px;color:#B5A090;margin-top:16px;font-style:italic;">
            Halaman ini akan diperbarui otomatis...
        </p>
    </div>

    <script>setTimeout(function(){ location.reload(); }, 10000);</script>

@endif

</div>

{{-- Script Download — tidak diubah ── --}}
<script>
async function downloadPNG() {
    const btn = document.querySelector('.btn-download');
    const originalText = btn.textContent;
    btn.textContent = 'Menyiapkan...';
    btn.disabled = true;

    try {
        const receiptContainer = document.getElementById('receipt-container');
        const canvas = await html2canvas(receiptContainer, {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#FAF6F0',
        });

        const link = document.createElement('a');
        link.download = 'Receipt-{{ str_pad($booking->id, 5, "0", STR_PAD_LEFT) }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();

    } catch (e) {
        alert('Gagal mengunduh struk. Silakan coba lagi.');
        console.error(e);
    }

    btn.textContent = originalText;
    btn.disabled = false;
}
</script>

</body>
</html>