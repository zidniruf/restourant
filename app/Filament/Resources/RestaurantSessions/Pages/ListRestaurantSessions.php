<?php

namespace App\Filament\Resources\RestaurantSessions\Pages;

use App\Filament\Resources\RestaurantSessions\RestaurantSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRestaurantSessions extends ListRecords
{
    protected static string $resource = RestaurantSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
