<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;

use Illuminate\Support\Str;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | INFORMASI MENU
                |--------------------------------------------------------------------------
                */

                Section::make('Informasi Menu')
                    ->description('Informasi utama menu restoran')
                    ->schema([

                        TextInput::make('title')
                            ->label('Nama Menu')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Nasi Goreng Spesial')

                            // AUTO SLUG
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {

                                $set('slug', Str::slug($state));

                            }),

                        // SLUG HIDDEN
                        TextInput::make('slug')
                            ->hidden(),

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Kategori')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('25000'),

                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | MEDIA
                |--------------------------------------------------------------------------
                */

                Section::make('Media')
                    ->description('Upload gambar menu')
                    ->schema([

                        FileUpload::make('image')
                            ->label('Gambar Menu')
                            ->image()

                            // CRUD GAMBAR
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->previewable(true)

                            ->disk('public')
                            ->directory('katalog-gambar')

                            ->imagePreviewHeight('250')

                            ->loadingIndicatorPosition('left')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')

                            ->required(),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | DESKRIPSI
                |--------------------------------------------------------------------------
                */

                Section::make('Deskripsi')
                    ->schema([

                        Textarea::make('description')
                            ->label('Deskripsi Menu')
                            ->rows(5)
                            ->placeholder('Jelaskan menu secara singkat...')
                            ->columnSpanFull(),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | ATRIBUT TAMBAHAN
                |--------------------------------------------------------------------------
                */

                Section::make('Atribut Tambahan')
                    ->description('Tambahkan informasi tambahan menu')
                    ->schema([

                        KeyValue::make('properties')
                            ->label('Properti Tambahan')
                            ->keyLabel('Nama Atribut')
                            ->valueLabel('Nilai')
                            ->columnSpanFull(),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                Section::make('Status')
                    ->schema([

                        Toggle::make('is_recommended')
                            ->label('Tampilkan di Rekomendasi'),

                        Toggle::make('is_active')
                            ->label('Menu Aktif')
                            ->default(true),

                    ])
                    ->columns(2),

            ]);
    }
}