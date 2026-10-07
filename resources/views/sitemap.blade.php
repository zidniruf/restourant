@php
    // Sitemap dihitung langsung di view karena route-nya masih berupa closure
    // di web.php (Route::get('/sitemap.xml', function () { ... })).
    // Kalau nanti mau lebih rapi, ini bisa dipindah ke SitemapController.

    $staticPages = [
        ['route' => 'home',       'priority' => '1.0', 'changefreq' => 'weekly'],
        ['route' => 'menu.index', 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['route' => 'about',      'priority' => '0.6', 'changefreq' => 'monthly'],
        ['route' => 'gallery',    'priority' => '0.6', 'changefreq' => 'weekly'],
        ['route' => 'promo',      'priority' => '0.8', 'changefreq' => 'weekly'],
        ['route' => 'contact',    'priority' => '0.9', 'changefreq' => 'monthly'],
    ];

    $setting = \App\Models\Setting::first();
    $lastUpdated = optional($setting)->updated_at ?? now();

    // Ambil semua menu aktif untuk dijadikan URL detail (/menu/{slug})
    $menuItems = \App\Models\Item::where('is_active', true)->get();
@endphp
{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @foreach($staticPages as $page)
    <url>
        <loc>{{ route($page['route']) }}</loc>
        <lastmod>{{ $lastUpdated->format('Y-m-d') }}</lastmod>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
    @endforeach

    @foreach($menuItems as $item)
    <url>
        <loc>{{ route('menu.show', $item->slug) }}</loc>
        <lastmod>{{ ($item->updated_at ?? $lastUpdated)->format('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

</urlset>