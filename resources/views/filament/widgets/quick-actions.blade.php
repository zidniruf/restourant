<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-3 gap-2">
            <a
                href="/admin/items/create"
                class="flex flex-col items-center gap-1.5 rounded-xl bg-white p-2.5 text-center ring-1 ring-gray-100 shadow-sm transition hover:ring-amber-200"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#FFF1E0;">
                    <x-filament::icon icon="heroicon-o-plus-circle" class="h-4 w-4" style="color:#D9770E;" />
                </span>
                <span class="text-[11px] font-semibold leading-tight text-gray-700">Tambah Menu</span>
            </a>

            <a
                href="/admin/settings/1/edit"
                class="flex flex-col items-center gap-1.5 rounded-xl bg-white p-2.5 text-center ring-1 ring-gray-100 shadow-sm transition hover:ring-emerald-200"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#EAF7EF;">
                    <x-filament::icon icon="heroicon-o-cog-6-tooth" class="h-4 w-4" style="color:#1FA35C;" />
                </span>
                <span class="text-[11px] font-semibold leading-tight text-gray-700">Edit Setting</span>
            </a>

            <a
                href="/admin/galleries/create"
                class="flex flex-col items-center gap-1.5 rounded-xl bg-white p-2.5 text-center ring-1 ring-gray-100 shadow-sm transition hover:ring-blue-200"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#EAF2FF;">
                    <x-filament::icon icon="heroicon-o-photo" class="h-4 w-4" style="color:#2F6FE4;" />
                </span>
                <span class="text-[11px] font-semibold leading-tight text-gray-700">Tambah Gallery</span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>