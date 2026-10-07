@extends('layouts.app')

@section('title', 'Gallery — ' . (optional($setting)->site_name ?? 'Restaurant'))
@section('description', 'Lihat koleksi foto suasana dan pengalaman bersantap di ' . (optional($setting)->site_name ?? 'restoran kami') . '.')
@section('keywords', 'galeri restoran, foto restoran, suasana restoran')

@section('content')

<section
    id="gallery"
    class="pt-32 pb-16 sm:pb-24 bg-[#0a0a0a] overflow-hidden"
    x-data="{
        selectedImage: null,
        selectedIndex: 0,
        images: {{ json_encode($galleries->map(fn($g) => asset('storage/' . $g->image))->values()) }},
        types: {{ json_encode($galleries->map(fn($g) => $g->type ?? '')->values()) }},
        titles: {{ json_encode($galleries->map(fn($g) => $g->title ?? '')->values()) }},
        prev() { this.selectedIndex = (this.selectedIndex - 1 + this.images.length) % this.images.length; this.selectedImage = this.images[this.selectedIndex]; },
        next() { this.selectedIndex = (this.selectedIndex + 1) % this.images.length; this.selectedImage = this.images[this.selectedIndex]; },
        open(url, idx) { this.selectedImage = url; this.selectedIndex = idx; }
    }"
    @keydown.escape.window="selectedImage = null"
    @keydown.arrow-left.window="if(selectedImage) prev()"
    @keydown.arrow-right.window="if(selectedImage) next()"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div id="gallery-heading" class="text-center mb-8 sm:mb-16 flex flex-col items-center opacity-0 translate-y-10 transition-all duration-1000 ease-out">
            <div class="flex items-center justify-center gap-4 mb-4 sm:mb-6">
                <div class="w-8 sm:w-12 h-px bg-[#c88e5b]/40"></div>
                <span class="text-[#c88e5b] text-[9px] sm:text-[10px] tracking-[0.4em] uppercase" style="font-family: 'Lato', sans-serif; font-weight: 300;">
                    Koleksi Kami
                </span>
                <div class="w-8 sm:w-12 h-px bg-[#c88e5b]/40"></div>
            </div>

            <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold text-[#c88e5b] tracking-widest uppercase leading-none" style="font-family: 'Playfair Display', serif;">
                Gallery
            </h1>

            <div class="w-16 sm:w-20 h-px bg-[#c88e5b]/40 mx-auto mt-4 sm:mt-6"></div>

            <p class="text-gray-400 mt-3 sm:mt-4 text-xs sm:text-sm italic tracking-wide px-4" style="font-family: 'Lato', sans-serif; font-weight: 300;">
                Suasana &amp; Pengalaman di {{ optional($setting)->site_name ?? 'Restaurant' }} Kami
            </p>
        </div>

        {{-- Grid --}}
        @if($galleries->count() > 0)
        <div class="columns-2 md:columns-3 lg:columns-4 gap-2 sm:gap-3 lg:gap-4 space-y-2 sm:space-y-3 lg:space-y-4">
            @foreach($galleries as $i => $gallery)
                <div
                    class="gallery-item break-inside-avoid overflow-hidden rounded-lg sm:rounded-xl border border-white/10 hover:border-[#c88e5b]/50 transition-all duration-700 ease-out opacity-0 translate-y-8 scale-95 cursor-pointer group relative"
                    @click="open('{{ asset('storage/' . $gallery->image) }}', {{ $i }})"
                >
                    <img
                        src="{{ asset('storage/' . $gallery->image) }}"
                        class="w-full object-cover transition-transform duration-700 ease-out group-hover:scale-110 grayscale-[20%] group-hover:grayscale-0"
                        loading="lazy"
                        alt="{{ $gallery->title ?? 'Galeri ' . (optional($setting)->site_name ?? 'restoran') }}"
                    >
                    <div class="absolute inset-0 bg-[#1C1007]/40 opacity-0 group-hover:opacity-100 transition-opacity duration-400 flex items-center justify-center">
                        <div class="w-7 h-7 md:w-10 md:h-10 border border-[#FAF6F0]/60 flex items-center justify-center scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 delay-75">
                            <svg class="w-4 h-4 text-[#FAF6F0]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0zm-3-3v6m-3-3h6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10 sm:mt-14 flex justify-center [&_a]:text-gray-400 [&_a]:border-white/10 [&_.active_span]:bg-[#c88e5b] [&_.active_span]:text-white">
            {{ $galleries->links() }}
        </div>
        @else
        <div class="text-center text-gray-400 text-sm uppercase tracking-widest py-20" style="font-family: 'Lato', sans-serif;">
            Belum ada foto di galeri
        </div>
        @endif

    </div>

    {{-- Lightbox --}}
    <div
        x-show="selectedImage"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] bg-[#1C1007]/92 backdrop-blur-md flex flex-col items-center justify-center p-4 sm:p-8"
        @click="selectedImage = null"
        style="display:none;"
    >
        <button
            @click="selectedImage = null"
            class="absolute top-5 right-5 sm:top-8 sm:right-8 w-9 h-9 border border-[#FAF6F0]/20 text-[#FAF6F0]/50 hover:text-[#FAF6F0] hover:border-[#c88e5b]/60 transition flex items-center justify-center"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <button
            @click.stop="prev()"
            class="absolute left-3 sm:left-6 w-10 h-10 border border-[#FAF6F0]/20 text-[#FAF6F0]/50 hover:text-[#FAF6F0] hover:border-[#c88e5b]/60 transition flex items-center justify-center"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <button
            @click.stop="next()"
            class="absolute right-3 sm:right-6 w-10 h-10 border border-[#FAF6F0]/20 text-[#FAF6F0]/50 hover:text-[#FAF6F0] hover:border-[#c88e5b]/60 transition flex items-center justify-center"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <div class="flex flex-col items-center mb-5 gap-2" @click.stop>
            <div class="flex items-center gap-3">
                <div class="w-8 h-px bg-[#c88e5b]/50"></div>
                <span class="text-[#c88e5b] text-[9px] tracking-[0.45em] uppercase" style="font-family: 'Lato', sans-serif; font-weight: 300;" x-text="types[selectedIndex] || 'Gallery'"></span>
                <div class="w-8 h-px bg-[#c88e5b]/50"></div>
            </div>

            <template x-if="titles[selectedIndex]">
                <h3 class="text-[#FAF6F0] text-lg sm:text-2xl font-bold tracking-widest uppercase leading-none" style="font-family: 'Playfair Display', serif;" x-text="titles[selectedIndex]"></h3>
            </template>
        </div>

        <img
            :src="selectedImage"
            class="max-w-full max-h-[70vh] shadow-2xl border border-[#FAF6F0]/10 object-contain"
            @click.stop
        >

        <div class="mt-5 text-[#FAF6F0]/30 text-[10px] tracking-[0.3em] uppercase" style="font-family: 'Lato', sans-serif;" x-text="(selectedIndex + 1) + ' / ' + images.length"></div>
    </div>

</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ================= GALLERY REVEAL =================
    const galleryHeading = document.getElementById('gallery-heading');
    const galleryItems = document.querySelectorAll('#gallery .gallery-item');

    const galleryObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                el.classList.remove('opacity-0', 'translate-y-10', 'translate-y-8', 'scale-95');
                el.classList.add('opacity-100', 'translate-y-0', 'scale-100');
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.12 });

    if (galleryHeading) galleryObserver.observe(galleryHeading);

    galleryItems.forEach((item, i) => {
        item.style.transitionDelay = `${(i % 12) * 60}ms`;
        galleryObserver.observe(item);
    });
});
</script>
@endpush