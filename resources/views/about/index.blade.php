@extends('layouts.app')

@section('title', 'Tentang Kami — ' . (optional($setting)->site_name ?? 'Restaurant'))
@section('description', optional($setting)->hero_description ?? 'Kenali lebih dekat cerita, konsep, dan nilai yang kami bawa dalam setiap hidangan.')
@section('keywords', 'tentang restoran, profil restoran, konsep restoran, ' . (optional($setting)->site_name ?? 'restaurant'))

@section('content')

<section class="relative pt-24 sm:pt-32 pb-16 sm:pb-24 bg-[#FAF6F0] overflow-hidden">

    {{-- BG letter dekoratif --}}
    <div class="absolute right-0 top-1/2 -translate-y-1/2 text-[16rem] sm:text-[30rem] md:text-[40rem] font-serif text-[#1C1007]/[0.03] select-none pointer-events-none leading-none"
        style="font-family: 'Playfair Display', serif;">
        A
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header halaman --}}
        <div id="about-page-heading" class="text-center mb-10 sm:mb-16 opacity-0 translate-y-8 transition-all duration-700 ease-out">
            <div class="flex items-center justify-center gap-3 mb-3">
                <div class="w-6 sm:w-8 h-px bg-[#c88e5b]"></div>
                <p class="text-[#c88e5b] text-[10px] sm:text-xs uppercase tracking-[0.3em]" style="font-family:'Lato',sans-serif; font-weight:300;">
                    Kenali Kami Lebih Dekat
                </p>
                <div class="w-6 sm:w-8 h-px bg-[#c88e5b]"></div>
            </div>
            <h1
                class="text-3xl sm:text-6xl md:text-7xl font-bold text-[#c88e5b] tracking-widest uppercase leading-none"
                style="font-family: 'Playfair Display', serif;"
            >
                Tentang Kami
            </h1>
        </div>

<div class="grid md:grid-cols-2 gap-6 sm:gap-10 md:gap-16 items-start">

    {{-- KIRI: Gambar --}}
    <div id="about-page-image" class="relative flex flex-col gap-4 opacity-0 translate-y-8 transition-all duration-700 ease-out md:sticky md:top-28">
        <img
            src="{{ $setting->hero_featured_image ? asset('storage/' . $setting->hero_featured_image) : 'https://images.unsplash.com/photo-1552566626-52f8b828add9' }}"
            class="w-full h-[220px] sm:h-[300px] md:h-[480px] lg:h-[560px] object-cover rounded-2xl shadow-[0_12px_40px_rgba(28,16,7,0.15)]"
            alt="Suasana {{ optional($setting)->site_name ?? 'restoran' }}"
        >
    </div>

            {{-- KANAN: Teks --}}
            <div id="about-page-text" class="opacity-0 translate-y-8 transition-all duration-700 delay-150 ease-out">
                <p class="text-[#c88e5b] text-[10px] sm:text-xs uppercase tracking-[0.25em] mb-3 sm:mb-4" style="font-family: 'Lato', sans-serif;">
                    Cerita Kami
                </p>

                <h2 class="text-2xl sm:text-4xl md:text-5xl font-bold text-[#1C1007] tracking-wide uppercase leading-tight mb-4 sm:mb-6" style="font-family: 'Playfair Display', serif;">
                    {{ optional($setting)->hero_title ?? 'Pengalaman Kuliner Tak Terlupakan' }}
                </h2>

                <div class="w-10 sm:w-12 h-px bg-[#c88e5b]/50 mb-4 sm:mb-6"></div>

<div class="prose-about text-[#6B5444] text-xs sm:text-sm md:text-base leading-relaxed" style="font-family: 'Lato', sans-serif; font-weight: 300;">
    @if(optional($setting)->hero_description)
        {!! $setting->hero_description !!}
    @else
        <p>Kami menghadirkan makanan berkualitas tinggi dengan suasana nyaman dan pelayanan terbaik untuk pengalaman makan yang tidak terlupakan.</p>
    @endif

    @if(optional($setting)->hero_subtitle)
    <p class="mt-3 sm:mt-4">
        {{ $setting->hero_subtitle }}
    </p>
    @endif
</div>

                <div class="flex flex-row gap-3 mt-6 sm:mt-8">
                    <a
                        href="{{ route('menu.index') }}"
                        class="inline-flex items-center justify-center flex-1 sm:flex-none bg-[#c88e5b] hover:bg-[#b07a4a] text-white text-[10px] sm:text-xs uppercase tracking-widest px-5 sm:px-8 py-3.5 sm:py-4 rounded-full transition-all duration-300 active:scale-95"
                        style="font-family:'Lato',sans-serif; font-weight:600;"
                    >
                        Lihat Menu
                    </a>
                    <a
                        href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center flex-1 sm:flex-none border border-[#c88e5b] text-[#c88e5b] hover:bg-[#c88e5b] hover:text-white transition-all duration-300 px-5 sm:px-8 py-3.5 sm:py-4 rounded-full text-[10px] sm:text-xs font-bold tracking-widest uppercase active:scale-95"
                        style="font-family: 'Lato', sans-serif;"
                    >
                        Reservasi Meja
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection

@push('styles')
<style>
    #about-page-heading.is-visible,
    #about-page-image.is-visible,
    #about-page-text.is-visible {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }

    .prose-about p {
        margin-bottom: 0.9rem;
        text-align: justify;
    }
    .prose-about p:last-child {
        margin-bottom: 0;
    }
    .prose-about h2, .prose-about h3 {
        font-family: 'Playfair Display', serif;
        color: #1C1007;
        font-weight: 700;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    .prose-about ul, .prose-about ol {
        margin: 0.5rem 0 0.9rem 1.25rem;
    }
    .prose-about ul { list-style: disc; }
    .prose-about ol { list-style: decimal; }
    .prose-about strong { color: #1C1007; font-weight: 700; }
    .prose-about blockquote {
        border-left: 3px solid #c88e5b;
        padding-left: 1rem;
        color: #8a7361;
        font-style: italic;
        margin: 0.75rem 0;
    }
    .prose-about a {
        color: #c88e5b;
        text-decoration: underline;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ================= ABOUT PAGE REVEAL =================
    const aboutPageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    [
        'about-page-heading',
        'about-page-image',
        'about-page-text'
    ].forEach(id => {
        const el = document.getElementById(id);
        if (el) aboutPageObserver.observe(el);
    });

    // Heading langsung terlihat tanpa perlu scroll (sudah di viewport saat load)
    requestAnimationFrame(() => {
        const heading = document.getElementById('about-page-heading');
        if (heading) heading.classList.add('is-visible');
    });
});
</script>
@endpush