<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ==========================================================
         SEO META TAGS — di-override tiap halaman lewat @section
         ========================================================== --}}
    <title>@yield('title', optional($setting)->site_name ?? 'Restouran')</title>
    <meta name="description" content="@yield('description', optional($setting)->hero_description ?? 'Restoran premium dengan pengalaman kuliner terbaik. Reservasi meja online mudah dan cepat.')">
    <meta name="keywords" content="@yield('keywords', 'restoran, reservasi meja, booking meja, restoran kudus, makan malam romantis')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Favicon --}}
    <link rel="icon" href="{{ optional($setting)->logo ? asset('storage/' . $setting->logo) : asset('/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ optional($setting)->logo ? asset('storage/' . $setting->logo) : asset('/favicon.ico') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', optional($setting)->site_name ?? 'Restouran')">
    <meta property="og:description" content="@yield('og_description', optional($setting)->hero_description ?? 'Restoran premium dengan pengalaman kuliner terbaik.')">
    <meta property="og:image" content="@yield('og_image', optional($setting)->hero_background ? asset('storage/' . $setting->hero_background) : '')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="restaurant">

    <meta name="wa-number" content="{{ preg_replace('/[^0-9]/', '', optional($setting)->whatsapp ?? '') }}">
    <meta name="site-name" content="{{ optional($setting)->site_name ?? 'Restaurant' }}">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .contact-hour-row > span:first-child { color: #1C1007 !important; }
        .contact-hour-row[style*="background"] { background: rgba(200, 142, 91, 0.12) !important; }
        [x-cloak] { display: none !important; }
    </style>

    {{-- Slot untuk CSS tambahan khusus halaman tertentu --}}
    @stack('styles')
</head>

{{-- FIX STICKY FOOTER: min-h-screen + flex flex-col --}}
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

{{-- ==========================================================
     NAVBAR — sama di semua halaman
     ========================================================== --}}
<nav
    x-data="{
        open: false,
        isScrolled: false,
        showNav: true,
        lastPos: 0,
        init() {
            this.isScrolled = window.scrollY > 50;
            this.lastPos = window.scrollY;
        }
    }"
    @scroll.window="
        isScrolled = window.scrollY > 50;
        showNav = window.scrollY < lastPos || window.scrollY < 50;
        if (window.scrollY > lastPos) open = false;
        lastPos = window.scrollY;
    "
    :class="{
        'translate-y-0': showNav,
        '-translate-y-full': !showNav
    }"
    class="fixed top-0 left-0 w-full z-50 transition-all duration-300 ease-in-out
           bg-[#1C1007] border-b border-white/10 shadow-lg"
>
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="flex items-center justify-between h-16 md:h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 md:gap-3 flex-shrink-0">
                @if(optional($setting)->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}"
                        class="w-15 h-15 md:w-10 md:h-10 object-contain flex-shrink-0">
                @endif
                <span
                    class="font-bold text-base md:text-2xl tracking-widest uppercase text-[#FAF6F0] whitespace-nowrap"
                    style="font-family:'Playfair Display',serif;"
                >
                    {{ optional($setting)->site_name ?? 'Restaurant' }}
                </span>
            </a>

            {{-- Desktop Menu --}}
            <div
                x-show="!isScrolled"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="hidden lg:flex items-center gap-8 text-xs uppercase tracking-widest"
                style="font-family:'Lato',sans-serif; font-weight:300;"
            >
                <a href="{{ route('home') }}"    class="{{ request()->routeIs('home') ? 'text-[#c88e5b]' : 'text-[#FAF6F0]' }} hover:text-[#c88e5b] transition duration-300">Home</a>
                <a href="{{ route('about') }}"   class="{{ request()->routeIs('about') ? 'text-[#c88e5b]' : 'text-[#FAF6F0]' }} hover:text-[#c88e5b] transition duration-300">About</a>
                <a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.index') ? 'text-[#c88e5b]' : 'text-[#FAF6F0]' }} hover:text-[#c88e5b] transition duration-300">Menu</a>
                <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'text-[#c88e5b]' : 'text-[#FAF6F0]' }} hover:text-[#c88e5b] transition duration-300">Gallery</a>
                <a href="{{ route('promo.index') }}"   class="{{ request()->routeIs('promo') ? 'text-[#c88e5b]' : 'text-[#FAF6F0]' }} hover:text-[#c88e5b] transition duration-300">Promo</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-[#c88e5b]' : 'text-[#FAF6F0]' }} hover:text-[#c88e5b] transition duration-300">Contact</a>
            </div>

            {{-- Burger Desktop (saat scrolled) --}}
            <button
                @click="open = !open"
                x-show="isScrolled"
                class="hidden lg:flex w-9 h-9 items-center justify-center rounded border
                       border-white/30 text-[#FAF6F0] hover:text-[#c88e5b] hover:border-[#c88e5b]/50 transition flex-shrink-0"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Burger Mobile --}}
            <button
                @click="open = !open"
                class="lg:hidden w-9 h-9 flex items-center justify-center rounded border
                       border-white/30 text-[#FAF6F0] hover:text-[#c88e5b] hover:border-[#c88e5b]/50 transition flex-shrink-0"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>

        {{-- Dropdown Mobile --}}
        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="absolute w-full left-0 px-6 py-6 bg-[#1C1007] border-t border-white/10 shadow-lg"
        >
            <div class="flex flex-col gap-4 text-center text-xs uppercase tracking-widest"
                style="font-family:'Lato',sans-serif; font-weight:300;">
                <a href="{{ route('home') }}"       @click="open=false" class="text-[#FAF6F0] hover:text-[#c88e5b] transition py-1">Home</a>
                <a href="{{ route('about') }}"      @click="open=false" class="text-[#FAF6F0] hover:text-[#c88e5b] transition py-1">About</a>
                <a href="{{ route('menu.index') }}" @click="open=false" class="text-[#FAF6F0] hover:text-[#c88e5b] transition py-1">Menu</a>
                <a href="{{ route('gallery') }}"    @click="open=false" class="text-[#FAF6F0] hover:text-[#c88e5b] transition py-1">Gallery</a>
                <a href="{{ route('promo.index') }}"      @click="open=false" class="text-[#FAF6F0] hover:text-[#c88e5b] transition py-1">Promo</a>
                <a href="{{ route('contact') }}"    @click="open=false" class="text-[#FAF6F0] hover:text-[#c88e5b] transition py-1">Contact</a>
            </div>
        </div>

    </div>
</nav>

{{-- ==========================================================
     KONTEN HALAMAN — diisi tiap file halaman lewat @section('content')
     FIX STICKY FOOTER: flex-1 supaya main mengisi sisa ruang kosong
     ========================================================== --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- ==========================================================
     FOOTER — sama di semua halaman
     ========================================================== --}}
<footer id="footer" class="bg-[#1C1007] text-[#E2D0BA] py-6 border-t border-[#c88e5b]/20">
    <div class="max-w-7xl mx-auto px-6">
        <div
            class="text-center text-xs md:text-sm uppercase tracking-widest flex flex-col md:flex-row justify-center items-center gap-1 md:gap-2"
            style="font-family: 'Lato', sans-serif; font-weight: 300;"
        >
            <span>© {{ date('Y') }}
                <strong
                    class="text-[#c88e5b]"
                    style="font-family: 'Playfair Display', serif; font-weight: 700;"
                >
                    {{ optional($setting)->site_name ?? 'Restaurant' }}
                </strong>.
            </span>
            <span>All rights reserved.</span>
        </div>
    </div>
</footer>

{{-- ==========================================================
     FLOATING WHATSAPP — sama di semua halaman
     ========================================================== --}}
@if(optional($setting)->whatsapp)
    @php
        $waNumber = preg_replace('/[^0-9]/', '', $setting->whatsapp);
        $siteName = optional($setting)->site_name ?? 'Restaurant';
        $waText = urlencode("Halo *{$siteName}* \n\nSaya ingin bertanya mengenai:\n\n• Menu & Harga\n• Reservasi Meja\n• Promo yang Tersedia\n\nMohon informasinya, terima kasih! ");
    @endphp
    <a
        href="https://wa.me/{{ $waNumber }}?text={{ $waText }}"
        target="_blank"
        class="fixed bottom-6 right-6 w-16 h-16 rounded-full flex items-center justify-center shadow-2xl z-50 transition-transform duration-300 hover:scale-110"
        title="Chat via WhatsApp"
        style="background-color: #25D366;"
    >
        @if(file_exists(public_path('images/whatsapp.png')))
            <img src="{{ asset('images/whatsapp.png') }}" class="w-9 h-9 object-contain" alt="WhatsApp">
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9" viewBox="0 0 24 24" fill="white">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        @endif
        <span class="absolute w-16 h-16 rounded-full animate-ping opacity-30" style="background-color: #25D366;"></span>
    </a>
@endif

{{-- ==========================================================
     TOAST NOTIFICATION — error & success, sama di semua halaman
     ========================================================== --}}
@if($errors->any())
<div id="toast-error" style="
    position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999;
    background: #1a1a1a; border: 1px solid #ef4444; border-left: 4px solid #ef4444;
    border-radius: 12px; padding: 16px 20px; max-width: 90vw; width: 420px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.5); display: flex; gap: 12px;
    align-items: flex-start; animation: slideDown 0.3s ease;
">
    <div style="font-size:20px;flex-shrink:0">🚫</div>
    <div style="flex:1">
        @foreach($errors->all() as $error)
        <div style="color:#fca5a5;font-size:13px;line-height:1.5">{{ $error }}</div>
        @endforeach
    </div>
    <button onclick="document.getElementById('toast-error').remove()"
        style="color:#6b7280;font-size:18px;background:none;border:none;cursor:pointer;flex-shrink:0;line-height:1">×</button>
</div>
@endif

@if(session('success'))
<div id="toast-success" style="
    position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999;
    background: #1a1a1a; border: 1px solid #10b981; border-left: 4px solid #10b981;
    border-radius: 12px; padding: 16px 20px; max-width: 90vw; width: 420px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.5); display: flex; gap: 12px;
    align-items: center; animation: slideDown 0.3s ease;
">
    <div style="font-size:20px">✅</div>
    <div style="color:#6ee7b7;font-size:13px;flex:1">{{ session('success') }}</div>
    <button onclick="document.getElementById('toast-success').remove()"
        style="color:#6b7280;font-size:18px;background:none;border:none;cursor:pointer">×</button>
</div>
@endif

<style>
@keyframes slideDown {
    from { opacity:0; transform: translateX(-50%) translateY(-20px); }
    to   { opacity:1; transform: translateX(-50%) translateY(0); }
}
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(() => {
            document.getElementById('toast-error')?.remove();
            document.getElementById('toast-success')?.remove();
        }, 5000);
    });
</script>

{{-- Slot untuk JS tambahan khusus halaman tertentu (mis. animasi hero, form booking) --}}
@stack('scripts')

</body>
</html>