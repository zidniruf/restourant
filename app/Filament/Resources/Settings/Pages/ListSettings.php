<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    public function mount(): void
    {
        $setting = Setting::first();

        if (! $setting) {
            $setting = Setting::create([
                'site_name' => 'Restaurant',
            ]);
        }

        redirect(
            SettingResource::getUrl('edit', [
                'record' => $setting,
            ])
        );
    }
}