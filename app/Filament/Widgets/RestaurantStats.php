<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use App\Models\Promo;
use App\Models\Testimonial;
use Filament\Widgets\Widget;

class RestaurantStats extends Widget
{
    protected string $view = 'filament.widgets.restaurant-stats';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function getCards(): array
    {
        return [
            [
                'label'       => 'Total Menu',
                'value'       => Item::count(),
                'description' => 'Semua menu restoran',
                'icon'        => 'heroicon-o-squares-2x2',
                'bg'          => '#FFF6E5',
                'color'       => '#D98E04',
            ],
[
    'label'       => 'Promo Aktif',
    'value'       => Promo::where('is_active', true)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->count(),
    'description' => 'Promo berjalan',
    'icon'        => 'heroicon-o-gift',
    'bg'          => '#EAF7EF',
    'color'       => '#1FA35C',
],
            [
                'label'       => 'Testimonials',
                'value'       => Testimonial::count(),
                'description' => 'Review pelanggan',
                'icon'        => 'heroicon-o-chat-bubble-left-right',
                'bg'          => '#EAF2FF',
                'color'       => '#2F6FE4',
            ],
        ];
    }
}