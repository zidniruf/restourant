<?php

namespace App\Filament\Resources\RestaurantSessions;

use App\Filament\Resources\RestaurantSessions\Pages\CreateRestaurantSession;
use App\Filament\Resources\RestaurantSessions\Pages\EditRestaurantSession;
use App\Filament\Resources\RestaurantSessions\Pages\ListRestaurantSessions;
use App\Models\RestaurantSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;

class RestaurantSessionResource extends Resource
{
    protected static ?string $model = RestaurantSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Sesi';

    protected static string|\UnitEnum|null $navigationGroup = 'Booking Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Sesi';

    protected static ?string $pluralModelLabel = 'Sesi';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Nama Sesi')
                    ->placeholder('Contoh: Pagi, Siang, Sore, Malam')
                    ->required()
                    ->maxLength(50),

                TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->seconds(false)
                    ->required(),

                TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->seconds(false)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Nama Sesi')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('start_time')
                    ->label('Jam Mulai')
                    ->formatStateUsing(fn ($state) => substr($state, 0, 5)),

                TextColumn::make('end_time')
                    ->label('Jam Selesai')
                    ->formatStateUsing(fn ($state) => substr($state, 0, 5)),

            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRestaurantSessions::route('/'),
            'create' => CreateRestaurantSession::route('/create'),
            'edit'   => EditRestaurantSession::route('/{record}/edit'),
        ];
    }
}