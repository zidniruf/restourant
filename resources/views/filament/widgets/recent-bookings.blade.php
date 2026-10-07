<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Booking Terbaru</x-slot>

        @php
            $statusColors = [
                'pending'   => ['bg' => '#FFF6E5', 'text' => '#B45309'],
                'approved'  => ['bg' => '#EAF2FF', 'text' => '#1D4ED8'],
                'confirmed' => ['bg' => '#F1EEFF', 'text' => '#6D28D9'],
                'completed' => ['bg' => '#EAF7EF', 'text' => '#047857'],
                'rejected'  => ['bg' => '#FDEDED', 'text' => '#B91C1C'],
                'cancelled' => ['bg' => '#F1F1F0', 'text' => '#6B7280'],
            ];
            $labelMap = [
                'pending'   => 'Pending',
                'approved'  => 'Disetujui',
                'confirmed' => 'Dikonfirmasi',
                'completed' => 'Selesai',
                'rejected'  => 'Ditolak',
                'cancelled' => 'Dibatalkan',
            ];
        @endphp

        {{-- Mobile: stacked cards ringkas --}}
        <div class="flex flex-col gap-1.5 sm:hidden">
            @forelse ($this->getBookings() as $booking)
                @php $sc = $statusColors[$booking->status] ?? ['bg' => '#F1F1F0', 'text' => '#6B7280']; @endphp
                <div class="flex items-center justify-between gap-2 rounded-xl bg-white p-2.5 ring-1 ring-gray-100 shadow-sm">
                    <div class="flex min-w-0 items-center gap-2">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-900 text-[11px] font-semibold text-white">
                            {{ strtoupper(substr($booking->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-xs font-semibold text-gray-900">{{ $booking->name }}</p>
                            <p class="truncate text-[11px] text-gray-400">{{ $booking->table->name ?? '-' }} · {{ $booking->booking_date->format('d M') }}</p>
                        </div>
                    </div>
                    <div class="flex shrink-0 flex-col items-end gap-1">
                        <span class="text-xs font-semibold text-gray-900">Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</span>
                        <span
                            class="rounded-full px-2 py-0.5 text-[10px] font-semibold"
                            style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }};"
                        >
                            {{ $labelMap[$booking->status] ?? ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="py-4 text-center text-sm text-gray-400">Belum ada booking.</p>
            @endforelse
        </div>

        {{-- Desktop: tabel --}}
        <div class="hidden overflow-x-auto rounded-xl ring-1 ring-gray-100 sm:block">
            <table class="w-full min-w-[640px] border-collapse text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/60 text-left">
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Customer</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Tempat</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Tanggal</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Total</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->getBookings() as $booking)
                        @php $sc = $statusColors[$booking->status] ?? ['bg' => '#F1F1F0', 'text' => '#6B7280']; @endphp
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-900 text-[11px] font-semibold text-white">
                                        {{ strtoupper(substr($booking->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $booking->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $booking->phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-gray-600">{{ $booking->table->name ?? '-' }}</td>
                            <td class="px-4 py-2.5 text-gray-600">{{ $booking->booking_date->format('d M Y') }}</td>
                            <td class="px-4 py-2.5 font-semibold text-gray-900">Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5">
                                <span
                                    class="rounded-full px-2.5 py-1 text-[11px] font-semibold"
                                    style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }};"
                                >
                                    {{ $labelMap[$booking->status] ?? ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>