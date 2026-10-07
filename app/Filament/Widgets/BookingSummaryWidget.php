<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class BookingSummaryWidget extends Widget
{
    protected string $view = 'filament.widgets.booking-summary';

    protected static ?int $sort = 0; // tampil paling atas

    protected int|string|array $columnSpan = 'full';

    public string $filter = 'today';

    public array $filterOptions = [
        'today' => 'Hari Ini',
        '7'     => '7 Hari',
        '30'    => '30 Hari',
    ];

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    protected function getStartDate(): Carbon
    {
        return match ($this->filter) {
            '7'  => Carbon::today()->subDays(6),
            '30' => Carbon::today()->subDays(29),
            default => Carbon::today(),
        };
    }

    // Periode sebelumnya dengan durasi yang sama, untuk hitung trend naik/turun
    protected function getPreviousRange(): array
    {
        return match ($this->filter) {
            '7'  => [Carbon::today()->subDays(13), Carbon::today()->subDays(7)],
            '30' => [Carbon::today()->subDays(59), Carbon::today()->subDays(30)],
            default => [Carbon::yesterday(), Carbon::yesterday()],
        };
    }

    protected function trend(float $current, float $previous): ?float
    {
        if ($previous <= 0) {
            return null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    public function getCards(): array
    {
        $start = $this->getStartDate();
        [$prevStart, $prevEnd] = $this->getPreviousRange();

        $baseQuery = fn () => Booking::whereDate('created_at', '>=', $start);
        $prevQuery = fn () => Booking::whereDate('created_at', '>=', $prevStart)->whereDate('created_at', '<=', $prevEnd);

        $totalBooking = $baseQuery()->count();
        $totalGuest   = $baseQuery()->sum('guest_count');
        $totalNilai   = $baseQuery()->sum('total_price');

        $approvedQuery = fn () => $baseQuery()->whereIn('status', ['approved', 'confirmed', 'completed']);
        $approvedCount = $approvedQuery()->count();
        $approvedGuest = $approvedQuery()->sum('guest_count');

        // Total DP yang benar-benar diterima (payment_status = paid),
        // difilter berdasarkan tanggal DP dibayar (paid_at)
        $dpQuery = fn () => Booking::where('payment_status', 'paid')->whereDate('paid_at', '>=', $start);
        $totalDp = $dpQuery()->sum('dp_amount');
        $dpCount = $dpQuery()->count();

        $prevBooking  = $prevQuery()->count();
        $prevNilai    = $prevQuery()->sum('total_price');
        $prevApproved = $prevQuery()->whereIn('status', ['approved', 'confirmed', 'completed'])->count();
        $prevDp       = Booking::where('payment_status', 'paid')
            ->whereDate('paid_at', '>=', $prevStart)
            ->whereDate('paid_at', '<=', $prevEnd)
            ->sum('dp_amount');

        return [
            [
                'label'       => 'Booking Masuk',
                'value'       => number_format($totalBooking, 0, ',', '.'),
                'description' => $totalGuest . ' orang',
                'icon'        => 'heroicon-o-clipboard-document-list',
                'bg'          => '#EAF2FF',
                'color'       => '#2F6FE4',
                'trend'       => $this->trend($totalBooking, $prevBooking),
            ],
            [
                'label'       => 'Total Nilai Booking',
                'value'       => 'Rp ' . number_format($totalNilai, 0, ',', '.'),
                'description' => 'Semua status',
                'icon'        => 'heroicon-o-banknotes',
                'bg'          => '#FFF6E5',
                'color'       => '#D98E04',
                'trend'       => $this->trend($totalNilai, $prevNilai),
            ],
            [
                'label'       => 'Booking Disetujui',
                'value'       => number_format($approvedCount, 0, ',', '.'),
                'description' => $approvedGuest . ' orang',
                'icon'        => 'heroicon-o-check-circle',
                'bg'          => '#EAF7EF',
                'color'       => '#1FA35C',
                'trend'       => $this->trend($approvedCount, $prevApproved),
            ],
            [
                'label'       => 'Total DP Diterima',
                'value'       => 'Rp ' . number_format($totalDp, 0, ',', '.'),
                'description' => $dpCount . ' pembayaran DP',
                'icon'        => 'heroicon-o-currency-dollar',
                'bg'          => '#F1EEFF',
                'color'       => '#7C5CFC',
                'trend'       => $this->trend($totalDp, $prevDp),
            ],
        ];
    }
}