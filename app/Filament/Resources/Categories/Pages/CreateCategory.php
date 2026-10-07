<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Override langsung proses create di Filament v5
    protected function handleRecordCreation(array $data): Model
    {
        // Generate slug unik sebelum insert
        $slug = Str::slug($data['name']);
        $original = $slug;
        $count = 1;

        while (\App\Models\Category::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        $data['slug'] = $slug;

        return parent::handleRecordCreation($data);
    }
}