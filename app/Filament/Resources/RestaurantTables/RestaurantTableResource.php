<?php

namespace App\Filament\Resources\RestaurantTables;

use App\Filament\Resources\RestaurantTables\Pages\CreateRestaurantTable;
use App\Filament\Resources\RestaurantTables\Pages\EditRestaurantTable;
use App\Filament\Resources\RestaurantTables\Pages\ListRestaurantTables;
use App\Models\RestaurantTable;
use App\Models\TableType;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class RestaurantTableResource extends Resource
{
    protected static ?string $model = RestaurantTable::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;
    protected static ?string $navigationLabel = 'Meja / Tempat';
    protected static string|\UnitEnum|null $navigationGroup = 'Management';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Tempat';
    protected static ?string $pluralModelLabel = 'Meja / Tempat';

public static function form(Schema $schema): Schema
{
    return $schema->components([
        Section::make('Informasi Tempat')
            ->description('Bisa berupa meja individual, lantai, area, atau tipe kustom lainnya.')
            ->schema([

                // ✅ Select dinamis — bisa pilih yang ada atau buat tipe baru
                Select::make('type')
                    ->label('Tipe')
                    ->options(fn () => TableType::pluck('name', 'name')->toArray())
                    ->searchable()
                    ->required()
                    ->live()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nama Tipe Baru')
                            ->required()
                            ->placeholder('Contoh: VIP Room, Rooftop, Drive-Thru')
                            ->unique(TableType::class, 'name'),

                        Select::make('color')
                            ->label('Warna Badge')
                            ->options([
                                'warning' => '🟡 Kuning (Warning)',
                                'info'    => '🔵 Biru (Info)',
                                'success' => '🟢 Hijau (Success)',
                                'danger'  => '🔴 Merah (Danger)',
                                'gray'    => '⚫ Abu (Gray)',
                            ])
                            ->default('warning')
                            ->required(),
                    ])
                    ->createOptionUsing(function (array $data): string {
                        TableType::create($data);
                        return $data['name'];
                    })
                    ->helperText('Pilih tipe yang ada, atau klik "+ Buat tipe baru" untuk menambah tipe kustom.'),

                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->placeholder(fn ($get) => match($get('type')) {
                        'Per Lantai' => 'Contoh: Lantai 1, Lantai 2',
                        'Area'       => 'Contoh: Area Outdoor, VIP Room',
                        default      => 'Contoh: Meja A1, Meja B3',
                    }),

                TextInput::make('capacity')
                    ->label('Kapasitas (Jumlah Kursi)')
                    ->numeric()
                    ->minValue(1)
                    ->placeholder('Kosongkan jika tidak terbatas')
                    ->helperText('Isi jumlah kursi maksimal, atau kosongkan jika tidak terbatas.'),

                Textarea::make('description')
                    ->label('Deskripsi (Opsional)')
                    ->placeholder('Contoh: Meja dekat jendela, cocok untuk 4 orang')
                    ->rows(2),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText('Non-aktifkan jika tempat sedang tidak tersedia.'),

            ])->columns(2),

        Section::make('Biaya Sewa Tempat')
            ->description('Atur apakah tempat ini gratis atau berbayar.')
            ->schema([

                Toggle::make('is_paid')
                    ->label('Tempat Berbayar?')
                    ->live()
                    ->default(false)
                    ->helperText('Aktifkan jika tempat ini dikenakan biaya sewa.'),

                Select::make('pricing_type')
                    ->label('Tipe Harga')
                    ->options([
                        'flat'     => 'Sekali Masuk (Flat)',
                        'per_hour' => 'Per Jam',
                    ])
                    ->required(fn ($get) => $get('is_paid'))
                    ->visible(fn ($get) => $get('is_paid'))
                    ->native(false),

                TextInput::make('price')
                    ->label('Harga (Rp)')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->required(fn ($get) => $get('is_paid'))
                    ->visible(fn ($get) => $get('is_paid'))
                    ->placeholder('Contoh: 50000')
                    ->helperText(fn ($get) => $get('pricing_type') === 'per_hour'
                        ? 'Harga ini akan dikalikan jumlah jam yang dipilih customer.'
                        : 'Harga tetap sekali bayar, tidak peduli durasi.'),

            ])->columns(2),
    ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([

            TextColumn::make('type')
                ->label('Tipe')
                ->badge()
                ->color(fn ($state) => TableType::where('name', $state)->value('color') ?? 'gray'),

            TextColumn::make('name')
                ->label('Nama')
                ->searchable()
                ->sortable(),

            TextColumn::make('description')
                ->label('Deskripsi')
                ->limit(30)
                ->placeholder('-'),

            TextColumn::make('capacity')
                ->label('Kapasitas')
                ->formatStateUsing(fn ($state) => $state ? $state . ' orang' : 'Tidak terbatas')
                ->placeholder('Tidak terbatas'),

            TextColumn::make('price')
                ->label('Harga')
                ->formatStateUsing(function ($record) {
                    if (!$record->is_paid) {
                        return 'Gratis';
                    }
                    $formatted = 'Rp ' . number_format($record->price, 0, ',', '.');
                    return $record->pricing_type === 'per_hour'
                        ? $formatted . ' / jam'
                        : $formatted . ' (sekali masuk)';
                })
                ->badge()
                ->color(fn ($record) => $record->is_paid ? 'danger' : 'success'),

            IconColumn::make('is_active')
                ->label('Aktif')
                ->boolean(),

        ])
        ->recordActions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->defaultSort('type');
}

    public static function getPages(): array
    {
        return [
            'index'  => ListRestaurantTables::route('/'),
            'create' => CreateRestaurantTable::route('/create'),
            'edit'   => EditRestaurantTable::route('/{record}/edit'),
        ];
    }
}