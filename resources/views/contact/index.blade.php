@extends('layouts.app')

@section('title', 'Kontak & Reservasi — ' . (optional($setting)->site_name ?? 'Restaurant'))
@section('description', 'Hubungi kami atau lakukan reservasi meja secara online. Cepat, mudah, dan gratis.')
@section('keywords', 'reservasi meja, booking restoran, kontak restoran, ' . (optional($setting)->site_name ?? 'restaurant'))

@section('content')

<section id="contact" class="relative pt-32 pb-24 bg-[#FAF6F0] overflow-hidden">

    <div class="absolute left-0 top-0 h-full w-px bg-gradient-to-b from-transparent via-[#c88e5b]/20 to-transparent pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">

        <div class="flex items-center gap-3 mb-3">
            <div class="w-6 md:w-12 h-px bg-[#c88e5b]"></div>
            <span class="text-[#c88e5b] text-[10px] md:text-xs tracking-[0.3em] uppercase" style="font-family: 'Lato', sans-serif; font-weight: 300;">
                Reservasi
            </span>
        </div>

        <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-[#1C1007] tracking-widest uppercase leading-none mb-8 md:mb-16" style="font-family: 'Playfair Display', serif;">
            Contact <span class="text-[#c88e5b]">Us</span>
        </h1>

        <div class="{{ optional($setting)->is_reservation_enabled ? 'grid lg:grid-cols-2' : 'max-w-2xl mx-auto' }} gap-6 lg:gap-16 items-start">

            {{-- FORM RESERVASI --}}
            @if(optional($setting)->is_reservation_enabled)
            <div class="border border-[#E2D0BA] bg-white rounded-2xl shadow-[0_8px_30px_rgba(28,16,7,0.05)] p-4 sm:p-6 md:p-8">

                <h2 class="text-lg md:text-2xl font-bold text-[#1C1007] mb-1 uppercase tracking-widest" style="font-family: 'Playfair Display', serif;">
                    Form Reservasi
                </h2>
                <p class="text-[#6B5444] text-xs mb-5" style="font-family: 'Lato', sans-serif;">
                    Lengkapi data berikut untuk melakukan booking tempat.
                </p>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 mb-4 text-sm" style="font-family: 'Lato', sans-serif;">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 mb-4 text-sm" style="font-family: 'Lato', sans-serif;">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('booking.store') }}" method="POST" x-data="bookingForm()" class="space-y-3.5" @submit.prevent="kirimBooking($event)">
                    @csrf

                    @php $labelClass = 'block text-[11px] text-[#6B5444] mb-1.5 uppercase tracking-widest'; @endphp
                    @php $inputClass = 'w-full bg-[#FAF6F0] border border-[#E2D0BA] text-[#1C1007] placeholder-[#B5A090] rounded-xl px-3 py-3 text-sm sm:text-base focus:outline-none focus:border-[#c88e5b] transition'; @endphp

                    {{-- NAMA & WA --}}
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Nama</label>
                            <input type="text" name="name" required placeholder="Nama Anda" class="{{ $inputClass }}" style="font-family: 'Lato', sans-serif;">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">WhatsApp</label>
                            <input type="text" name="phone" required placeholder="628xx" class="{{ $inputClass }}" style="font-family: 'Lato', sans-serif;">
                        </div>
                    </div>

                    {{-- TANGGAL & MEJA --}}
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Tanggal</label>
                            <input type="date" name="booking_date"
                                min="{{ now()->addDay()->format('Y-m-d') }}" required
                                class="{{ $inputClass }} [color-scheme:light]"
                                style="font-family: 'Lato', sans-serif;">
                        </div>

                        <div>
                            <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Tempat</label>

                            @php $grouped = $tables->groupBy('type'); @endphp

<div x-data="{ open: false }" class="relative">

                                <input type="hidden" name="restaurant_table_id" :value="selectedTableId" x-ref="tableInput"
                                    :class="!selectedTableId ? 'border border-red-400' : ''">

                                <button
                                    type="button"
                                    @click="open = !open"
                                    class="w-full bg-white border border-[#E2D0BA] text-[#1C1007] rounded-xl px-3 py-2.5 text-xs sm:text-sm focus:outline-none focus:border-[#c88e5b] transition text-left flex items-center justify-between"
                                    style="font-family: 'Lato', sans-serif;"
                                >
                                    <span x-text="selectedLabel" :class="selectedTableId ? 'text-[#1C1007]' : 'text-[#9B8B7E]'"></span>
                                    <svg class="w-4 h-4 text-[#c88e5b] flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div
                                    x-show="open"
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                    class="absolute z-50 w-full bg-white border border-[#E2D0BA] rounded-xl shadow-lg max-h-64 overflow-y-auto"
                                    style="display: none;"
                                >
<div
    @click="selectedTableId = ''; selectedLabel = '-- Pilih Tempat --'; selectedCapacity = null; selectedIsPaid = false; selectedPricingType = ''; selectedPrice = 0; durationHours = 1; open = false"
                                        class="px-3 py-2 text-xs text-[#9B8B7E] hover:bg-[#FAF6F0] cursor-pointer transition"
                                        style="font-family: 'Lato', sans-serif;"
                                    >
                                        -- Pilih Tempat --
                                    </div>

                                    @foreach($grouped as $type => $items)
                                        <div class="px-3 pt-2 pb-1 text-[9px] uppercase tracking-[0.2em] text-[#c88e5b] border-t border-[#F0E8DC]" style="font-family: 'Lato', sans-serif; font-weight: 400;">
                                            {{ $type }}
                                        </div>

                                        @foreach($items as $table)
                                            <div
@click="
    selectedTableId = {{ $table->id }};
    selectedLabel = '{{ addslashes($table->name) }}';
    selectedCapacity = {{ $table->capacity ?? 'null' }};
    selectedIsPaid = {{ $table->is_paid ? 'true' : 'false' }};
    selectedPricingType = '{{ $table->pricing_type }}';
    selectedPrice = {{ $table->price ?? 0 }};
    durationHours = 1;
    open = false
"
                                                class="px-3 py-2.5 cursor-pointer hover:bg-[#FAF6F0] transition border-b border-[#F0E8DC]/60 last:border-0"
                                                :class="selectedTableId === {{ $table->id }} ? 'bg-[#FAF6F0] border-l-2 border-l-[#c88e5b]' : ''"
                                            >
<div class="text-xs font-semibold text-[#1C1007]" style="font-family: 'Lato', sans-serif;">
    {{ $table->name }}
    @if($table->capacity)
        <span class="ml-1.5 text-[10px] font-normal text-[#c88e5b]">· Maks {{ $table->capacity }} orang</span>
    @endif
    @if($table->is_paid)
        <span class="ml-1.5 text-[10px] font-normal text-red-500">
            · {{ $table->pricing_type === 'per_hour'
                    ? 'Rp ' . number_format($table->price) . '/jam'
                    : 'Rp ' . number_format($table->price) . ' (sekali masuk)' }}
        </span>
    @else
        <span class="ml-1.5 text-[10px] font-normal text-green-600">· Gratis</span>
    @endif
</div>
                                                @if($table->description)
                                                <div class="text-[10px] text-[#9B8B7E] mt-0.5 leading-relaxed" style="font-family: 'Lato', sans-serif; font-weight: 300;">
                                                    {{ $table->description }}
                                                </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

{{-- DURASI JAM (khusus tempat berbayar per jam) --}}
<div x-show="selectedIsPaid && selectedPricingType === 'per_hour'" x-cloak>
    <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Durasi (Jam)</label>
    <input type="number" name="duration_hours" x-model.number="durationHours" min="1" max="24"
        class="{{ $inputClass }}" style="font-family: 'Lato', sans-serif;">
    <p class="text-[11px] text-[#B5A090] mt-1" style="font-family: 'Lato', sans-serif;">
        Rp <span x-text="Number(selectedPrice).toLocaleString('id-ID')"></span> / jam ×
        <span x-text="durationHours"></span> jam = Rp <span x-text="tablePrice.toLocaleString('id-ID')"></span>
    </p>
</div>

                    {{-- SESI --}}
                    @if($sessions->count() > 0)
                    <div>
                        <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Sesi</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($sessions as $session)
                            <label class="cursor-pointer">
                                <input type="radio" name="restaurant_session_id" value="{{ $session->id }}" class="sr-only peer">
                                <div class="border border-[#E2D0BA] rounded-xl py-2 px-2 text-center peer-checked:border-[#c88e5b] peer-checked:bg-[#c88e5b]/5 hover:border-[#c88e5b]/50 transition">
                                    <div class="text-[#1C1007] text-xs uppercase tracking-wider" style="font-family: 'Playfair Display', serif;">
                                        {{ $session->name }}
                                    </div>
                                    <div class="text-[10px] text-[#6B5444] mt-0.5" style="font-family: 'Lato', sans-serif;">
                                        {{ substr($session->start_time, 0, 5) }}–{{ substr($session->end_time, 0, 5) }}
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- JAM ACARA & MAKANAN SIAP --}}
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Jam Acara</label>
                            <input type="time" name="event_time" required class="{{ $inputClass }} [color-scheme:light]" style="font-family: 'Lato', sans-serif;">
                            <p class="text-[11px] text-[#B5A090] mt-1" style="font-family: 'Lato', sans-serif;">Waktu acara dimulai</p>
                        </div>
                        <div>
                            <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Makanan Siap</label>
                            <input type="time" name="food_ready_time" required class="{{ $inputClass }} [color-scheme:light]" style="font-family: 'Lato', sans-serif;">
                            <p class="text-[9px] text-[#B5A090] mt-1" style="font-family: 'Lato', sans-serif;">Estimasi makanan siap</p>
                        </div>
                    </div>

<div>
    <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Jumlah Orang</label>
    <input type="number" name="guest_count" min="1" required placeholder="4"
        :max="selectedCapacity || undefined"
        @input="if (selectedCapacity && Number($event.target.value) > selectedCapacity) $event.target.value = selectedCapacity"
        class="{{ $inputClass }}" style="font-family: 'Lato', sans-serif;">
    <p x-show="selectedCapacity" x-cloak class="text-[11px] text-[#B5A090] mt-1" style="font-family: 'Lato', sans-serif;">
        Maksimal <span x-text="selectedCapacity"></span> orang untuk tempat ini
    </p>
</div>

{{-- PILIH MENU --}}
<div x-data="{ activeCategory: 'semua', searchQuery: '' }">
    <div class="flex items-center justify-between mb-2">
        <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Pilih Menu</label>
        <span class="text-[10px] text-[#B5A090]" style="font-family: 'Lato', sans-serif;">Opsional</span>
    </div>

    {{-- SEARCH MENU --}}
    <div class="relative mb-2.5">
        <svg class="w-4 h-4 text-[#B5A090] absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <circle cx="11" cy="11" r="7"/>
            <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
        </svg>
        <input
            type="text"
            x-model="searchQuery"
            placeholder="Cari menu, mis. Nasi Goreng..."
            class="w-full bg-[#FAF6F0] border border-[#E2D0BA] text-[#1C1007] placeholder-[#B5A090] rounded-xl pl-9 pr-8 py-2.5 text-xs sm:text-sm focus:outline-none focus:border-[#c88e5b] transition"
            style="font-family: 'Lato', sans-serif;"
        >
        <button
            type="button"
            x-show="searchQuery.length > 0"
            x-cloak
            @click="searchQuery = ''"
            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[#B5A090] hover:text-[#c88e5b] transition"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div x-data="{ showMore: false, isMobile: false }" x-init="isMobile = window.innerWidth < 768" @resize.window="isMobile = window.innerWidth < 768">
        <div class="flex flex-wrap gap-1.5 mb-2">
            <button type="button" @click="activeCategory = 'semua'"
                :class="activeCategory==='semua' ? 'bg-[#c88e5b] text-white border-[#c88e5b]' : 'bg-transparent text-[#6B5444] border-[#E2D0BA]'"
                class="border rounded-full px-2.5 py-1 text-[10px] uppercase tracking-wide whitespace-nowrap transition"
                style="font-family: 'Lato', sans-serif;">
                Semua
            </button>

            @foreach($categories as $cat)
            <button type="button" @click="activeCategory = '{{ $cat->id }}'"
                x-show="!isMobile || showMore || {{ $loop->index }} < 3"
                :class="activeCategory==='{{ $cat->id }}' ? 'bg-[#c88e5b] text-white border-[#c88e5b]' : 'bg-transparent text-[#6B5444] border-[#E2D0BA]'"
                class="border rounded-full px-2.5 py-1 text-[10px] uppercase tracking-wide whitespace-nowrap transition"
                style="font-family: 'Lato', sans-serif;">
                {{ $cat->name }}
            </button>
            @endforeach

            @if($categories->count() > 3)
            <button type="button" x-show="isMobile" @click="showMore = !showMore"
                x-text="showMore ? '− Sedikit' : '+ {{ $categories->count() - 3 }} lagi'"
                class="border border-[#c88e5b]/50 rounded-full px-2.5 py-1 text-[10px] uppercase tracking-wide text-[#c88e5b] bg-transparent whitespace-nowrap transition"
                style="font-family: 'Lato', sans-serif;">
            </button>
            @endif
        </div>
    </div>

    <div class="max-h-56 overflow-y-auto space-y-1.5 pr-0.5
        [&::-webkit-scrollbar]:w-1
        [&::-webkit-scrollbar-track]:bg-[#F0E8DC]
        [&::-webkit-scrollbar-thumb]:bg-[#c88e5b]/40">

        @foreach($menus as $menu)
        @php $menuKeyword = strtolower(addslashes($menu->title)); @endphp
        <div x-show="(activeCategory==='semua' || activeCategory==='{{ $menu->category_id }}') && (searchQuery === '' || '{{ $menuKeyword }}'.includes(searchQuery.toLowerCase().trim()))"
             class="grid items-center border border-[#E2D0BA] rounded-xl overflow-hidden transition-colors duration-200 hover:border-[#c88e5b]/40"
             style="grid-template-columns: 1fr 90px;">

            <div class="flex items-center min-w-0 overflow-hidden">
                @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" class="w-14 h-14 object-cover flex-shrink-0" alt="{{ $menu->title }}">
                @else
                <div class="w-14 h-14 bg-[#F0E8DC] flex-shrink-0 flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#B5A090]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif
                <div class="px-2.5 py-1.5 min-w-0 overflow-hidden">
                    <div class="text-[#1C1007] text-sm truncate leading-tight" style="font-family: 'Lato', sans-serif;">{{ $menu->title }}</div>
                    <div class="text-[#c88e5b] text-xs mt-0.5" style="font-family: 'Lato', sans-serif;">Rp {{ number_format($menu->price) }}</div>
                </div>
            </div>

            <div class="grid h-11 border-l border-[#E2D0BA] flex-shrink-0" style="grid-template-columns: 30px 30px 30px;">
                <button type="button" @click="decrease({{ $menu->id }})"
                    class="text-[#1C1007] text-lg border-r border-[#E2D0BA] flex items-center justify-center bg-transparent hover:bg-[#F0E8DC] transition active:scale-90">−</button>
                <span class="text-[#1C1007] text-sm flex items-center justify-center" x-text="getQty({{ $menu->id }})"></span>
                <button type="button" @click="increase({{ $menu->id }},'{{ $menu->title }}',{{ $menu->price }})"
                    class="text-white text-lg flex items-center justify-center bg-[#c88e5b] hover:bg-[#b07a4a] transition active:scale-90">+</button>
            </div>
        </div>
        @endforeach

        {{-- EMPTY STATE --}}
        <div x-show="searchQuery.length > 0" x-cloak
             x-init="$watch('searchQuery', () => {})"
             class="text-center py-4 text-xs text-[#9B8B7E]"
             style="font-family: 'Lato', sans-serif;"
             x-effect="
                let visible = Array.from($el.parentElement.querySelectorAll('[style*=\'grid-template-columns: 1fr 90px\']')).some(el => el.style.display !== 'none');
                $el.style.display = (searchQuery.length > 0 && !visible) ? '' : 'none';
             ">
            Menu "<span x-text="searchQuery"></span>" tidak ditemukan.
        </div>
    </div>
</div>

                    <template x-for="(menu, index) in menus" :key="index">
                        <div>
                            <input type="hidden" :name="'menus['+index+'][id]'" :value="menu.id">
                            <input type="hidden" :name="'menus['+index+'][qty]'" :value="menu.qty">
                        </div>
                    </template>

                    {{-- CATATAN --}}
                    <div>
                        <label class="{{ $labelClass }}" style="font-family: 'Lato', sans-serif;">Catatan</label>
                        <textarea name="note" rows="2" placeholder="Opsional" class="{{ $inputClass }} resize-none" style="font-family: 'Lato', sans-serif;"></textarea>
                    </div>

                    {{-- TOTAL & SUBMIT --}}
                    <div class="bg-[#c88e5b]/10 border border-[#c88e5b]/30 rounded-xl p-3 sm:p-4 flex items-center justify-between gap-3">
                        <div>
                            <div class="text-[10px] text-[#6B5444] uppercase tracking-widest mb-0.5" style="font-family: 'Lato', sans-serif;">Total</div>
                            <div class="text-xl sm:text-3xl font-bold text-[#1C1007]" style="font-family: 'Playfair Display', serif;">
                                Rp <span x-text="totalFormatted"></span>
                            </div>
                        </div>
                        <button type="submit" id="contact-submit-btn"
                            class="relative flex-shrink-0 overflow-hidden bg-[#c88e5b] hover:bg-[#b07a4a] text-white text-[10px] sm:text-xs uppercase tracking-widest rounded-full px-4 sm:px-6 py-3 transition active:scale-95"
                            style="font-family: 'Lato', sans-serif; font-weight: 600;">
                            <span class="relative z-10">Kirim Booking</span>
                            <span class="submit-ripple absolute inset-0 bg-white/20 scale-0 rounded-full opacity-0 pointer-events-none"></span>
                        </button>
                    </div>

                </form>
            </div>
            @endif

            {{-- INFO KANAN --}}
            <div class="space-y-3 md:space-y-6">

                {{-- Jam Operasional --}}
                <div class="border border-[#E2D0BA] bg-white rounded-2xl shadow-[0_8px_30px_rgba(28,16,7,0.05)] p-4 md:p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-5 h-px bg-[#c88e5b]"></div>
                        <h3 class="text-[10px] md:text-xs uppercase tracking-[0.3em] text-[#c88e5b]" style="font-family: 'Lato', sans-serif; font-weight: 300;">Jam Operasional</h3>
                    </div>

                    @php
                        $dayNames = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                        $hoursData = array_values((array) $setting->business_hours);
                        $todayIdx = (now()->dayOfWeekIso - 1); // Senin = 0
                    @endphp

                    <div class="space-y-0">
                        @foreach($dayNames as $i => $namaHari)
                            @php $jam = $hoursData[$i] ?? null; @endphp
                            <div class="contact-hour-row flex items-center justify-between py-1.5 border-b border-[#E2D0BA] last:border-0"
                                 @if($i === $todayIdx) style="background: rgba(200,142,91,0.05);" @endif>
                                <span class="text-xs {{ $i === $todayIdx ? 'text-[#1C1007] font-semibold' : 'text-[#6B5444]' }}" style="font-family: 'Lato', sans-serif; font-weight: 300;">
                                    {{ $namaHari }}
                                </span>
                                <span class="text-xs {{ ($jam['is_closed'] ?? false) ? 'text-red-400' : 'text-[#c88e5b]' }}" style="font-family: 'Lato', sans-serif; font-weight: 300;">
                                    @if(!$jam)
                                        —
                                    @elseif($jam['is_closed'] ?? false)
                                        Tutup
                                    @else
                                        {{ $jam['open'] ?? '--:--' }} – {{ $jam['close'] ?? '--:--' }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Hubungi Kami --}}
                <div class="border border-[#E2D0BA] bg-white rounded-2xl shadow-[0_8px_30px_rgba(28,16,7,0.05)] p-4 md:p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-5 h-px bg-[#c88e5b]"></div>
                        <h3 class="text-[10px] md:text-xs uppercase tracking-[0.3em] text-[#c88e5b]" style="font-family: 'Lato', sans-serif; font-weight: 300;">Hubungi Kami</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        @if(optional($setting)->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp) }}" target="_blank"
                            class="border border-[#E2D0BA] rounded-xl p-3 flex flex-col items-center gap-1.5 hover:border-[#c88e5b]/50 hover:bg-[#FAF6F0] transition text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 text-[#c88e5b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span class="text-[10px] text-[#6B5444] uppercase tracking-widest" style="font-family: 'Lato', sans-serif;">WhatsApp</span>
                        </a>
                        @endif
                        @if(optional($setting)->instagram)
                        <a href="{{ $setting->instagram }}" target="_blank"
                            class="border border-[#E2D0BA] rounded-xl p-3 flex flex-col items-center gap-1.5 hover:border-[#c88e5b]/50 hover:bg-[#FAF6F0] transition text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 text-[#c88e5b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                            </svg>
                            <span class="text-[10px] text-[#6B5444] uppercase tracking-widest" style="font-family: 'Lato', sans-serif;">Instagram</span>
                        </a>
                        @endif
                        @if(optional($setting)->tiktok)
                        <a href="{{ $setting->tiktok }}" target="_blank"
                            class="border border-[#E2D0BA] rounded-xl p-3 flex flex-col items-center gap-1.5 hover:border-[#c88e5b]/50 hover:bg-[#FAF6F0] transition text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 text-[#c88e5b]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.27 8.27 0 0 0 4.83 1.54V6.78a4.85 4.85 0 0 1-1.06-.09z"/>
                            </svg>
                            <span class="text-[10px] text-[#6B5444] uppercase tracking-widest" style="font-family: 'Lato', sans-serif;">TikTok</span>
                        </a>
                        @endif
                        @if(optional($setting)->maps_link)
                        <a href="{{ $setting->maps_link }}" target="_blank"
                            class="border border-[#E2D0BA] rounded-xl p-3 flex flex-col items-center gap-1.5 hover:border-[#c88e5b]/50 hover:bg-[#FAF6F0] transition text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 text-[#c88e5b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span class="text-[10px] text-[#6B5444] uppercase tracking-widest" style="font-family: 'Lato', sans-serif;">Lokasi</span>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Maps --}}
                @if(optional($setting)->google_maps_embed)
                <div class="border border-[#E2D0BA] rounded-2xl overflow-hidden shadow-[0_8px_30px_rgba(28,16,7,0.05)] h-48 md:h-[280px]">
                    <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full opacity-90">
                        {!! $setting->google_maps_embed !!}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
Alpine.data('bookingForm', () => ({
    menus: [],
    selectedTableId: '',
    selectedLabel: '-- Pilih Tempat --',
    selectedCapacity: null,
    selectedIsPaid: false,
    selectedPricingType: '',
    selectedPrice: 0,
    durationHours: 1,

        increase(id, title, price) {
            let existing = this.menus.find(m => m.id === id);
            if (existing) {
                existing.qty++;
            } else {
                this.menus.push({ id, title, price, qty: 1 });
            }
        },

        decrease(id) {
            let existing = this.menus.find(m => m.id === id);
            if (!existing) return;
            existing.qty--;
            if (existing.qty <= 0) {
                this.menus = this.menus.filter(m => m.id !== id);
            }
        },

        getQty(id) {
            let m = this.menus.find(m => m.id === id);
            return m ? m.qty : 0;
        },

get tablePrice() {
    if (!this.selectedIsPaid) return 0;
    if (this.selectedPricingType === 'per_hour') {
        return (Number(this.selectedPrice) || 0) * (Number(this.durationHours) || 1);
    }
    return Number(this.selectedPrice) || 0;
},

get total() {
    const menuTotal = this.menus.reduce((sum, m) => sum + m.price * m.qty, 0);
    return menuTotal + this.tablePrice;
},

        get totalFormatted() {
            return this.total.toLocaleString('id-ID');
        },

        kirimBooking(e) {
            const form = e.target;

            const name       = form.querySelector('[name="name"]')?.value?.trim();
            const phone      = form.querySelector('[name="phone"]')?.value?.trim();
            const date       = form.querySelector('[name="booking_date"]')?.value;
            const eventTime  = form.querySelector('[name="event_time"]')?.value || '-';
            const foodTime   = form.querySelector('[name="food_ready_time"]')?.value || '-';
            const guests     = form.querySelector('[name="guest_count"]')?.value || '-';
            const note       = form.querySelector('[name="note"]')?.value?.trim() || '-';


const tableLabel = this.selectedLabel ?? '-';
const tableId    = this.selectedTableId ?? '';

            if (!name || !phone || !date) {
                alert('Mohon lengkapi Nama, WhatsApp, dan Tanggal terlebih dahulu.');
                return;
            }

            if (!tableId) {
                alert('Mohon pilih Tempat terlebih dahulu.');
                return;
            }

            const sessionEl   = form.querySelector('[name="restaurant_session_id"]:checked');
            const sessionCard = sessionEl ? sessionEl.closest('label') : null;
            const sessionName = sessionCard ? (sessionCard.querySelector('[style*="Playfair"]')?.innerText?.trim() ?? '-') : '-';
            const sessionTime = sessionCard ? (sessionCard.querySelector('.text-\\[10px\\]')?.innerText?.trim() ?? '') : '';

            const tgl = new Date(date);
            const formattedDate = tgl.toLocaleDateString('id-ID', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });

            let menuList = '';
            if (this.menus.length > 0) {
                menuList = this.menus.map(m =>
                    '  \u2022 ' + m.title + ' x' + m.qty + ' = Rp ' + (m.price * m.qty).toLocaleString('id-ID')
                ).join('\n');
            } else {
                menuList = '  (Tidak memesan menu)';
            }

            const waNumber = document.querySelector('meta[name="wa-number"]')?.content ?? '';
            const siteName = document.querySelector('meta[name="site-name"]')?.content ?? 'Restaurant';

            const sesiLine = (sessionName !== '-')
                ? '\uD83D\uDD50 *Sesi*       : ' + sessionName + ' (' + sessionTime + ')\n'
                : '';

let tablePriceLine = '';
if (this.selectedIsPaid) {
    const ket = this.selectedPricingType === 'per_hour'
        ? ' (' + this.durationHours + ' jam × Rp ' + Number(this.selectedPrice).toLocaleString('id-ID') + ')'
        : ' (sekali masuk)';

tablePriceLine =
    ' *Biaya Sewa Tempat*\n'
    + ' Rp ' + this.tablePrice.toLocaleString('id-ID')
    + ket + '\n';
}

            const pesan = 'Halo *' + siteName + '* \n\n'
                + 'Saya ingin melakukan *Reservasi* dengan detail berikut:\n\n'
                + '━━━━━━━━━━━━━━━━\n'
                + ' *Nama*       : ' + name + '\n'
                + ' *WhatsApp*   : ' + phone + '\n'
                + ' *Tanggal*    : ' + formattedDate + '\n'
                + ' *Tempat*     : ' + tableLabel + '\n'
                + ' *Jam Acara*  : ' + eventTime + '\n'
                + ' *Makan Siap* : ' + foodTime + '\n'
                + ' *Tamu*       : ' + guests + ' orang\n'
                + '━━━━━━━━━━━━━━━━\n'
                + ' *Pesanan Menu:*\n'
                + menuList + '\n'
                + tablePriceLine
                + sesiLine
                + '━━━━━━━━━━━━━━━━\n'
                + ' *Total*      : Rp ' + this.totalFormatted + '\n'
                + ' *Catatan*    : ' + note + '\n'
                + '━━━━━━━━━━━━━━━━\n\n'
                + 'Mohon konfirmasi reservasi saya. Terima kasih ';

            const encoded = encodeURIComponent(pesan);
            window.open('https://wa.me/' + waNumber + '?text=' + encoded, '_blank');

            const tableHidden = form.querySelector('[name="restaurant_table_id"]');
            if (tableHidden) {
                tableHidden.value = tableId;
            }

            form.submit();
        }
    }));
});

document.addEventListener('DOMContentLoaded', () => {
    // Input focus glow
    document.querySelectorAll('#contact input, #contact textarea').forEach(input => {
        input.addEventListener('focus', () => {
            input.style.boxShadow = '0 0 0 2px rgba(200,142,91,0.15)';
        });
        input.addEventListener('blur', () => {
            input.style.boxShadow = 'none';
        });
    });

    // Submit button ripple
    const submitBtn = document.querySelector('#contact-submit-btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function (e) {
            const ripple = this.querySelector('.submit-ripple');
            if (!ripple) return;

            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height) * 2;

            ripple.style.width  = size + 'px';
            ripple.style.height = size + 'px';
            ripple.style.left   = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top    = (e.clientY - rect.top  - size / 2) + 'px';
            ripple.style.transition = 'none';
            ripple.style.transform  = 'scale(0)';
            ripple.style.opacity    = '1';

            requestAnimationFrame(() => {
                ripple.style.transition = 'transform 0.5s ease, opacity 0.5s ease 0.2s';
                ripple.style.transform  = 'scale(1)';
                ripple.style.opacity    = '0';
            });
        });
    }
});
</script>
@endpush