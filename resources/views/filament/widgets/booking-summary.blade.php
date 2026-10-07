<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-sm font-semibold text-gray-900">Ringkasan Booking</h2>

            <div class="inline-flex w-full items-center gap-1 rounded-full bg-gray-100 p-1 sm:w-auto">
                @foreach ($filterOptions as $key => $label)
                    <button
                        type="button"
                        wire:click="setFilter('{{ $key }}')"
                        class="flex-1 rounded-full px-2.5 py-1 text-[11px] font-medium transition sm:flex-none
                            {{ $filter === $key ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="mt-3 grid grid-cols-2 gap-2 xl:grid-cols-4">
            @foreach ($this->getCards() as $card)
                @include('filament.widgets.partials.stat-card', $card)
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>