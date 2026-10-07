<?php

namespace App\Filament\Resources\Bookings;

use App\Filament\Resources\Bookings\Pages\CreateBooking;
use App\Filament\Resources\Bookings\Pages\EditBooking;
use App\Filament\Resources\Bookings\Pages\ListBookings;
use App\Models\Booking;
use App\Models\RestaurantSession;
use App\Models\Setting;
use App\Models\Item;
use Filament\Forms\Components\Repeater;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static ?string $navigationLabel = 'Booking';
    protected static string|\UnitEnum|null $navigationGroup = 'Booking Management';
    protected static ?int $navigationSort = 3;

public static function form(Schema $schema): Schema
{
    return $schema->components([
        Section::make('Informasi Booking')
            ->schema([
                TextInput::make('name')->label('Nama')->required(),
                TextInput::make('phone')->label('Nomor WhatsApp')->required(),
                TextInput::make('guest_count')->label('Jumlah Orang')->numeric()->required(),
                DatePicker::make('booking_date')->label('Tanggal Booking')->required(),
                TextInput::make('event_time')->label('Jam Acara')->type('time'),
                TextInput::make('food_ready_time')->label('Jam Makanan Siap')->type('time'),
                Select::make('restaurant_session_id')
                    ->label('Sesi')
                    ->options(fn () => RestaurantSession::all()->mapWithKeys(
                        fn ($s) => [$s->id => "{$s->name} (" . substr($s->start_time, 0, 5) . ' - ' . substr($s->end_time, 0, 5) . ')']
                    )),
                Select::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'approved'  => 'Approved',
                        'rejected'  => 'Rejected',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])->required(),
                Textarea::make('note')->label('Catatan')->columnSpanFull(),
            ])->columns(2),

        Section::make('Menu Pesanan')
            ->schema([
                Repeater::make('items')
                    ->relationship('items')
                    ->schema([
                        Select::make('item_id')
                            ->label('Menu')
                            ->options(fn () => Item::where('is_active', true)->pluck('title', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $item = Item::find($state);
                                $set('price', $item->price ?? 0);
                            }),

                        TextInput::make('qty')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),

                        TextInput::make('price')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(3)
                    ->addActionLabel('Tambah Menu')
                    ->defaultItems(0)
                    ->columnSpanFull(),
            ]),
    ]);
}
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

TextColumn::make('created_at')
    ->label('Dikirim')
    ->dateTime('d M Y, H:i')
    ->placeholder('-')
    ->sortable()
    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->copyMessage('Nomor disalin!'),

                TextColumn::make('booking_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar-days'),

                TextColumn::make('table.name')
                    ->label('Tempat')
                    ->placeholder('-')
                    ->icon('heroicon-o-map-pin'),

                TextColumn::make('event_time')
                    ->label('Jam Acara')
                    ->placeholder('-')
                    ->formatStateUsing(fn ($state) => $state ? substr($state, 0, 5) : '-'),

                TextColumn::make('food_ready_time')
                    ->label('Makan Siap')
                    ->placeholder('-')
                    ->formatStateUsing(fn ($state) => $state ? substr($state, 0, 5) : '-'),

                TextColumn::make('guest_count')
                    ->label('Tamu')
                    ->suffix(' orang'),

TextColumn::make('session.name')
                    ->label('Sesi')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->session) return '-';
                        $start = substr($record->session->start_time, 0, 5);
                        $end   = substr($record->session->end_time, 0, 5);
                        return "{$record->session->name} ({$start}-{$end})";
                    })
                    ->placeholder('-'),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->placeholder('Rp 0'),

                TextColumn::make('dp_amount')
                    ->label('DP')
                    ->money('IDR')
                    ->placeholder('-'),

                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'paid'   => 'success',
                        'unpaid' => 'warning',
                        'failed' => 'danger',
                        default  => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'paid'   => '✅ Lunas DP',
                        'unpaid' => '⏳ Belum Bayar',
                        'failed' => '❌ Gagal',
                        default  => $state,
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending'   => 'warning',
                        'approved'  => 'success',
                        'rejected'  => 'danger',
                        'confirmed' => 'info',
                        'paid'      => 'info',
                        'completed' => 'gray',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'   => '⏳ Pending',
                        'approved'  => '✅ Disetujui',
                        'rejected'  => '❌ Ditolak',
                        'confirmed' => '💜 Dikonfirmasi',
                        'paid'      => '💳 Paid',
                        'completed' => '🏁 Selesai',
                        'cancelled' => '🚫 Dibatalkan',
                        default     => $state,
                    }),
            ])

                ->filters([
                    SelectFilter::make('status')
                        ->label('Status')
                        ->options([
                            'pending'   => '⏳ Pending',
                            'approved'  => '✅ Disetujui',
                            'confirmed' => '💜 Dikonfirmasi',
                            'rejected'  => '❌ Ditolak',
                            'completed' => '🏁 Selesai',
                            'cancelled' => '🚫 Dibatalkan',
                        ]),

                    SelectFilter::make('payment_status')
                        ->label('Pembayaran')
                        ->options([
                            'unpaid' => '⏳ Belum Bayar',
                            'paid'   => '✅ Lunas DP',
                            'failed' => '❌ Gagal',
                        ]),

                SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options(function () {
                        $options = [];
                        for ($i = 0; $i < 12; $i++) {
                            $date = now()->subMonths($i);
                            $key  = $date->format('Y-m');
                            $options[$key] = $date->translatedFormat('F Y');
                        }
                        return $options;
                    })
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            [$year, $month] = explode('-', $data['value']);
                            $query->whereYear('booking_date', $year)
                                  ->whereMonth('booking_date', $month);
                        }
                    }),
            ])

            ->recordActions([
                self::approveAction(),
                self::sendReceiptAction(),
                self::rejectAction(),
                self::viewReceiptAction(),
                DeleteAction::make()->label('Hapus'),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus yang dipilih'),
                ]),
            ])

            ->headerActions([
                Action::make('cetak_laporan')
                    ->label('Cetak Laporan')
                    ->color('gray')
                    ->icon('heroicon-o-printer')
                    ->form([
                        Select::make('bulan')
                            ->label('Pilih Bulan')
                            ->options(function () {
                                $options = [];
                                for ($i = 0; $i < 12; $i++) {
                                    $date = now()->subMonths($i);
                                    $key  = $date->format('Y-m');
                                    $options[$key] = $date->translatedFormat('F Y');
                                }
                                return $options;
                            })
                            ->default(now()->format('Y-m'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return redirect()->route('admin.laporan.cetak', ['bulan' => $data['bulan']]);
                    }),
            ])

            ->defaultSort('booking_date', 'desc');
    }

    // ── ACTIONS ──

protected static function approveAction(): Action
{
    return Action::make('approve')
        ->label('Setujui')
        ->color('success')
        ->icon('heroicon-o-check-circle')
        ->visible(fn ($record) => $record->status === 'pending')
        ->requiresConfirmation()
        ->modalHeading('Setujui Booking')
        ->modalDescription('Booking akan disetujui dan QR Code akan dibuat.')
        ->modalSubmitActionLabel('Ya, Setujui')
        ->form([
            TextInput::make('dp_amount')
                ->label('DP (Down Payment) dari Customer')
                ->numeric()
                ->minValue(0)
                ->prefix('Rp')
                ->required()
                ->placeholder('Contoh: 50000')
                ->helperText(fn ($record) => 'Total booking: Rp ' . number_format($record->total_price ?? 0, 0, ',', '.') . '. Masukkan jumlah DP yang sudah/akan dibayar customer.'),
        ])
        ->action(function ($record, array $data) {
            // Generate unique_token jika belum ada
            if (empty($record->unique_token)) {
                $record->unique_token = \Illuminate\Support\Str::random(32);
            }

            // Generate QR Code
            $qrContent = url('/booking/verify/' . $record->unique_token);
            $filename  = 'qrcodes/booking-' . $record->id . '.svg';

            $svgContent = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($qrContent);

            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $svgContent);

            $record->update([
                'status'          => 'approved',
                'unique_token'    => $record->unique_token,
                'qr_code_path'    => $filename,
                'dp_amount'       => $data['dp_amount'],
                // DP dianggap sudah diterima saat admin menyetujui & menginput nominalnya
                'payment_status'  => 'paid',
                'paid_at'         => now(),
            ]);

            Notification::make()
                ->title('Booking disetujui, DP Rp ' . number_format($data['dp_amount'], 0, ',', '.') . ' diterima, QR dibuat!')
                ->success()
                ->send();
        });
}

protected static function sendReceiptAction(): Action
{
    return Action::make('kirim_struk')
        ->label('Kirim Struk WA')
        ->color('info')
        ->icon('heroicon-o-paper-airplane')
        ->visible(fn ($record) => in_array($record->status, ['approved', 'confirmed']))
        ->requiresConfirmation()
        ->modalHeading('Kirim Struk ke WhatsApp')
        ->modalDescription('Struk akan dikirim ke WhatsApp customer.')
        ->action(function ($record) {
            Notification::make()
                ->title('Mengarahkan ke WhatsApp...')
                ->success()
                ->send();
        })
        ->successRedirectUrl(fn ($record) => route('admin.booking.kirimwa', $record->id));
}

protected static function rejectAction(): Action
{
    return Action::make('reject')
        ->label('Tolak')
        ->color('danger')
        ->icon('heroicon-o-x-circle')
        ->visible(fn ($record) => $record->status === 'pending')
        ->requiresConfirmation()
        ->modalHeading('Tolak Booking')
        ->form([
            Textarea::make('reject_reason')->label('Alasan Penolakan')->required()->rows(3),
        ])
        ->action(fn ($record, array $data) => $record->update([
            'status'        => 'rejected',
            'reject_reason' => $data['reject_reason'],
        ]));
}

protected static function viewReceiptAction(): Action
{
    return Action::make('lihat_struk')
        ->label('Lihat Struk')
        ->color('gray')
        ->icon('heroicon-o-document-text')
        ->visible(fn ($record) => in_array($record->status, ['approved', 'confirmed', 'completed']) && !empty($record->unique_token))
        ->url(fn ($record) => url('/booking/struk/' . $record->unique_token))
        ->openUrlInNewTab();
}

public static function getNavigationBadge(): ?string
{
    // ✅ hanya hitung pending
    $total = static::getModel()::where('status', 'pending')->count();
    return $total > 0 ? (string) $total : null;
}
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit'   => EditBooking::route('/{record}/edit'),
        ];
    }
}