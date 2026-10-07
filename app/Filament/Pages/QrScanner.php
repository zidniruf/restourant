<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class QrScanner extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationLabel = 'Scanner Check-in';

    protected static string|\UnitEnum|null $navigationGroup = 'Booking Management';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.qr-scanner';

    public function getTitle(): string
    {
        return 'Scanner QR Check-in';
    }
}