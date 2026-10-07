<?php

namespace App\Filament\Resources\Galleries;

use App\Filament\Resources\Galleries\Pages\CreateGallery;
use App\Filament\Resources\Galleries\Pages\EditGallery;
use App\Filament\Resources\Galleries\Pages\ListGalleries;

use App\Models\Gallery;
use App\Models\GalleryType; // ← tambah ini

use BackedEnum;

use Filament\Resources\Resource;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Support\Icons\Heroicon;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

use Filament\Tables\Table;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;
    protected static ?string $navigationLabel = 'Gallery';
    protected static string|\UnitEnum|null $navigationGroup = 'Management';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Gallery Image')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('gallery')
                            ->required(),
                    ]),

                Section::make('Informasi')
                    ->schema([

                        TextInput::make('title')
                            ->label('Judul'),

                        // ✅ Select dinamis dari tabel gallery_types
                        Select::make('type')
                            ->label('Tipe')
                            ->options(fn () => GalleryType::pluck('name', 'name')->toArray())
                            ->searchable()
                            ->live()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Tipe Baru')
                                    ->required()
                                    ->placeholder('Contoh: Behind The Scene, Promo, etc')
                                    ->unique(GalleryType::class, 'name'),

                                Select::make('color')
                                    ->label('Warna Badge')
                                    ->options([
                                        'warning' => '🟡 Kuning (Warning)',
                                        'info'    => '🔵 Biru (Info)',
                                        'success' => '🟢 Hijau (Success)',
                                        'danger'  => '🔴 Merah (Danger)',
                                        'gray'    => '⚫ Abu (Gray)',
                                    ])
                                    ->default('info')
                                    ->required(),
                            ])
                            ->createOptionUsing(function (array $data): string {
                                GalleryType::create($data);
                                return $data['name'];
                            })
                            ->helperText('Pilih tipe yang ada, atau klik "+ Buat tipe baru" untuk menambah tipe kustom.'),

                        Toggle::make('is_active')
                            ->default(true),

                    ])
                    ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                    ->disk('public')
                    ->square(),

                TextColumn::make('title')
                    ->searchable(),

                // ✅ Warna badge diambil dari tabel gallery_types
                TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => GalleryType::where('name', $state)->value('color') ?? 'gray'),

                IconColumn::make('is_active')
                    ->boolean(),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGalleries::route('/'),
            'create' => CreateGallery::route('/create'),
            'edit'   => EditGallery::route('/{record}/edit'),
        ];
    }
}