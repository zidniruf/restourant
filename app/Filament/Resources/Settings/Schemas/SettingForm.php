<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\KeyValue;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('site_name'),
                TextInput::make('logo'),
                TextInput::make('hero_title'),
                Textarea::make('hero_subtitle')
                    ->columnSpanFull(),
                Textarea::make('hero_description')
                    ->columnSpanFull(),
                TextInput::make('whatsapp'),
                TextInput::make('instagram'),
                TextInput::make('facebook'),
                Textarea::make('address')
                    ->columnSpanFull(),
                Textarea::make('opening_hours')
                    ->columnSpanFull(),
                Section::make('Contact Information')
    ->schema([


        TextInput::make('tiktok'),

        TextInput::make('opening_hours'),

        Textarea::make('address')
            ->rows(3),

        Textarea::make('maps_embed')
            ->rows(4),

    ])
    ->columns(2),

    Section::make('Contact & Social Media')
    ->schema([

        TextInput::make('whatsapp')
            ->label('Nomor WhatsApp'),

        TextInput::make('instagram'),

        TextInput::make('facebook'),

        TextInput::make('address')
            ->columnSpanFull(),

        Textarea::make('google_maps_embed')
            ->rows(5)
            ->columnSpanFull(),

        KeyValue::make('business_hours')
            ->label('Jam Operasional')
            ->keyLabel('Hari')
            ->valueLabel('Jam'),

    ])
    ->columns(2),
            ]);
    }
}
