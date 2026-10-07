<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-3 gap-2">
            @foreach ($this->getCards() as $card)
                @include('filament.widgets.partials.stat-card', $card)
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>