<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\EditSetting;
use App\Filament\Resources\Settings\Pages\ListSettings;
use App\Models\Setting;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\RichEditor;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Settings Website';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Konfigurasi FileUpload yang konsisten untuk semua field gambar.
     */
private static function imageUpload(
    string $name,
    string $label,
    string $directory = 'settings'
): FileUpload {
    return FileUpload::make($name)
        ->label($label)
        ->image()
        ->imageEditor()
        ->downloadable()
        ->openable()
        ->previewable(true)
        ->disk('public')
        ->directory($directory)
        ->visibility('public')
        ->preserveFilenames()
        ->columnSpanFull();
}

private static function mediaUpload(
    string $name,
    string $label,
    string $directory = 'settings'
): FileUpload {
    return FileUpload::make($name)
        ->label($label)
        ->disk('public')
        ->directory($directory)
        ->visibility('public')
        ->acceptedFileTypes([
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
            'video/mp4',
            'video/webm',
            'video/ogg',
        ])
        ->downloadable()
        ->openable()
        ->columnSpanFull();
}
    /**
     * Default jadwal operasional 7 hari.
     */
    private static function defaultBusinessHours(): array
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return array_map(fn ($day) => [
            'day'       => $day,
            'open'      => '08:00',
            'close'     => '22:00',
            'is_closed' => false,
            'note'      => '',
        ], $days);
    }

    // -------------------------------------------------------------------------
    // Form
    // -------------------------------------------------------------------------

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

// ── Informasi Website ──────────────────────────────────────────
Section::make('Informasi Website')
    ->icon('heroicon-o-globe-alt')
    ->schema([
        // Toggle ini ditaruh di sini, di dalam schema Section yang sama
        Toggle::make('is_reservation_enabled')
            ->label('Aktifkan Reservasi Online')
            ->helperText('Jika dimatikan, form reservasi di website akan disembunyikan.')
            ->default(true)
            ->columnSpanFull(),

        TextInput::make('site_name')
            ->label('Nama Website')
            ->placeholder('Contoh: Rumah Makan Sari Rasa')
            ->columnSpanFull(),

        self::imageUpload('logo', 'Logo Website'),
    ]),

            // ── Hero Section ───────────────────────────────────────────────
            Section::make('Hero Section')
                ->icon('heroicon-o-photo')
                ->description('Pengaturan tampilan utama halaman website')
                ->schema([
                    TextInput::make('hero_title')
                        ->label('Judul Hero')
                        ->placeholder('Selamat Datang di...')
                        ->columnSpanFull(),

                    Textarea::make('hero_subtitle')
                        ->label('Subtitle Hero')
                        ->rows(2)
                        ->columnSpanFull(),

                    self::mediaUpload('hero_background', 'Background Hero'),

RichEditor::make('hero_description')
    ->label('Deskripsi About')
    ->placeholder('Ceritakan tentang restoran Anda...')
    ->toolbarButtons([
        'bold',
        'italic',
        'link',
        'bulletList',
        'orderedList',
        'blockquote',
        'h2',
        'undo',
        'redo',
    ])
    ->columnSpanFull(),

                    self::imageUpload('hero_featured_image', 'Gambar About', 'hero'),

                    Toggle::make('show_hero_food_image')
                        ->label('Tampilkan Gambar Menu di Hero')
                        ->default(true)
                        ->columnSpanFull(),
                ]),

            // ── Kontak & Sosial Media ──────────────────────────────────────
            Section::make('Kontak & Sosial Media')
                ->icon('heroicon-o-device-phone-mobile')
                ->schema([
                    TextInput::make('whatsapp')
                        ->label('Nomor WhatsApp')
                        ->placeholder('628xxxxxxxxxx')
                        ->tel(),

                    TextInput::make('instagram')
                        ->label('URL Instagram')
                        ->placeholder('https://Instagram.com/@username')
                        ->url(),

                    TextInput::make('tiktok')
                        ->label('URL TikTok')
                        ->placeholder('https://tiktok.com/@username')
                        ->url(),

                    TextInput::make('maps_link')
                        ->label('Link Google Maps')
                        ->placeholder('https://maps.app.goo.gl/...')
                        ->url(),

                    Textarea::make('google_maps_embed')
                        ->label('Embed Google Maps')
                        ->rows(4)
                        ->placeholder('<iframe src="..."></iframe>')
                        ->columnSpanFull(),
                ])
                ->columns(1), // 1 kolom di mobile, bisa override di desktop

            // ── Jam Operasional ────────────────────────────────────────────
            Section::make('Jam Operasional')
                ->icon('heroicon-o-clock')
                ->description('Atur jadwal buka & tutup setiap hari')
                ->schema([
                    Repeater::make('business_hours')
                        ->label('')
                        ->default(self::defaultBusinessHours())
                        ->afterStateHydrated(function ($state, callable $set) {
                            if (blank($state)) {
                                $set('business_hours', self::defaultBusinessHours());
                            }
                        })
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->schema([
                            TextInput::make('day')
                                ->label('Hari')
                                ->disabled()
                                ->dehydrated(true),

                            TimePicker::make('open')
                                ->label('Buka')
                                ->seconds(false)
                                ->native(false),

                            TimePicker::make('close')
                                ->label('Tutup')
                                ->seconds(false)
                                ->native(false),

                            Toggle::make('is_closed')
                                ->label('Libur')
                                ->live()
                                ->inline(false),

                            TextInput::make('note')
                                ->label('Keterangan')
                                ->placeholder('Libur Nasional')
                                ->visible(fn ($get) => $get('is_closed')),
                        ])
                        // Mobile: 2 kolom | Desktop: 5 kolom
                        ->columns([
                            'default' => 2,
                            'sm'      => 2,
                            'md'      => 3,
                            'lg'      => 5,
                        ]),
                ]),

        ]);
    }

    // -------------------------------------------------------------------------
    // Table
    // -------------------------------------------------------------------------

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('site_name')
                    ->label('Nama Website'),

                TextColumn::make('whatsapp')
                    ->label('WhatsApp'),
            ]);
    }

    // -------------------------------------------------------------------------
    // Permissions
    // -------------------------------------------------------------------------

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    public static function getPages(): array
    {
        return [
            'index' => ListSettings::route('/'),
            'edit'  => EditSetting::route('/{record}/edit'),
        ];
    }
}