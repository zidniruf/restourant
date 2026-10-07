<?php

namespace App\Filament\Resources\Items\Tables;

use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

use Filament\Tables\Filters\SelectFilter;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // GAMBAR MENU
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->square(),

                // NAMA MENU
                TextColumn::make('title')
                    ->label('Nama Menu')
                    ->searchable()
                    ->sortable(),

                // KATEGORI
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),

                // HARGA
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                // REKOMENDASI
                IconColumn::make('is_recommended')
                    ->label('Rekomendasi')
                    ->boolean(),

                // STATUS AKTIF
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                // TANGGAL
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y'),

            ])

            ->filters([

                // FILTER KATEGORI
                SelectFilter::make('category')
                    ->relationship('category', 'name'),

            ]);
    }
}