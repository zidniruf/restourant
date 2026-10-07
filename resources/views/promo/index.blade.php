@extends('layouts.app')

@section('title', 'Promo — ' . (optional($setting)->site_name ?? 'Restaurant'))
@section('description', 'Lihat semua promo dan penawaran spesial yang sedang berlangsung di ' . (optional($setting)->site_name ?? 'restoran kami') . '.')
@section('keywords', 'promo restoran, diskon makanan, penawaran spesial')

@section('content')

<section id="promo" class="pt-32 pb-24 bg-[#0a0a0a] text-white min-h-screen">

    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="text-center mb-16">
            <p class="text-[#c88e5b] text-xs uppercase tracking-[0.3em] mb-3" style="font-family:'Lato',sans-serif;">
                Penawaran Spesial
            </p>
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-bold text-[#c88e5b] tracking-widest uppercase leading-none" style="font-family: 'Playfair Display', serif;">
                Promo
            </h1>
            <p class="text-gray-400 mt-4 text-sm md:text-base italic max-w-lg mx-auto" style="font-family:'Lato',sans-serif; font-weight:300;">
                Nikmati penawaran terbaik dari kami, khusus untuk Anda
            </p>
        </div>

        @if($promos->count() > 0)
        <div class="flex flex-col gap-0 border-t border-white/5">
            @foreach($promos as $promo)
                @php
                    $bgUrl = $promo->image ? asset('storage/' . $promo->image) : asset('images/default-promo.jpg');
                @endphp
                <div class="promo-item relative w-full aspect-video md:aspect-[21/9] bg-cover bg-center bg-no-repeat border-b border-white/5 overflow-hidden">
                    <div class="absolute inset-[-10%] w-[120%] h-[120%] bg-cover bg-center bg-no-repeat" style="background-image:url('{{ $bgUrl }}');"></div>
                    <div class="absolute inset-0 bg-black/60"></div>

                    <div class="relative z-10 h-full w-full max-w-7xl mx-auto px-5 sm:px-8 flex flex-col justify-center">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 mb-3">
                                <div class="w-4 h-px bg-[#c88e5b]"></div>
                                <span class="text-[#c88e5b] font-light tracking-[0.25em] uppercase text-[10px] md:text-xs">
                                    @if($promo->start_date && $promo->end_date)
                                        {{ $promo->start_date->format('d M') }} — {{ $promo->end_date->format('d M Y') }}
                                    @else
                                        Limited Edition
                                    @endif
                                </span>
                            </div>

                            <h2 class="text-white text-3xl sm:text-5xl md:text-6xl font-serif font-bold mb-4 leading-tight">
                                {{ $promo->title }}
                            </h2>

                            <p class="text-gray-300 text-xs sm:text-sm md:text-base leading-relaxed mb-6 md:mb-8 max-w-lg">
                                {{ $promo->description }}
                            </p>

                            <a
                                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', optional($setting)->whatsapp) }}?text=Halo,%20saya%20tertarik%20dengan%20promo%20{{ urlencode($promo->title) }}"
                                target="_blank"
                                class="group/btn inline-flex items-center gap-3 text-white text-xs sm:text-sm font-light uppercase tracking-widest transition duration-300 hover:text-[#c88e5b]"
                            >
                                <span class="relative pb-px">
                                    Hubungi Kami
                                    <span class="absolute bottom-0 left-0 w-0 h-px bg-[#c88e5b] transition-all duration-500 group-hover/btn:w-full"></span>
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="absolute bottom-4 right-5 sm:bottom-6 sm:right-8 font-serif text-white/5 font-bold leading-none select-none" style="font-size: clamp(4rem, 12vw, 10rem);">
                        0{{ $loop->iteration }}
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="text-center text-gray-400 text-sm uppercase tracking-widest py-20" style="font-family:'Lato',sans-serif;">
            Belum ada promo yang sedang berlangsung
        </div>
        @endif

        <div class="text-center mt-16">
            <a
                href="{{ route('menu.index') }}"
                class="inline-block border border-[#c88e5b] text-[#c88e5b] hover:bg-[#c88e5b] hover:text-white transition-all duration-300 px-8 py-4 text-xs font-bold tracking-widest uppercase"
                style="font-family: 'Lato', sans-serif;"
            >
                Lihat Menu Lengkap
            </a>
        </div>

    </div>
</section>

@endsection