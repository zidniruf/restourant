@php $trend ??= null; @endphp
<div class="rounded-xl bg-white p-3 ring-1 ring-gray-100 shadow-sm">
    <div class="flex items-start justify-between">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: {{ $bg }};">
            <x-filament::icon :icon="$icon" class="h-4 w-4" style="color: {{ $color }};" />
        </span>
        @if (! is_null($trend))
            <span class="inline-flex items-center gap-0.5 rounded-full px-1.5 py-0.5 text-[10px] font-semibold {{ $trend >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                {{ $trend >= 0 ? '↑' : '↓' }} {{ number_format(abs($trend), 1) }}%
            </span>
        @endif
    </div>

    <p class="mt-2 text-xl font-bold leading-tight tracking-tight text-gray-900">{{ $value }}</p>
    <p class="mt-0.5 text-xs font-medium text-gray-500">{{ $label }}</p>

    @if (! empty($description))
        <p class="mt-0.5 text-[11px] text-gray-400">{{ $description }}</p>
    @endif
</div>