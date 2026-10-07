<?php

namespace App\Filament\Resources\Promos\Pages;

use App\Filament\Resources\Promos\PromoResource;
use Filament\Resources\Pages\ListRecords;
use App\Models\Promo;

class ListPromos extends ListRecords
{
    protected static string $resource = PromoResource::class;

    public function mount(): void
    {
        // 1. Cari data promo pertama di database
        $promo = Promo::first();

        // 2. Jika belum ada data sama sekali, buat 1 data awal (dummy)
        if (! $promo) {
            $promo = Promo::create([
                'title' => 'Promo Spesial',
                'is_active' => false,
            ]);
        }

        // 3. Langsung alihkan (redirect) ke halaman Edit data tersebut
        redirect()->to(PromoResource::getUrl('edit', ['record' => $promo->id]));
    }
}