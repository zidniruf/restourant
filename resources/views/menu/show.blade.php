@extends('layouts.app')

@section('title', $item->title . ' — ' . (optional($setting)->site_name ?? 'Restaurant'))
@section('description', Str::limit(strip_tags($item->description ?? ''), 155))
@section('keywords', $item->title . ', menu ' . ($item->category->name ?? '') . ', ' . (optional($setting)->site_name ?? 'restaurant'))

@section('content')

<script type="application/ld+json">
{!! json_encode(array_filter([
    "@@context" => "https://schema.org",
    "@@type" => "MenuItem",
    "name" => $item->title,
    "description" => strip_tags($item->description ?? ''),
    "image" => $item->image ? asset('storage/' . $item->image) : null,
    "offers" => $item->price ? [
        "@@type" => "Offer",
        "price" => (string) $item->price,
        "priceCurrency" => "IDR",
    ] : null,
])) !!}
</script>

<section class="relative pt-24 sm:pt-32 pb-16 sm:pb-24 bg-[#FAF6F0] overflow-hidden">

    {{-- Aksen garis vertikal kiri, konsisten dengan section contact --}}
    <div class="absolute left-0 top-0 h-full w-px bg-gradient-to-b from-transparent via-[#c88e5b]/20 to-transparent pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6">

        {{-- BACK LINK: hanya tampil di desktop, disembunyikan di mobile --}}
        <a href="{{ route('menu.index') }}"
            class="hidden md:inline-flex items-center gap-1.5 text-[10px] sm:text-xs uppercase tracking-widest text-[#6B5444] hover:text-[#c88e5b] transition"
            style="font-family:'Lato',sans-serif;">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Menu
        </a>

        {{-- BADGE + KATEGORI, konsisten dgn pola "Reservasi" / "Recommendation for you" --}}
        <div id="menu-badge" class="flex items-center gap-3 mt-6 sm:mt-8 mb-3 opacity-0 translate-y-4 transition-all duration-700 ease-out">
            <div class="w-6 md:w-10 h-px bg-[#c88e5b]"></div>
            <span class="text-[#c88e5b] text-[10px] md:text-xs tracking-[0.3em] uppercase" style="font-family:'Lato',sans-serif; font-weight:300;">
                {{ $item->category->name ?? 'Menu' }}
            </span>
        </div>

        <div class="grid md:grid-cols-2 gap-6 sm:gap-10 items-start">

            {{-- IMAGE --}}
            <div id="menu-image" class="rounded-2xl overflow-hidden bg-white border border-[#E2D0BA] aspect-[4/3] sm:aspect-square flex items-center justify-center shadow-[0_8px_30px_rgba(28,16,7,0.06)] opacity-0 translate-y-6 transition-all duration-700 ease-out">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-14 h-14 sm:w-16 sm:h-16 text-[#C8B89A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                @endif
            </div>

            {{-- CONTENT --}}
            <div id="menu-content" class="opacity-0 translate-y-6 transition-all duration-700 delay-150 ease-out">

                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#1C1007] uppercase tracking-wide leading-tight" style="font-family:'Playfair Display',serif;">
                    {{ $item->title }}
                </h1>

                @if($item->price)
                <div class="inline-flex items-center mt-3 sm:mt-4 px-3.5 sm:px-4 py-1.5 bg-[#c88e5b]/10 border border-[#c88e5b]/30 rounded-full">
                    <span class="text-base sm:text-xl text-[#c88e5b] font-semibold" style="font-family:'Lato',sans-serif;">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </span>
                </div>
                @endif

                @if($item->description)
                <p class="text-[#6B5444] leading-relaxed mt-4 sm:mt-5 text-xs sm:text-sm" style="font-family:'Lato',sans-serif; font-weight:300;">
                    {{ $item->description }}
                </p>
                @endif

                @if($item->properties && count($item->properties) > 0)
                <div class="mt-5 sm:mt-6 pt-5 sm:pt-6 border-t border-[#E2D0BA]">
                    <span class="text-[9px] sm:text-[10px] uppercase tracking-[0.2em] text-[#c88e5b]" style="font-family:'Lato',sans-serif;">Info Tambahan</span>
                    <ul class="mt-2 space-y-1.5 list-disc list-inside">
                        @foreach($item->properties as $key => $value)
                        <li class="text-xs sm:text-sm text-[#6B5444]" style="font-family:'Lato',sans-serif;">
                            <span class="text-[#1C1007] font-medium">{{ $key }}</span>: {{ $value }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <a href="{{ route('contact') }}"
                    id="menu-cta-btn"
                    class="menu-cta-btn relative inline-flex items-center gap-2 mt-6 sm:mt-8 bg-[#c88e5b] hover:bg-[#b07a4a] text-white text-[10px] sm:text-xs uppercase tracking-widest px-6 sm:px-8 py-3 sm:py-4 rounded-full transition-all duration-300 active:scale-95 overflow-hidden w-full sm:w-auto justify-center"
                    style="font-family:'Lato',sans-serif; font-weight:600;">
                    <span class="menu-cta-pulse absolute inset-0 rounded-full"></span>
                    <svg class="relative z-10 w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="relative z-10">Reservasi Sekarang</span>
                </a>
            </div>
        </div>

        @if($recommendations->count())
        <div id="rec-heading" class="mt-14 sm:mt-20 pt-8 sm:pt-10 border-t border-[#E2D0BA] flex flex-col items-center opacity-0 translate-y-8 transition-all duration-700 ease-out">
            <div class="w-8 sm:w-10 h-px bg-[#c88e5b]/50 mb-3"></div>
            <h2 class="text-lg sm:text-2xl font-bold text-[#1C1007] uppercase text-center tracking-widest" style="font-family:'Playfair Display',serif;">
                Rekomendasi Lainnya
            </h2>
        </div>

        <div class="mt-6 sm:mt-8 grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">
            @foreach($recommendations as $rec)
            <a href="{{ route('menu.show', $rec->slug) }}"
                class="rec-card group bg-white border border-[#E2D0BA] rounded-xl sm:rounded-2xl overflow-hidden flex flex-col transition-all duration-500 hover:border-[#c88e5b]/60 hover:shadow-[0_8px_30px_rgba(200,142,91,0.15)] hover:-translate-y-1 opacity-0 translate-y-6">
                <div class="w-full overflow-hidden" style="aspect-ratio: 4/3;">
                    @if($rec->image)
                        <img src="{{ asset('storage/' . $rec->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $rec->title }}" loading="lazy">
                    @else
                        <div class="w-full h-full bg-[#F0E8DC] flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#B5A090]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="px-2.5 sm:px-3 py-2.5 sm:py-3">
                    <span class="text-[9px] sm:text-[10px] uppercase tracking-[0.15em] text-[#c88e5b]" style="font-family:'Lato',sans-serif;">
                        {{ $rec->category->name ?? 'Menu' }}
                    </span>
                    <h3 class="text-xs sm:text-sm font-bold text-[#1C1007] uppercase leading-tight line-clamp-2 mt-0.5 group-hover:text-[#c88e5b] transition-colors" style="font-family:'Playfair Display',serif;">
                        {{ $rec->title }}
                    </h3>
                    @if($rec->price)
                    <p class="text-[11px] sm:text-[12px] font-semibold text-[#c88e5b] mt-1" style="font-family:'Lato',sans-serif;">
                        Rp {{ number_format($rec->price, 0, ',', '.') }}
                    </p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @endif

    </div>
</section>
@endsection

@push('styles')
<style>
    /* Pulse glow tombol CTA, konsisten dgn tombol reservasi di hero */
    .menu-cta-pulse {
        box-shadow: 0 0 0 0 rgba(200, 142, 91, 0.6);
        animation: menuCtaPulse 2.5s ease-out infinite;
        pointer-events: none;
    }
    @keyframes menuCtaPulse {
        0%   { box-shadow: 0 0 0 0 rgba(200, 142, 91, 0.55); }
        70%  { box-shadow: 0 0 0 14px rgba(200, 142, 91, 0); }
        100% { box-shadow: 0 0 0 0 rgba(200, 142, 91, 0); }
    }
    .menu-cta-btn .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.45);
        transform: scale(0);
        animation: menuRippleAnim 600ms ease-out;
        pointer-events: none;
    }
    @keyframes menuRippleAnim {
        to { transform: scale(2.5); opacity: 0; }
    }

    .rec-card.is-visible,
    #menu-badge.is-visible,
    #menu-image.is-visible,
    #menu-content.is-visible,
    #rec-heading.is-visible {
        opacity: 1 !important;
        transform: translateY(0) translateX(0) !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ================= MENU DETAIL REVEAL =================
    const menuBadge = document.getElementById('menu-badge');
    const menuImage = document.getElementById('menu-image');
    const menuContent = document.getElementById('menu-content');
    const recHeading = document.getElementById('rec-heading');
    const recCards = document.querySelectorAll('.rec-card');

    const menuObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    [menuBadge, menuImage, menuContent, recHeading].forEach(el => {
        if (el) menuObserver.observe(el);
    });

    recCards.forEach((card, i) => {
        card.style.transitionDelay = `${(i % 8) * 70}ms`;
        card.style.transitionProperty = 'opacity, transform';
        card.style.transitionDuration = '600ms';
        card.style.transitionTimingFunction = 'ease-out';
        menuObserver.observe(card);
    });

    // Header badge & image/content muncul cepat tanpa nunggu scroll (langsung di viewport)
    requestAnimationFrame(() => {
        if (menuBadge) menuBadge.classList.add('is-visible');
    });

    // ================= CTA RIPPLE EFFECT =================
    const ctaBtn = document.getElementById('menu-cta-btn');
    if (ctaBtn) {
        ctaBtn.addEventListener('click', function (e) {
            const rect = ctaBtn.getBoundingClientRect();
            const ripple = document.createElement('span');
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.classList.add('ripple');
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;

            ctaBtn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    }

});
</script>
@endpush