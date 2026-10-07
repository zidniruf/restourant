<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsWidget extends BaseWidget
{
    // Auto refresh setiap 10 detik
    protected ?string $pollingInterval = '10s';

    // Tampil di paling atas dashboard
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $pending   = Booking::where('status', 'pending')->count();
        $paidToday = Booking::where('payment_status', 'paid')
                        ->whereDate('paid_at', today())
                        ->count();
        $checkinToday = Booking::where('checkin_status', 'checked_in')
                        ->whereDate('checked_in_at', today())
                        ->count();
        $completedTotal = Booking::where('status', 'completed')->count();

        return [
            Stat::make('⏳ Menunggu Konfirmasi', $pending)
                ->description($pending > 0 ? '⚠️ Ada booking baru!' : 'Tidak ada booking pending')
                ->color($pending > 0 ? 'warning' : 'success')
                ->chart([1]),

            Stat::make('💳 Pembayaran Masuk Hari Ini', $paidToday)
                ->description('Booking sudah bayar DP hari ini')
                ->color('success'),

            Stat::make('🚪 Check-in Hari Ini', $checkinToday)
                ->description('Tamu yang sudah hadir')
                ->color('info'),

            Stat::make('✅ Total Selesai', $completedTotal)
                ->description('Semua transaksi completed')
                ->color('primary'),
        ];
    }
}