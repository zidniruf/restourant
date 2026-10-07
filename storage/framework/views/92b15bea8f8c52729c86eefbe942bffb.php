

<?php $__env->startSection('title', (optional($setting)->site_name ?? 'Restaurant') . ' — Reservasi Meja Online'); ?>
<?php $__env->startSection('description', optional($setting)->hero_description ?? 'Restoran premium dengan pengalaman kuliner terbaik. Reservasi meja online mudah dan cepat.'); ?>
<?php $__env->startSection('og_image', optional($setting)->hero_background ? asset('storage/' . $setting->hero_background) : ''); ?>

<?php $__env->startSection('content'); ?>


<section id="home" class="relative min-h-[100svh] min-h-screen overflow-hidden flex items-center justify-center bg-[#FAF6F0]">

<?php
    $heroMedia = optional($setting)->hero_background;
    $extension = strtolower(pathinfo($heroMedia ?? '', PATHINFO_EXTENSION));
    $isVideo = in_array($extension, ['mp4', 'webm', 'ogg']);
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($heroMedia): ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVideo): ?>
        <video id="hero-bg" autoplay muted loop playsinline preload="metadata"
            class="absolute inset-0 w-full h-full object-cover scale-110 transition-transform duration-[10s] ease-out">
            <source src="<?php echo e(asset('storage/'.$heroMedia)); ?>">
        </video>
    <?php else: ?>
        <img id="hero-bg" src="<?php echo e(asset('storage/'.$heroMedia)); ?>"
            class="absolute inset-0 w-full h-full object-cover scale-110 transition-transform duration-[10s] ease-out"
            alt="<?php echo e(optional($setting)->site_name); ?>">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="absolute inset-0 bg-gradient-to-b from-[#1C1007]/80 via-[#1C1007]/60 to-[#1C1007]/90"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/20"></div>

    
    <div class="absolute inset-3 sm:inset-6 border border-[#c88e5b]/25 pointer-events-none z-10"></div>

    <div class="relative z-20 max-w-5xl mx-auto px-6 sm:px-8 pt-24 pb-24 sm:pb-20 w-full text-center flex flex-col items-center">

        <div id="hero-badge" class="flex items-center gap-3 sm:gap-4 mb-7 sm:mb-9 opacity-0 translate-y-4 transition-all duration-700 ease-out">
            <span class="block w-8 sm:w-10 h-px bg-gradient-to-r from-transparent to-[#c88e5b]"></span>
            <span class="text-[#d9a869] text-[10px] sm:text-[11px] uppercase tracking-[0.35em] sm:tracking-[0.4em] whitespace-nowrap" style="font-family:'Lato',sans-serif; font-weight:400;">
                <?php echo e(optional($setting)->hero_badge ?? 'Fine Dining Experience'); ?>

            </span>
            <span class="block w-8 sm:w-10 h-px bg-gradient-to-l from-transparent to-[#c88e5b]"></span>
        </div>

        <h1 id="hero-title"
            class="text-[26px] sm:text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAF6F0] tracking-[0.03em] sm:tracking-[0.05em] leading-[1.3] sm:leading-tight opacity-0 translate-y-8 transition-all duration-1000 ease-out px-2"
            style="font-family: 'Playfair Display', serif; text-shadow: 0 2px 24px rgba(0,0,0,0.45);">
            <?php echo e(optional($setting)->hero_title ?? 'Where Every Bite Tells a Story'); ?>

        </h1>

        <div id="hero-divider" class="flex items-center gap-3 my-6 sm:my-7 opacity-0 transition-all duration-700 delay-500 ease-out">
            <span class="block w-10 sm:w-14 h-px bg-[#c88e5b]/50"></span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#c88e5b]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/>
            </svg>
            <span class="block w-10 sm:w-14 h-px bg-[#c88e5b]/50"></span>
        </div>

        <p id="hero-subtitle"
            class="text-xs sm:text-sm md:text-base text-[#E2D0BA]/90 leading-relaxed max-w-[290px] sm:max-w-2xl italic opacity-0 translate-y-8 transition-all duration-1000 delay-300 ease-out"
            style="font-family: 'Lato', sans-serif; font-weight: 300;">
            <?php echo e(optional($setting)->hero_subtitle ?? 'Nikmati makanan terbaik dengan suasana restoran modern dan pelayanan premium.'); ?>

        </p>

        <div class="mt-8 sm:mt-10">
            <a href="<?php echo e(route('contact')); ?>"
               id="reservasi-btn"
               class="reservasi-btn group relative inline-flex items-center gap-2 sm:gap-3 bg-transparent border border-[#c88e5b] hover:bg-[#c88e5b] text-[#FAF6F0] text-[10px] sm:text-xs uppercase tracking-[0.25em] px-7 sm:px-12 py-3 sm:py-4 rounded-full transition-all duration-500 hover:-translate-y-0.5 overflow-hidden"
               style="font-family:'Lato',sans-serif; font-weight:500;">

                <span class="reservasi-pulse absolute inset-0 rounded-full"></span>

                <svg class="relative z-10 w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>

                <span class="relative z-10">Reservasi Sekarang</span>

                <svg class="relative z-10 w-3 h-3 sm:w-3.5 sm:h-3.5 transition-transform duration-500 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

    </div>

    <div class="absolute bottom-6 sm:bottom-9 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50 z-20">
        <span class="text-[#E2D0BA] text-[9px] uppercase tracking-[0.35em]" style="font-family:'Lato',sans-serif;">Scroll</span>
        <div class="w-px h-8 sm:h-10 bg-gradient-to-b from-[#c88e5b] to-transparent animate-pulse"></div>
    </div>

</section>


<section class="py-10 sm:py-16 md:py-24 bg-[#FAF6F0]">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">

        
        <div class="text-center mb-8 sm:mb-12 md:mb-16 flex flex-col items-center">
            <p id="special-badge"
                class="reveal-item text-[#c88e5b] text-[10px] sm:text-xs uppercase tracking-[0.25em] sm:tracking-[0.3em] mb-2 sm:mb-3"
                style="font-family: 'Lato', sans-serif;">
                Recommendation For You
            </p>

            <h2 id="special-title"
                class="reveal-item text-2xl sm:text-3xl md:text-5xl font-bold text-[#1C1007] tracking-widest uppercase leading-none"
                style="font-family: 'Playfair Display', serif;">
                Today's Special
            </h2>

            <div id="special-divider"
                class="reveal-item w-12 sm:w-16 h-px bg-[#c88e5b]/50 mt-3 sm:mt-5">
            </div>
        </div>

        
        <div class="relative highlight-wrapper -mx-4 px-4 sm:mx-0 sm:px-0">
            <div id="highlightTrack"
                class="highlight-track flex gap-3 sm:gap-4 md:gap-6 overflow-x-auto scrollbar-hide snap-x snap-mandatory">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $highlights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>

                    <a href="<?php echo e(route('menu.show', $item->slug)); ?>"
                        class="product-card snap-start
                               w-[130px] sm:w-[200px] md:w-[240px]
                               flex-shrink-0
                               bg-white
                               border border-[#E2D0BA]
                               rounded-xl sm:rounded-2xl
                               overflow-hidden
                               flex flex-col
                               transition-all duration-500
                               hover:border-[#c88e5b]/60
                               hover:shadow-[0_8px_30px_rgba(200,142,91,0.15)]
                               group">

                        
                        <div class="aspect-square overflow-hidden bg-[#F0E8DC]">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->image): ?>
                                <img
                                    src="<?php echo e(asset('storage/' . $item->image)); ?>"
                                    alt="<?php echo e($item->title); ?>"
                                    draggable="false"
                                    class="w-full h-full object-cover transition duration-700 ease-in-out group-hover:scale-110">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        
                        <div class="p-2.5 sm:p-4 text-center">

                            <h3
                                class="text-xs sm:text-sm md:text-base font-bold text-[#1C1007] uppercase tracking-wide line-clamp-1 transition-colors duration-300 group-hover:text-[#c88e5b]"
                                style="font-family: 'Playfair Display', serif;">
                                <?php echo e($item->title); ?>

                            </h3>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->price): ?>
                                <p
                                    class="mt-1 text-[#c88e5b] font-semibold text-[10px] sm:text-xs md:text-sm"
                                    style="font-family: 'Lato', sans-serif;">
                                    Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        </div>

                    </a>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            </div>
        </div>

        
        <div class="text-center mt-8 sm:mt-12">

            <a href="<?php echo e(route('menu.index')); ?>"
                id="special-cta"
                class="reveal-item inline-block border border-[#c88e5b]
                       text-[#c88e5b]
                       hover:bg-[#c88e5b]
                       hover:text-white
                       transition-all duration-300
                       px-6 sm:px-8
                       py-2.5 sm:py-3
                       rounded-full
                       text-[10px] sm:text-xs
                       font-bold
                       tracking-widest
                       uppercase"
                style="font-family: 'Lato', sans-serif;">

                Lihat Semua Menu

            </a>

        </div>

    </div>

</section>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($promos->isNotEmpty()): ?>

<?php
    $promo = $promos->first();
?>

<section class="py-10 sm:py-16 bg-[#FAF6F0]">

    <div class="max-w-7xl mx-auto px-5 sm:px-6">

        <div id="promo-card"
            class="group relative overflow-hidden rounded-2xl sm:rounded-3xl
            border border-[#c88e5b]/20
            shadow-[0_20px_60px_rgba(0,0,0,.08)]
            bg-[#1C1007]
            min-h-[170px] sm:min-h-[240px] lg:min-h-[340px]
            opacity-0 translate-y-10 scale-[0.97]
            transition-all duration-1000 ease-out">

            
            <img
                id="promo-bg-img"
                src="<?php echo e($promo->image ? asset('storage/'.$promo->image) : asset('images/default-promo.jpg')); ?>"
                class="absolute inset-0 w-full h-full object-cover opacity-100 transition-transform duration-[3000ms] ease-out group-hover:scale-110"
                alt="<?php echo e($promo->title); ?>">

            <div class="absolute inset-0 bg-gradient-to-r from-[#1C1007] via-[#1C1007]/90 to-[#1C1007]/70"></div>

            <div class="relative z-10
                grid
                grid-cols-[90px_1fr]
                sm:grid-cols-[140px_1fr]
                md:grid-cols-[220px_1fr]
                lg:grid-cols-[320px_1fr]
                gap-3 sm:gap-6 lg:gap-8
                items-center
                p-3 sm:p-6 lg:p-10">

                <div id="promo-image-wrap" class="opacity-0 -translate-x-6 transition-all duration-1000 delay-200 ease-out">
                    <img
                        src="<?php echo e($promo->image ? asset('storage/'.$promo->image) : asset('images/default-promo.jpg')); ?>"
                        alt="<?php echo e($promo->title); ?>"
                        class="w-full
                               h-[90px]
                               sm:h-[150px]
                               md:h-[220px]
                               lg:h-64
                               object-cover
                               rounded-xl
                               shadow-xl
                               transition-all
                               duration-700
                               group-hover:scale-105 group-hover:rotate-1">
                </div>

                
                <div id="promo-content" class="opacity-0 translate-y-8 transition-all duration-1000 delay-400 ease-out">

                    <span id="promo-badge-flame"
                        class="inline-flex items-center gap-1
                        text-[#c88e5b]
                        uppercase
                        tracking-[0.2em]
                        text-[9px]
                        sm:text-xs">
                        <span class="promo-flame-pulse">🔥</span> Promo Spesial
                    </span>

                    <h2
                        class="mt-2
                        text-lg
                        sm:text-3xl
                        md:text-4xl
                        lg:text-5xl
                        font-bold
                        text-white"
                        style="font-family:'Playfair Display',serif;">

                        <?php echo e($promo->title); ?>


                    </h2>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($promo->start_date && $promo->end_date): ?>

                    <p class="mt-2
                        text-[#c88e5b]
                        text-[10px]
                        sm:text-xs
                        md:text-sm
                        uppercase
                        tracking-widest">

                        Berlaku
                        <?php echo e($promo->start_date->format('d M Y')); ?>

                        -
                        <?php echo e($promo->end_date->format('d M Y')); ?>


                    </p>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <p class="
                        mt-2
                        sm:mt-4
                        text-[11px]
                        sm:text-sm
                        md:text-base
                        text-gray-300
                        leading-relaxed
                        line-clamp-3">

                        <?php echo e($promo->description); ?>


                    </p>

<div class="mt-8">

<a href="<?php echo e(route('contact')); ?>"
        class="promo-cta-btn relative inline-flex items-center gap-3
               bg-[#c88e5b]
               hover:bg-[#b57b4d]
               text-white
               px-4 sm:px-6 lg:px-8
               py-2 sm:py-3 lg:py-4
               text-[10px] sm:text-xs
               rounded-full
               uppercase
               tracking-widest
               transition-all duration-300
               overflow-hidden">

        <span class="promo-cta-pulse absolute inset-0 rounded-full"></span>

        <span class="relative z-10">
            Reservasi Sekarang →
        </span>

    </a>

</div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<style>
    .reveal-item {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.7s ease-out, transform 0.7s ease-out;
    }
    .reveal-item.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($galleries->count() > 0): ?>
<section id="gallery" class="py-16 md:py-24 bg-[#0a0a0a]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div id="gallery-heading" class="text-center mb-8 sm:mb-12 flex flex-col items-center opacity-0 translate-y-10 transition-all duration-1000 ease-out">
            <h2 class="text-2xl sm:text-3xl md:text-5xl font-bold text-[#c88e5b] tracking-widest uppercase" style="font-family: 'Playfair Display', serif;">
                Gallery
            </h2>
            <p class="text-gray-400 text-xs sm:text-sm mt-2 sm:mt-3 italic px-4" style="font-family: 'Lato', sans-serif; font-weight: 300;">
                Suasana &amp; sajian dari dapur kami
            </p>
        </div>
        <div class="columns-3 md:columns-4 gap-2 sm:gap-3 space-y-2 sm:space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="gallery-item break-inside-avoid overflow-hidden rounded-lg sm:rounded-xl border border-white/10 hover:border-[#c88e5b]/50 transition-all duration-700 ease-out opacity-0 translate-y-8 scale-95">
                    <img src="<?php echo e(asset('storage/' . $gallery->image)); ?>" class="w-full object-cover" loading="lazy" alt="<?php echo e($gallery->title ?? 'Galeri'); ?>">
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <div id="gallery-cta" class="text-center mt-8 sm:mt-10 opacity-0 translate-y-6 transition-all duration-1000 ease-out">
            <a href="<?php echo e(route('gallery')); ?>" class="inline-block border border-[#c88e5b] text-[#c88e5b] hover:bg-[#c88e5b] hover:text-white transition-all duration-300 px-6 sm:px-8 py-2.5 sm:py-3 rounded-full text-[10px] sm:text-xs font-bold tracking-widest uppercase" style="font-family: 'Lato', sans-serif;">
                Lihat Semua Galeri
            </a>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<section id="about" class="relative py-12 sm:py-16 md:py-24 bg-[#FAF6F0] overflow-hidden">

    <div id="about-bg-letter"
        class="absolute right-0 top-1/2 -translate-y-1/2 text-[12rem] sm:text-[18rem] md:text-[40rem] font-serif text-[#1C1007]/[0.03] select-none pointer-events-none leading-none transition-transform duration-1000 ease-out"
        style="font-family: 'Playfair Display', serif;">
        A
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-6">
        <div class="grid md:grid-cols-2 gap-0 md:gap-16 items-center">

            <div class="relative flex flex-col gap-4">
                <img id="about-image"
                    src="<?php echo e($setting->hero_featured_image ? asset('storage/' . $setting->hero_featured_image) : 'https://images.unsplash.com/photo-1552566626-52f8b828add9'); ?>"
                    class="w-full h-[220px] sm:h-[300px] md:h-[340px] object-cover rounded-t-2xl rounded-b-none md:rounded-2xl shadow-[0_12px_40px_rgba(28,16,7,0.15)] opacity-0
-translate-x-10
md:-translate-x-12 transition-all duration-1000 delay-300 ease-out relative z-0"
                    alt="Suasana <?php echo e(optional($setting)->site_name ?? 'restoran'); ?>"
                >
            </div>

            
<div id="about-card"
class="
relative z-10
transition-all duration-500
-mt-14 sm:-mt-16 md:mt-0
-ml-4 sm:-ml-6 md:ml-0
mr-4 sm:mr-6 md:mx-0
bg-[#0a0a0a] md:bg-transparent
rounded-r-2xl rounded-l-none md:rounded-none
shadow-[0_-10px_30px_rgba(28,16,7,0.08)] md:shadow-none
px-5 sm:px-7 py-6 sm:py-8 md:p-0
pl-5 md:pl-6
">

                <div id="about-heading" class="mb-3 sm:mb-6 opacity-0 translate-y-10 transition-all duration-1000 ease-out">
                    <h2 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-[#c88e5b] tracking-widest uppercase leading-none"
                        style="font-family: 'Playfair Display', serif;">
                        About Us
                    </h2>
                </div>

                <div class="w-10 sm:w-12 h-px bg-[#c88e5b]/50 mb-4 sm:mb-6 opacity-0 transition-all duration-700 delay-200 ease-out" id="about-line"></div>

<div id="about-text"
    class="text-xs sm:text-sm text-white
md:text-[#6B5444] leading-relaxed text-justify opacity-0 translate-y-10 transition-all duration-1000 delay-300 ease-out"
    style="font-family: 'Lato', sans-serif; font-weight: 300;">
    <p class="line-clamp-4">
        <?php
            $aboutExcerpt = optional($setting)->hero_description
                ? \Illuminate\Support\Str::limit(strip_tags($setting->hero_description), 260)
                : 'Kami menghadirkan makanan berkualitas tinggi dengan suasana nyaman dan pelayanan terbaik untuk pengalaman makan yang tidak terlupakan.';
        ?>
        <?php echo e($aboutExcerpt); ?>

    </p>
    <a href="<?php echo e(route('about')); ?>" class="inline-block mt-3 sm:mt-4 text-white
md:text-[#6B5444] text-[10px] sm:text-xs uppercase tracking-widest hover:underline">
        Selengkapnya &rarr;
    </a>
</div>
            </div>

        </div>
    </div>
</section>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testimonis->count() > 0): ?>
<section id="testimonial" class="relative py-12 sm:py-20 md:py-28 bg-[#F0E8DC] overflow-hidden">

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
        <div id="testimonial-heading" class="flex flex-col items-end mb-8 sm:mb-16 md:mb-24 opacity-0 translate-y-10 transition-all duration-1000 ease-out">
            <h2 class="text-2xl sm:text-5xl md:text-7xl font-bold text-[#c88e5b] tracking-widest uppercase leading-none mb-2 sm:mb-4 md:mb-6 text-right" style="font-family: 'Playfair Display', serif;">
                Testimonials
            </h2>
            <div class="w-14 sm:w-32 md:w-64 h-[1px] bg-[#c88e5b]/40"></div>
        </div>

        <div class="relative w-full overflow-hidden">
            <div class="absolute inset-y-0 left-0 w-6 sm:w-12 md:w-32 bg-gradient-to-r from-[#F0E8DC] to-transparent z-20 pointer-events-none"></div>
            <div class="absolute inset-y-0 right-0 w-6 sm:w-12 md:w-32 bg-gradient-to-l from-[#F0E8DC] to-transparent z-20 pointer-events-none"></div>

            <div class="testimonial-marquee gap-4 sm:gap-6 md:gap-10 py-6 sm:py-8">
                <div class="flex gap-4 sm:gap-6 md:gap-10">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $testimonis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimoni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="w-[230px] sm:w-[330px] md:w-[380px] flex flex-col justify-between whitespace-normal flex-shrink-0 bg-white border border-[#E2D0BA] rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 transition-all duration-500 hover:border-[#c88e5b]/50 hover:shadow-[0_12px_30px_rgba(28,16,7,0.10)] hover:-translate-y-1">
                            <div>
                                <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-5">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testimoni->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $testimoni->image)); ?>" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full object-cover grayscale opacity-80" alt="<?php echo e($testimoni->name ?? 'Pelanggan'); ?>">
                                    <?php else: ?>
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full bg-[#F0E8DC] border border-[#c88e5b]/40 text-[#c88e5b] flex items-center justify-center text-xs sm:text-sm" style="font-family: 'Playfair Display', serif;">
                                            <?php echo e(strtoupper(substr($testimoni->name ?? 'P', 0, 1))); ?>

                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <h3 class="font-medium text-[#c88e5b] text-xs sm:text-sm md:text-base tracking-wide truncate" style="font-family: 'Lato', sans-serif;">
                                        <?php echo e($testimoni->name ?? 'Pelanggan'); ?>

                                    </h3>
                                </div>
                                <p class="text-[#6B5444] text-[11px] sm:text-xs md:text-sm leading-relaxed mb-4 sm:mb-6 text-justify" style="font-family: 'Lato', sans-serif; font-weight: 300;">
                                    "<?php echo e($testimoni->message); ?>"
                                </p>
                            </div>
                            <div class="flex text-[#c88e5b] text-xs sm:text-sm md:text-base gap-0.5 mt-auto">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 0; $i < 5; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i < ($testimoni->rating ?? 5)): ?> <span>★</span> <?php else: ?> <span class="text-[#E2D0BA]">★</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $testimonis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimoni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="w-[230px] sm:w-[330px] md:w-[380px] flex flex-col justify-between whitespace-normal flex-shrink-0 bg-white border border-[#E2D0BA] rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 transition-all duration-500 hover:border-[#c88e5b]/50 hover:shadow-[0_12px_30px_rgba(28,16,7,0.10)] hover:-translate-y-1" aria-hidden="true">
                            <div>
                                <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-5">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testimoni->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $testimoni->image)); ?>" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full object-cover grayscale opacity-80" alt="">
                                    <?php else: ?>
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full bg-[#F0E8DC] border border-[#c88e5b]/40 text-[#c88e5b] flex items-center justify-center text-xs sm:text-sm" style="font-family: 'Playfair Display', serif;">
                                            <?php echo e(strtoupper(substr($testimoni->name ?? 'P', 0, 1))); ?>

                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <h3 class="font-medium text-[#c88e5b] text-xs sm:text-sm md:text-base tracking-wide truncate" style="font-family: 'Lato', sans-serif;">
                                        <?php echo e($testimoni->name ?? 'Pelanggan'); ?>

                                    </h3>
                                </div>
                                <p class="text-[#6B5444] text-[11px] sm:text-xs md:text-sm leading-relaxed mb-4 sm:mb-6 text-justify" style="font-family: 'Lato', sans-serif; font-weight: 300;">
                                    "<?php echo e($testimoni->message); ?>"
                                </p>
                            </div>
                            <div class="flex text-[#c88e5b] text-xs sm:text-sm md:text-base gap-0.5 mt-auto">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 0; $i < 5; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i < ($testimoni->rating ?? 5)): ?> <span>★</span> <?php else: ?> <span class="text-[#E2D0BA]">★</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .testimonial-marquee {
        display: flex;
        width: max-content;
        animation: marquee 35s linear infinite;
    }
    .testimonial-marquee:hover { animation-play-state: paused; }

    .highlight-track {
        scroll-behavior: auto;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    /* ================= ABOUT ANIMATION ================= */

#about-image{
    transition:transform .8s ease;
}

#about-card{
    transition:
        transform .45s ease,
        box-shadow .45s ease,
        opacity .9s ease;
}

#about-card:hover{
    transform:translateY(-8px);
}

@keyframes aboutFloat{

    0%,100%{
        transform:translateY(0px);
    }

    50%{
        transform:translateY(-10px);
    }

}

#about-image.floating{
    animation:aboutFloat 5s ease-in-out infinite;
}

/* Shine effect */

#about-heading h2{
    position:relative;
    overflow:hidden;
    display:inline-block;
}

#about-heading h2::after{

    content:"";

    position:absolute;

    top:0;

    left:-150%;

    width:70%;

    height:100%;

    background:linear-gradient(
        90deg,
        transparent,
        rgba(255,255,255,.35),
        transparent
    );

    animation:aboutShine 5s linear infinite;

}

@keyframes aboutShine{

    100%{
        left:220%;
    }

}

    /* Pulse glow ring di belakang tombol */
.reservasi-pulse {
    box-shadow: 0 0 0 0 rgba(200, 142, 91, 0.6);
    animation: reservasiPulse 2.5s ease-out infinite;
    pointer-events: none;
}
@keyframes reservasiPulse {
    0%   { box-shadow: 0 0 0 0 rgba(200, 142, 91, 0.55); }
    70%  { box-shadow: 0 0 0 14px rgba(200, 142, 91, 0); }
    100% { box-shadow: 0 0 0 0 rgba(200, 142, 91, 0); }
}

/* Ripple effect saat klik */
.reservasi-btn .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.45);
    transform: scale(0);
    animation: rippleAnim 600ms ease-out;
    pointer-events: none;
}
@keyframes rippleAnim {
    to { transform: scale(2.5); opacity: 0; }
}

/* Reveal state untuk item gallery yang sudah tampil */
.gallery-item.is-visible {
    opacity: 1 !important;
    transform: translateY(0) scale(1) !important;
}

/* ================= PROMO ANIMATION ================= */
    .promo-flame-pulse {
        display: inline-block;
        animation: flamePulse 1.8s ease-in-out infinite;
    }
    @keyframes flamePulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50%      { transform: scale(1.25); opacity: 0.75; }
    }

    .promo-cta-pulse {
        box-shadow: 0 0 0 0 rgba(200, 142, 91, 0.6);
        animation: promoCtaPulse 2.2s ease-out infinite;
        pointer-events: none;
    }
    @keyframes promoCtaPulse {
        0%   { box-shadow: 0 0 0 0 rgba(200, 142, 91, 0.55); }
        70%  { box-shadow: 0 0 0 12px rgba(200, 142, 91, 0); }
        100% { box-shadow: 0 0 0 0 rgba(200, 142, 91, 0); }
    }

    .promo-cta-btn .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.45);
        transform: scale(0);
        animation: rippleAnim 600ms ease-out;
        pointer-events: none;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
// Efek "hidup" untuk tombol Reservasi
(() => {
    const btn = document.getElementById('reservasi-btn');
    if (!btn) return;

    // 1. Efek magnetic — tombol sedikit mengikuti posisi kursor (desktop only)
    const isTouch = window.matchMedia('(pointer: coarse)').matches;
    if (!isTouch) {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = `translate(${x * 0.15}px, ${y * 0.35}px)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translate(0, 0)';
        });
    }

    // 2. Ripple effect saat klik / tap
    btn.addEventListener('click', function (e) {
        const rect = btn.getBoundingClientRect();
        const ripple = document.createElement('span');
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.classList.add('ripple');
        ripple.style.width = ripple.style.height = `${size}px`;
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;

        btn.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
})();
    // Hero entrance animation
    const els = {
        badge:    { el: document.getElementById('hero-badge'),    delay: 0 },
        title:    { el: document.getElementById('hero-title'),    delay: 200 },
        divider:  { el: document.getElementById('hero-divider'),  delay: 400 },
        subtitle: { el: document.getElementById('hero-subtitle'), delay: 500 },
    };
    Object.values(els).forEach(({ el, delay }) => {
        if (!el) return;
        setTimeout(() => {
            el.classList.remove('opacity-0', 'translate-y-8', 'translate-y-4');
            el.classList.add('opacity-100', 'translate-y-0');
        }, delay);
    });

// Hero parallax (hanya untuk gambar, video tidak memakai parallax)
const heroBg = document.getElementById('hero-bg');

if (heroBg) {

    const isVideo = heroBg.tagName.toLowerCase() === 'video';

    // Jika background adalah video, tidak perlu efek parallax
    if (!isVideo) {

        heroBg.style.willChange = 'transform';

        let ticking = false;

        window.addEventListener('scroll', () => {

            if (!ticking) {

                window.requestAnimationFrame(() => {

                    const scrolled = window.scrollY;

                    if (scrolled < window.innerHeight) {

                        const isMobile = window.innerWidth < 768;
                        const speed = isMobile ? 0.15 : 0.3;

                        heroBg.style.transform =
                            `translateY(${scrolled * speed}px) scale(1.1)`;
                    }

                    ticking = false;

                });

                ticking = true;
            }

        }, { passive: true });

    }

}

// ================= ABOUT SECTION =================

const aboutSection = document.getElementById("about");

const aboutImage = document.getElementById("about-image");

const aboutCard = document.getElementById("about-card");

const aboutHeading = document.getElementById("about-heading");

const aboutText = document.getElementById("about-text");

const aboutLine = document.getElementById("about-line");

const aboutElements=[
aboutImage,
aboutHeading,
aboutLine,
aboutText
];

const aboutObserver = new IntersectionObserver((entries)=>{

entries.forEach(entry=>{

if(entry.isIntersecting){

entry.target.classList.remove(
'opacity-0',
'translate-y-10',
'-translate-x-12',
'-translate-x-10'
);

entry.target.classList.add(
'opacity-100',
'translate-y-0',
'translate-x-0'
);

}

});

},{
threshold:.25
});

aboutElements.forEach(el=>{
    if(el) aboutObserver.observe(el);
});

// ================= ABOUT PARALLAX =================

let aboutTicking = false;

window.addEventListener("scroll",()=>{

    if(aboutTicking) return;

    requestAnimationFrame(()=>{

        if(!aboutImage) return;

        const rect=aboutImage.getBoundingClientRect();

        if(rect.top<window.innerHeight && rect.bottom>0){

            const move=(window.innerHeight-rect.top)*0.04;

            aboutImage.style.transform=
            `translateY(${move}px) scale(1.03)`;

        }

        aboutTicking=false;

    });

    aboutTicking=true;

},{passive:true});

// ================= MOUSE PARALLAX =================

if(aboutSection && aboutImage){

    const isTouch =
        window.matchMedia("(pointer:coarse)").matches;

    if(!isTouch){

        aboutSection.addEventListener("mousemove",(e)=>{

            const rect=aboutSection.getBoundingClientRect();

            const x=(e.clientX-rect.left)/rect.width-.5;

            const y=(e.clientY-rect.top)/rect.height-.5;

            aboutImage.style.transform=
            `translate(${x*10}px,${y*10}px) scale(1.05)`;

        });

        aboutSection.addEventListener("mouseleave",()=>{

            aboutImage.style.transform="translate(0,0) scale(1.03)";

        });

    }

}

    // ================= GALLERY REVEAL =================
    // Judul & CTA gallery fade-up saat masuk viewport
    const galleryHeading = document.getElementById('gallery-heading');
    const galleryCta = document.getElementById('gallery-cta');
    const galleryItems = document.querySelectorAll('.gallery-item');

    const galleryObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                el.classList.remove('opacity-0', 'translate-y-10', 'translate-y-8', 'translate-y-6', 'scale-95');
                el.classList.add('opacity-100', 'translate-y-0', 'scale-100', 'is-visible');
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.12 });

    if (galleryHeading) galleryObserver.observe(galleryHeading);
    if (galleryCta) galleryObserver.observe(galleryCta);

    // Foto gallery muncul satu-satu (staggered) biar terasa hidup
    galleryItems.forEach((item, i) => {
        item.style.transitionDelay = `${(i % 8) * 80}ms`;
        galleryObserver.observe(item);
    });

    // ================= TESTIMONIAL HEADING REVEAL =================
    const testimonialHeading = document.getElementById('testimonial-heading');
    if (testimonialHeading) {
        const testimonialObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    el.classList.remove('opacity-0', 'translate-y-10');
                    el.classList.add('opacity-100', 'translate-y-0');
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.2 });
        testimonialObserver.observe(testimonialHeading);
    }

});

// ================= PROMO REVEAL & INTERACTION =================
    const promoCard = document.getElementById('promo-card');
    const promoImageWrap = document.getElementById('promo-image-wrap');
    const promoContent = document.getElementById('promo-content');
    const promoBgImg = document.getElementById('promo-bg-img');
    const promoCtaBtn = document.getElementById('promo-cta-btn');

    if (promoCard) {
        const promoObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Reveal card utama
                    promoCard.classList.remove('opacity-0', 'translate-y-10', 'scale-[0.97]');
                    promoCard.classList.add('opacity-100', 'translate-y-0', 'scale-100');

                    // Reveal gambar kecil (delay lebih cepat)
                    if (promoImageWrap) {
                        promoImageWrap.classList.remove('opacity-0', '-translate-x-6');
                        promoImageWrap.classList.add('opacity-100', 'translate-x-0');
                    }

                    // Reveal konten teks (delay lebih lama)
                    if (promoContent) {
                        promoContent.classList.remove('opacity-0', 'translate-y-8');
                        promoContent.classList.add('opacity-100', 'translate-y-0');
                    }

                    observer.unobserve(promoCard);
                }
            });
        }, { threshold: 0.2 });

        promoObserver.observe(promoCard);
    }

    // Parallax halus pada background image promo saat scroll
    if (promoBgImg) {
        let promoTicking = false;
        window.addEventListener('scroll', () => {
            if (promoTicking) return;
            requestAnimationFrame(() => {
                const rect = promoBgImg.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    const move = (window.innerHeight - rect.top) * 0.02;
                    promoBgImg.style.transform = `translateY(${move}px) scale(1.05)`;
                }
                promoTicking = false;
            });
            promoTicking = true;
        }, { passive: true });
    }

    // Ripple effect pada tombol CTA promo
    if (promoCtaBtn) {
        promoCtaBtn.addEventListener('click', function (e) {
            const rect = promoCtaBtn.getBoundingClientRect();
            const ripple = document.createElement('span');
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.classList.add('ripple');
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;

            promoCtaBtn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/makrupp123/Development/Laravel/restourant/resources/views/home.blade.php ENDPATH**/ ?>