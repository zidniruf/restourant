<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lato', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>

<body class="bg-[#FAF6F0] text-[#1C1007] min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- Kembali --}}
    <a href="/" class="inline-flex items-center gap-2 text-[#6B5444] hover:text-[#c88e5b] text-[10px] uppercase tracking-widest mb-5 transition-colors duration-200">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Beranda
    </a>

    {{-- Card --}}
    <div class="bg-white border border-[#E2D0BA] rounded-3xl shadow-[0_4px_24px_rgba(28,16,7,0.08)] overflow-hidden">

        {{-- Accent bar --}}
        <div class="h-1 w-full bg-gradient-to-r from-transparent via-[#c88e5b] to-transparent"></div>

        <div class="px-7 py-8">

            {{-- Header --}}
            <div class="text-center mb-8">
                <p class="text-[#c88e5b] text-[10px] uppercase tracking-[0.3em] mb-2" style="font-weight:300;">
                    ✦ &nbsp; Reservasi &nbsp; ✦
                </p>
                <h1 class="font-serif text-2xl font-bold text-[#1C1007] tracking-widest uppercase">
                    Cek Status
                </h1>
                <p class="text-[#6B5444] text-sm mt-1.5" style="font-weight:300;">
                    Lacak status reservasi Anda
                </p>
            </div>

            {{-- Error --}}
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-6 text-sm text-center" style="font-weight:300;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('booking.status.search') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold text-[#6B5444] uppercase tracking-widest mb-1.5">
                        Nama Pemesan
                    </label>
                    <input
                        type="text"
                        name="name"
                        required
                        placeholder="Masukkan nama Anda"
                        class="w-full bg-[#FAF6F0] border border-[#E2D0BA] rounded-xl px-4 py-3.5 text-[#1C1007] placeholder-[#B5A090] focus:outline-none focus:border-[#c88e5b] transition text-sm"
                        style="font-family:'Lato',sans-serif;"
                    >
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-[#6B5444] uppercase tracking-widest mb-1.5">
                        Nomor WhatsApp / Order ID
                    </label>
                    <input
                        type="text"
                        name="phone"
                        required
                        placeholder="08xxxxxxxxxx atau BOOK-xx-xxxxx"
                        class="w-full bg-[#FAF6F0] border border-[#E2D0BA] rounded-xl px-4 py-3.5 text-[#1C1007] placeholder-[#B5A090] focus:outline-none focus:border-[#c88e5b] transition text-sm"
                        style="font-family:'Lato',sans-serif;"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#c88e5b] hover:bg-[#b07a4a] text-white font-bold text-xs uppercase tracking-widest px-8 py-4 rounded-xl transition duration-300 shadow-[0_4px_16px_rgba(200,142,91,0.25)] mt-2"
                    style="font-family:'Lato',sans-serif;"
                >
                    Lihat Status
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>