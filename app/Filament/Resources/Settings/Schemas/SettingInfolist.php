<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('site_name')
                    ->placeholder('-'),
                TextEntry::make('logo')
                    ->placeholder('-'),
                TextEntry::make('hero_title')
                    ->placeholder('-'),
                TextEntry::make('hero_subtitle')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('hero_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('whatsapp')
                    ->placeholder('-'),
                TextEntry::make('instagram')
                    ->placeholder('-'),
                TextEntry::make('facebook')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('opening_hours')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
