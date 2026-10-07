<?php
namespace App\Filament\Resources\Items\Pages;
use App\Filament\Resources\Items\ItemResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['title']) && empty($data['slug'])) {
            $slug = Str::slug($data['title']);
            $original = $slug;
            $count = 1;
            while (\App\Models\Item::where('slug', $slug)->exists()) {
                $slug = $original . '-' . $count++;
            }
            $data['slug'] = $slug;
        }
        return $data;
    }
}
