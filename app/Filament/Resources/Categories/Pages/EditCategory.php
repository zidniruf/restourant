<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    // Setelah save → langsung ke halaman list
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Auto-generate slug & hindari duplikat saat edit
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $slug = Str::slug($data['name']);
        $original = $slug;
        $count = 1;

        while (\App\Models\Category::where('slug', $slug)
            ->where('id', '!=', $this->record->id)
            ->exists()) {
            $slug = $original . '-' . $count++;
        }

        $data['slug'] = $slug;
        return $data;
    }
}