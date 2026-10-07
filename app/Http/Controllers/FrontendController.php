<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\Setting;
use App\Models\Promo;
use App\Models\Gallery;
use App\Models\RestaurantTable;
use App\Models\RestaurantSession;

class FrontendController extends Controller
{
    /**
     * Halaman Home (ringkas) — hero, about singkat, menu favorit, promo preview.
     * Ini pengganti method index() lama yang berisi semua section jadi satu.
     */
    public function home()
    {
        $setting    = Setting::first();
        $highlights = Item::where('is_recommended', true)->get();
        $promos     = Promo::where('is_active', true)->latest()->limit(3)->get();
        $testimonis = Testimonial::latest()->limit(6)->get();
        $galleries  = Gallery::latest()->limit(8)->get();

        return view('home', compact(
            'setting',
            'highlights',
            'promos',
            'testimonis',
            'galleries'
        ));
    }

    /**
     * Halaman Menu — daftar lengkap menu dengan filter kategori.
     */
    public function menu()
    {
        $setting    = Setting::first();
        $categories = Category::all();
        $katalog    = Item::with('category')->get();
        $menus      = Item::where('is_active', true)->get();

        return view('menu.index', compact(
            'setting',
            'categories',
            'katalog',
            'menus'
        ));
    }

    /**
     * Halaman Tentang Kami — datanya masih dari tabel Setting.
     */
    public function about()
    {
        $setting = Setting::first();

        return view('about.index', compact('setting'));
    }

    /**
     * Halaman Gallery — pakai pagination supaya ringan & ramah SEO.
     */
    public function gallery()
    {
        $setting   = Setting::first();
        $galleries = Gallery::latest()->paginate(12);

        return view('gallery.index', compact('setting', 'galleries'));
    }

    /**
     * Halaman Promo — daftar promo aktif.
     */
    public function promo()
    {
        $setting = Setting::first();
        $promos  = Promo::where('is_active', true)->latest()->get();

        return view('promo.index', compact('setting', 'promos'));
    }

    /**
     * Halaman Contact & Reservasi — form booking + info kontak.
     */
    public function contact()
    {
        $setting    = Setting::first();
        $tables     = RestaurantTable::where('is_active', true)->get();
        $sessions   = RestaurantSession::all();
        $categories = Category::all();
        $menus      = Item::where('is_active', true)->get();

        return view('contact.index', compact(
            'setting',
            'tables',
            'sessions',
            'categories',
            'menus'
        ));
    }

    /**
 * Halaman Detail Menu — untuk SEO, tiap item punya URL sendiri.
 */
public function menuShow($slug)
{
    $setting = Setting::first();

    $item = Item::with('category')
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    // Rekomendasi acak: prioritaskan kategori sama, exclude item ini sendiri
    $recommendations = Item::with('category')
        ->where('is_active', true)
        ->where('id', '!=', $item->id)
        ->inRandomOrder()
        ->limit(4)
        ->get();

    return view('menu.show', compact('setting', 'item', 'recommendations'));
}
}