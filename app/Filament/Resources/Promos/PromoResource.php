<?php

namespace App\Filament\Resources\Promos;

use App\Filament\Resources\Promos\Pages\CreatePromo;
use App\Filament\Resources\Promos\Pages\EditPromo;
use App\Filament\Resources\Promos\Pages\ListPromos;

use App\Models\Promo;

use BackedEnum;

use Filament\Resources\Resource;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Support\Icons\Heroicon;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;

use Filament\Tables\Table;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static ?string $navigationLabel = 'Promo';

    protected static string|\UnitEnum|null $navigationGroup = 'Management';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Promo')
                    ->schema([

                        TextInput::make('title')
                            ->required(),

                        Textarea::make('description')
                            ->rows(5),

                    ]),

                Section::make('Banner Promo')
                    ->schema([

                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('promos'),

                    ]),

                Section::make('Periode Promo')
                    ->schema([

                        DatePicker::make('start_date'),

                        DatePicker::make('end_date'),

                        Toggle::make('is_active')
                            ->default(true),

                    ])
                    ->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                    ->disk('public'),

                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('start_date')
                    ->date(),

                TextColumn::make('end_date')
                    ->date(),

                IconColumn::make('is_active')
                    ->boolean(),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPromos::route('/'),
            'edit' => EditPromo::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}