

<?php $__env->startSection('title', 'Menu — ' . (optional($setting)->site_name ?? 'Restaurant')); ?>
<?php $__env->startSection('description', 'Lihat daftar menu lengkap kami: makanan, minuman, dan promo spesial dengan harga terbaru.'); ?>
<?php $__env->startSection('keywords', 'menu restoran, daftar harga makanan, menu ' . (optional($setting)->site_name ?? 'restaurant')); ?>

<?php $__env->startSection('content'); ?>

<?php
    // Hitung index relatif tiap item di dalam kategorinya (dipakai untuk logic "Lihat Semua" per kategori)
    $categoryCounters = [];
    foreach ($katalog as $item) {
        $slug = $item->category->slug ?? 'none';
        if (!isset($categoryCounters[$slug])) $categoryCounters[$slug] = 0;
        $item->category_relative_index = $categoryCounters[$slug];
        $categoryCounters[$slug]++;
    }

    $totalPerCategory = ['all' => count($katalog)];
    foreach ($categories as $category) {
        $totalPerCategory[$category->slug] = $categoryCounters[$category->slug] ?? 0;
    }
?>

<section
    id="menu"
    class="pt-28 md:pt-32 pb-16 md:pb-24 bg-[#FAF6F0] min-h-screen"
    x-data="{
        activeCategory: 'all',
        searchQuery: '',
        limit: window.innerWidth < 768 ? 8 : 12,
        showAll: false,
        totals: <?php echo e(json_encode($totalPerCategory)); ?>,
        visibleCount: 0,
        changeCategory(category) {
            this.activeCategory = category;
            this.showAll = false;
        }
    }"
    @resize.window="limit = window.innerWidth < 768 ? 8 : 12"
>
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        
        <div id="menu-header" class="mb-6 md:mb-8 text-center reveal-item">
            <p class="text-[#c88e5b] text-[10px] md:text-xs uppercase tracking-[0.3em] mb-2 md:mb-3" style="font-family:'Lato',sans-serif;">
                Daftar Lengkap
            </p>
            <h1
                class="text-3xl sm:text-5xl md:text-7xl font-bold text-[#c88e5b] tracking-widest uppercase leading-none"
                style="font-family: 'Playfair Display', serif;"
            >
                Menu
            </h1>
            <p class="text-[#6B5444] mt-3 md:mt-4 text-xs md:text-base italic max-w-lg mx-auto px-4" style="font-family:'Lato',sans-serif; font-weight:300;">
                <?php echo e(optional($setting)->site_name ?? 'Restaurant'); ?> — pilihan hidangan terbaik untuk Anda
            </p>
        </div>

        
        <div id="menu-search" class="max-w-md mx-auto mb-2 px-1 reveal-item">
            <div class="relative">
                <svg class="w-4 h-4 md:w-4.5 md:h-4.5 text-[#B5A090] absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="7"/>
                    <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    id="menu-search-input"
                    x-model="searchQuery"
                    placeholder="Cari menu favorit Anda..."
                    class="w-full bg-white border border-[#E2D0BA] text-[#1C1007] placeholder-[#B5A090] rounded-full pl-11 pr-10 py-3 md:py-3.5 text-xs md:text-sm shadow-[0_4px_20px_rgba(28,16,7,0.04)] focus:outline-none focus:border-[#c88e5b] transition"
                    style="font-family: 'Lato', sans-serif;"
                >
                <button
                    type="button"
                    x-show="searchQuery.length > 0"
                    x-cloak
                    @click="searchQuery = ''"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center rounded-full text-[#B5A090] hover:text-white hover:bg-[#c88e5b] transition"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            
            <p
                x-show="searchQuery.trim() !== ''"
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="text-center text-[10px] md:text-[11px] text-[#B5A090] mt-2 uppercase tracking-widest"
                style="font-family:'Lato',sans-serif; display:none;"
            >
                <span x-text="visibleCount"></span> menu ditemukan
            </p>
        </div>

        
        <div id="menu-filters" class="flex flex-nowrap overflow-x-auto gap-3 md:gap-4 mt-6 mb-8 md:mb-12 pb-2 justify-start md:justify-center scrollbar-hide -mx-4 px-4 md:mx-0 md:px-0 reveal-item">
            <button
                @click="changeCategory('all')"
                :class="activeCategory === 'all'
                    ? 'text-[#c88e5b] border-b border-[#c88e5b]'
                    : 'text-[#6B5444] hover:text-[#1C1007]'"
                class="flex-shrink-0 transition uppercase tracking-widest text-[11px] md:text-sm pb-1 whitespace-nowrap"
                style="font-family: 'Lato', sans-serif;"
            >
                Semua
            </button>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <button
                @click="changeCategory('<?php echo e($category->slug); ?>')"
                :class="activeCategory === '<?php echo e($category->slug); ?>'
                    ? 'text-[#c88e5b] border-b border-[#c88e5b]'
                    : 'text-[#6B5444] hover:text-[#1C1007]'"
                class="flex-shrink-0 transition uppercase tracking-widest text-[11px] md:text-sm pb-1 whitespace-nowrap"
                style="font-family: 'Lato', sans-serif;"
            >
                <?php echo e($category->name); ?>

            </button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <div class="flex flex-col min-h-[200px] w-full relative">

            
            <div
                x-show="searchQuery.trim() === '' && totals[activeCategory] === 0"
                class="absolute inset-0 flex items-center justify-center text-[#6B5444] text-xs md:text-sm uppercase tracking-widest text-center px-6"
                style="display: none; font-family: 'Lato', sans-serif;"
            >
                Menu belum tersedia untuk kategori ini
            </div>

            
            <div
                x-show="searchQuery.trim() !== '' && visibleCount === 0"
                x-cloak
                class="flex flex-col items-center justify-center text-center py-16 px-6"
                style="display: none;"
            >
                <svg class="w-9 h-9 text-[#C8B89A] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="7"/>
                    <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                </svg>
                <p class="text-[#6B5444] text-xs md:text-sm uppercase tracking-widest" style="font-family:'Lato',sans-serif;">
                    Menu "<span x-text="searchQuery" class="text-[#c88e5b]"></span>" tidak ditemukan
                </p>
            </div>

            <div class="menu-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6 w-full" x-effect="visibleCount = $el.querySelectorAll(':scope > a:not([style*=\'display: none\'])').length">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $katalog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
<?php $menuKeyword = strtolower(addslashes($item->title)); ?>
<a
    href="<?php echo e(route('menu.show', $item->slug)); ?>"
    x-show="
        (searchQuery.trim() !== ''
            ? (activeCategory === 'all' || activeCategory === '<?php echo e($item->category->slug ?? ''); ?>') && '<?php echo e($menuKeyword); ?>'.includes(searchQuery.toLowerCase().trim())
            : (activeCategory === 'all' && (showAll || <?php echo e($loop->index); ?> < limit)) || (activeCategory === '<?php echo e($item->category->slug ?? ''); ?>' && (showAll || <?php echo e($item->category_relative_index ?? 0); ?> < limit))
        )"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    data-delay="<?php echo e(($loop->index % 8) * 50); ?>"
    class="menu-card group bg-white border border-[#E2D0BA] rounded-2xl overflow-hidden active:scale-[0.98] flex flex-col transition-all duration-300 hover:shadow-[0_8px_30px_rgba(200,142,91,0.15)] hover:border-[#c88e5b]/50 hover:-translate-y-1 opacity-0 translate-y-6"
>
    <div class="relative w-full overflow-hidden" style="aspect-ratio: 4/3;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->image): ?>
            <img
                src="<?php echo e(asset('storage/' . $item->image)); ?>"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                alt="<?php echo e($item->title); ?>"
                loading="lazy"
            >
        <?php else: ?>
            <div class="w-full h-full bg-[#F0E8DC] flex items-center justify-center">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-[#C8B89A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="px-2.5 py-2.5 md:px-3 md:py-3 flex flex-col gap-0.5">
        <span class="text-[9px] md:text-[10px] uppercase tracking-[0.15em] text-[#c88e5b]" style="font-family: 'Lato', sans-serif; font-weight: 400;">
            <?php echo e($item->category->name ?? 'Menu'); ?>

        </span>

        <h3 class="text-[13px] md:text-base font-bold text-[#1C1007] uppercase leading-tight line-clamp-2 group-hover:text-[#c88e5b] transition-colors duration-300" style="font-family: 'Playfair Display', serif;">
            <?php echo e($item->title); ?>

        </h3>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->price): ?>
        <p class="text-[11px] md:text-[12px] font-semibold text-[#c88e5b] mt-1" style="font-family: 'Lato', sans-serif;">
            Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?>

        </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->description): ?>
        <p class="text-[10px] md:text-[11px] text-[#6B5444] line-clamp-1 mt-0.5" style="font-family: 'Lato', sans-serif; font-weight: 300;">
            <?php echo e($item->description); ?>

        </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</a>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            
            <div x-show="searchQuery.trim() === '' && totals[activeCategory] > limit" class="mt-10 md:mt-12 pt-4 md:pt-6 flex justify-center gap-4 w-full" style="display: none;">
                <button
                    x-show="!showAll"
                    @click="showAll = true"
                    class="bg-transparent border border-[#c88e5b] text-[#c88e5b] hover:bg-[#c88e5b] hover:text-white transition-all duration-300 px-6 py-2.5 md:px-8 md:py-3 rounded-full text-[10px] md:text-xs font-bold tracking-widest uppercase"
                    style="font-family: 'Lato', sans-serif;"
                >
                    Lihat Semua Menu
                </button>
                <button
                    x-show="showAll"
                    @click="showAll = false"
                    class="bg-transparent border border-[#E2D0BA] text-[#6B5444] hover:border-[#c88e5b] hover:text-[#c88e5b] transition-all duration-300 px-6 py-2.5 md:px-8 md:py-3 rounded-full text-[10px] md:text-xs font-bold tracking-widest uppercase"
                    style="font-family: 'Lato', sans-serif;"
                >
                    Lihat Lebih Sedikit
                </button>
            </div>
        </div>

        
        <div id="menu-cta" class="text-center mt-12 md:mt-16 pt-8 md:pt-10 border-t border-[#E2D0BA] reveal-item">
            <p class="text-[#6B5444] text-xs md:text-sm mb-3 md:mb-4" style="font-family:'Lato',sans-serif;">Sudah menentukan pilihan?</p>
            <a
                href="<?php echo e(route('contact')); ?>"
                id="menu-cta-btn"
                class="menu-cta-btn relative inline-flex items-center justify-center w-full sm:w-auto bg-[#c88e5b] hover:bg-[#b07a4a] text-white text-[11px] md:text-xs uppercase tracking-widest px-8 py-3.5 md:py-4 rounded-full transition-all duration-300 active:scale-95 overflow-hidden"
                style="font-family:'Lato',sans-serif; font-weight:600;"
            >
                <span class="menu-cta-pulse absolute inset-0 rounded-full"></span>
                <span class="relative z-10">Reservasi Meja Sekarang</span>
            </a>
        </div>
    </div>

</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .reveal-item {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.7s ease-out, transform 0.7s ease-out;
    }
    .reveal-item.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    .menu-card.card-visible {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }

    /* Glow halus saat search box difokuskan */
    #menu-search-input {
        transition: box-shadow 0.3s ease, border-color 0.3s ease;
    }
    #menu-search-input.focused-glow {
        box-shadow: 0 0 0 3px rgba(200, 142, 91, 0.15);
    }

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

    /* Feedback tap ringan pada tombol filter kategori */
    .menu-filter-btn-ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(200, 142, 91, 0.25);
        transform: scale(0);
        animation: menuRippleAnim 500ms ease-out;
        pointer-events: none;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ================= REVEAL ON SCROLL =================
    const revealItems = document.querySelectorAll('.reveal-item');
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    revealItems.forEach(el => revealObserver.observe(el));

    // Header langsung terlihat saat halaman dimuat (biasanya sudah di viewport)
    requestAnimationFrame(() => {
        document.getElementById('menu-header')?.classList.add('is-visible');
        document.getElementById('menu-search')?.classList.add('is-visible');
        document.getElementById('menu-filters')?.classList.add('is-visible');
    });

    // ================= STAGGERED CARD REVEAL =================
    const menuCards = document.querySelectorAll('.menu-card');
    const cardObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const delay = el.dataset.delay || 0;
                el.style.transitionDelay = `${delay}ms`;
                el.classList.add('card-visible');
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.1 });
    menuCards.forEach(card => cardObserver.observe(card));

    // ================= SEARCH INPUT GLOW =================
    const searchInput = document.getElementById('menu-search-input');
    if (searchInput) {
        searchInput.addEventListener('focus', () => searchInput.classList.add('focused-glow'));
        searchInput.addEventListener('blur', () => searchInput.classList.remove('focused-glow'));
    }

    // ================= CTA BUTTON: MAGNETIC + RIPPLE =================
    const ctaBtn = document.getElementById('menu-cta-btn');
    if (ctaBtn) {
        const isTouch = window.matchMedia('(pointer: coarse)').matches;

        if (!isTouch) {
            ctaBtn.addEventListener('mousemove', (e) => {
                const rect = ctaBtn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                ctaBtn.style.transform = `translate(${x * 0.12}px, ${y * 0.3}px)`;
            });
            ctaBtn.addEventListener('mouseleave', () => {
                ctaBtn.style.transform = 'translate(0, 0)';
            });
        }

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

    // ================= CATEGORY FILTER TAP FEEDBACK =================
    document.querySelectorAll('#menu-filters button').forEach(btn => {
        btn.style.position = 'relative';
        btn.style.overflow = 'hidden';
        btn.addEventListener('click', function (e) {
            const rect = btn.getBoundingClientRect();
            const ripple = document.createElement('span');
            const size = Math.max(rect.width, rect.height) * 1.4;
            const x = (e.clientX || rect.left + rect.width / 2) - rect.left - size / 2;
            const y = (e.clientY || rect.top + rect.height / 2) - rect.top - size / 2;

            ripple.classList.add('menu-filter-btn-ripple');
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;

            btn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 500);
        });
    });

});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/makrupp123/Development/Laravel/restourant/resources/views/menu/index.blade.php ENDPATH**/ ?>