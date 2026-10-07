<?php
namespace App\Filament\Resources\RestaurantSessions\Pages;
use App\Filament\Resources\RestaurantSessions\RestaurantSessionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurantSession extends CreateRecord
{
    protected static string $resource = RestaurantSessionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
