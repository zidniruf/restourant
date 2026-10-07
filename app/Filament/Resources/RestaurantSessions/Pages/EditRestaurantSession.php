<?php

namespace App\Filament\Resources\RestaurantSessions\Pages;

use App\Filament\Resources\RestaurantSessions\RestaurantSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRestaurantSession extends EditRecord
{
    protected static string $resource = RestaurantSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
