<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Customer')
                    ->schema([

                        TextInput::make('name')
                            ->label('Nama Customer')
                            ->required(),

                        TextInput::make('rating')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(5)
                            ->required(),

                    ])
                    ->columns(2),

                Section::make('Foto Customer')
                    ->schema([

                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('testimonials'),

                    ]),

                Section::make('Isi Testimoni')
                    ->schema([

                        Textarea::make('message')
                            ->rows(5)
                            ->required(),

                    ]),

                Section::make('Status')
                    ->schema([

                        Toggle::make('is_approved')
                            ->label('Tampilkan di Website')
                            ->default(true),

                    ]),

            ]);
    }
}