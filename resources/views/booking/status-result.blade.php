<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lato', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        @keyframes toastIn {
            from { opacity:0; transform:translateX(-50%) translateY(-16px) scale(0.97); }
            to   { opacity:1; transform:translateX(-50%) translateY(0) scale(1); }
        }
    </style>
</head>

<body class="bg-[#FAF6F0] text-[#1C1007] min-h-screen py-8 px-4">

{{-- TOAST: Di Luar Jam --}}
@if(session('outside_hours'))
<div id="toast-notif" style="
    position:fixed; top:16px; left:50%; transform:translateX(-50%);
    z-index:9999; width:min(420px,92vw);
    background:#fffbeb; border:1px solid #f59e0b; border-left:4px solid #f59e0b;
    border-radius:12px; padding:14px 16px;
    box-shadow:0 8px 24px rgba(28,16,7,0.10);
    display:flex; gap:12px; align-items:flex-start;
    animation:toastIn 0.35s cubic-bezier(.175,.885,.32,1.275);
">
    <div style="font-size:20px;flex-shrink:0;line-height:1">⏰</div>
    <div style="flex:1">
        <div style="font-size:11px;font-weight:700;color:#d97706;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">Di Luar Jam Operasional</div>
        <div style="font-size:13px;color:#92400e;line-height:1.5;">{{ session('outside_hours') }}</div>
    </div>
    <button onclick="closeToast()" style="color:#9ca3af;font-size:18px;background:none;border:none;cursor:pointer;flex-shrink:0;line-height:1;padding:0">×</button>
</div>
@endif

@if(session('success'))
<div id="toast-notif" style="
    position:fixed; top:16px; left:50%; transform:translateX(-50%);
    z-index:9999; width:min(420px,92vw);
    background:#f0fdf4; border:1px solid #10b981; border-left:4px solid #10b981;
    border-radius:12px; padding:14px 16px;
    box-shadow:0 8px 24px rgba(28,16,7,0.10);
    display:flex; gap:12px; align-items:center;
    animation:toastIn 0.35s cubic-bezier(.175,.885,.32,1.275);
">
    <div style="font-size:20px;flex-shrink:0">✅</div>
    <div style="flex:1;font-size:13px;color:#065f46;line-height:1.5;">{{ session('success') }}</div>
    <button onclick="closeToast()" style="color:#9ca3af;font-size:18px;background:none;border:none;cursor:pointer;flex-shrink:0">×</button>
</div>
@endif

<script>
function closeToast() {
    const t = document.getElementById('toast-notif');
    if (t) { t.style.opacity='0'; t.style.transition='opacity .3s'; setTimeout(()=>t.remove(),300); }
}
setTimeout(closeToast, 6000);
</script>

<div class="max-w-2xl mx-auto">

    {{-- Kembali --}}
    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[#6B5444] hover:text-[#c88e5b] text-[10px] uppercase tracking-widest mb-5 transition-colors duration-200">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    {{-- KARTU UTAMA --}}
    <div class="bg-white border border-[#E2D0BA] rounded-3xl overflow-hidden shadow-[0_4px_24px_rgba(28,16,7,0.08)]">

        {{-- Accent bar atas --}}
        <div class="h-1 w-full bg-gradient-to-r from-transparent via-[#c88e5b] to-transparent"></div>

        {{-- HEADER --}}
        <div class="border-b border-[#E2D0BA] px-6 py-8 text-center bg-[#FAF6F0]">
            <p class="text-[#c88e5b] text-[10px] uppercase tracking-[0.3em] mb-2" style="font-weight:300;">
                ✦ &nbsp; Reservasi &nbsp; ✦
            </p>
            <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#1C1007] tracking-widest uppercase">
                Status Reservasi
            </h1>
            <p class="text-[#6B5444] mt-1.5 text-xs" style="font-weight:300;">
                Berikut detail reservasi eksklusif Anda
            </p>
        </div>

        <div class="p-5 md:p-8 space-y-6">

            {{-- STATUS --}}
            @php
            $statusColor = match($booking->status) {
                'pending'   => 'bg-amber-50 border-amber-200 text-amber-700',
                'approved'  => 'bg-blue-50 border-blue-200 text-blue-700',
                'confirmed' => 'bg-purple-50 border-purple-200 text-purple-700',
                'paid'      => 'bg-green-50 border-green-200 text-green-700',
                'completed' => 'bg-[#F0E8DC] border-[#E2D0BA] text-[#1C1007]',
                'rejected'  => 'bg-red-50 border-red-200 text-red-600',
                'cancelled' => 'bg-gray-100 border-gray-200 text-gray-500',
                'expired'   => 'bg-gray-100 border-gray-200 text-gray-500',
                default     => 'bg-gray-100 border-gray-200 text-gray-500'
            };
            $statusLabel = match($booking->status) {
                'pending'   => '⏳ Menunggu Konfirmasi',
                'approved'  => '✅ Disetujui',
                'confirmed' => '💜 Dikonfirmasi',
                'paid'      => '💚 Sudah Bayar',
                'completed' => '🏁 Selesai',
                'rejected'  => '❌ Ditolak',
                'cancelled' => '🚫 Dibatalkan',
                'expired'   => '⌛ Kadaluarsa',
                default     => $booking->status
            };
            @endphp

            <div class="flex items-center justify-between flex-wrap gap-3 border border-[#E2D0BA] bg-[#FAF6F0] rounded-2xl p-4">
                <div>
                    <div class="text-[9px] text-[#6B5444] mb-1 uppercase tracking-widest font-bold">Status</div>
                    <div class="font-serif text-base font-bold text-[#1C1007] uppercase tracking-wide">
                        {{ $statusLabel }}
                    </div>
                </div>
                <span class="px-4 py-1.5 rounded-full text-[10px] font-bold border uppercase tracking-widest {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- DETAIL PELANGGAN --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-[#FAF6F0] border border-[#E2D0BA] rounded-xl p-4">
                    <div class="text-[9px] text-[#6B5444] mb-1.5 uppercase tracking-widest font-bold">Nama Pemesan</div>
                    <div class="font-serif text-sm font-bold text-[#1C1007] truncate">{{ $booking->name }}</div>
                </div>
                <div class="bg-[#FAF6F0] border border-[#E2D0BA] rounded-xl p-4">
                    <div class="text-[9px] text-[#6B5444] mb-1.5 uppercase tracking-widest font-bold">WhatsApp</div>
                    <div class="font-serif text-sm font-bold text-[#1C1007] truncate">{{ $booking->phone }}</div>
                </div>
                <div class="bg-[#FAF6F0] border border-[#E2D0BA] rounded-xl p-4">
                    <div class="text-[9px] text-[#6B5444] mb-1.5 uppercase tracking-widest font-bold">Tanggal</div>
                    <div class="font-serif text-sm font-bold text-[#1C1007]">
                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                    </div>
                </div>
                <div class="bg-[#FAF6F0] border border-[#E2D0BA] rounded-xl p-4">
                    <div class="text-[9px] text-[#6B5444] mb-1.5 uppercase tracking-widest font-bold">Jumlah Orang</div>
                    <div class="font-serif text-sm font-bold text-[#1C1007]">
                        {{ $booking->guest_count }} <span class="text-xs font-sans text-[#6B5444]" style="font-weight:300;">Orang</span>
                    </div>
                </div>
            </div>

            {{-- ALASAN PENOLAKAN --}}
            @if($booking->status == 'rejected')
                <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
                    <div class="font-bold text-red-600 mb-2 uppercase tracking-widest text-[10px]">Reservasi Ditolak</div>
                    <div class="text-red-700 text-sm leading-relaxed" style="font-weight:300;">{{ $booking->reject_reason }}</div>
                </div>
            @endif

            {{-- DETAIL PESANAN --}}
            <div>
                <h2 class="font-serif text-base font-bold text-[#c88e5b] uppercase tracking-widest mb-3">
                    Detail Pesanan
                </h2>
                <div class="space-y-2.5">
                    @foreach($booking->items as $bookingItem)
                        <div class="flex items-center justify-between gap-3 border border-[#E2D0BA] rounded-xl p-4 bg-[#FAF6F0] hover:border-[#c88e5b]/50 transition duration-300">
                            <div class="min-w-0">
                                <div class="font-serif text-sm font-bold text-[#1C1007] leading-snug">
                                    {{ $bookingItem->item->title }}
                                </div>
                                <div class="text-[10px] text-[#6B5444] mt-0.5 uppercase tracking-widest" style="font-weight:300;">
                                    Qty: <span class="text-[#1C1007] font-bold">{{ $bookingItem->qty }}</span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="text-[#c88e5b] font-bold text-sm font-serif">
                                    Rp {{ number_format($bookingItem->price * $bookingItem->qty, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TOTAL TAGIHAN --}}
            <div class="bg-[#F0E8DC] border border-[#c88e5b]/30 rounded-2xl p-5">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <div class="text-[9px] text-[#c88e5b] mb-1 uppercase tracking-widest font-bold">Total Tagihan</div>
                        <div class="font-serif text-2xl md:text-3xl font-bold text-[#1C1007]">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </div>
                    </div>
                    @if($booking->dp_amount)
                        <div class="border-t border-[#E2D0BA] pt-3 w-full sm:w-auto sm:border-t-0 sm:border-l sm:pl-5 sm:pt-0">
                            <div class="text-[9px] text-[#6B5444] mb-1 uppercase tracking-widest font-bold">DP 50%</div>
                            <div class="font-serif text-xl font-bold text-green-600">
                                Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            {{-- BUTTON BAYAR --}}
            @if($booking->status == 'approved' && $booking->payment_link)
                <div class="text-center sm:text-right mb-6">
                    <a
                        href="{{ $booking->payment_link }}"
                        target="_blank"
                        class="w-full sm:w-auto inline-block bg-[#c88e5b] text-[#0a0a0a] font-bold text-xs uppercase tracking-widest px-8 py-4 rounded-xl hover:bg-white transition duration-300 shadow-[0_0_20px_rgba(200,142,91,0.2)]"
                    >
                        Bayar DP 50%
                    </a>
                </div>
            @endif

            {{-- STATUS PAID --}}
            @if($booking->status == 'paid')
                <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                    <div class="text-3xl mb-2">✅</div>
                    <div class="font-serif text-base font-bold text-green-700 tracking-wide mb-1">Pembayaran Berhasil</div>
                    <div class="text-sm text-green-600" style="font-weight:300;">Terima kasih. Reservasi Anda sudah dikonfirmasi.</div>
                </div>
            @endif

            {{-- STATUS PENDING --}}
            @if($booking->status == 'pending')
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
                    <div class="text-3xl mb-2">⏳</div>
                    <div class="font-serif text-base font-bold text-amber-700 tracking-wide mb-1">Menunggu Persetujuan Admin</div>
                    <div class="text-sm text-amber-600 mb-4" style="font-weight:300;">Mohon tunggu admin memeriksa ketersediaan tempat.</div>
                    <div class="text-xs flex items-center justify-center gap-2 text-amber-500">
                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span>Diperbarui dalam <span id="refresh-counter" class="font-bold">10</span> detik</span>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

<script>
(function(){
    const status        = '{{ $booking->status }}';
    const paymentStatus = '{{ $booking->payment_status }}';
    const needsRefresh  = status === 'pending' ||
                         (status === 'approved' && paymentStatus !== 'paid');
    if (!needsRefresh) return;

    let sec = 10;
    const el = document.getElementById('refresh-counter');
    setInterval(()=>{ sec--; if(el) el.textContent=sec; if(sec<=0) location.reload(); }, 1000);
})();
</script>

</body>
</html>