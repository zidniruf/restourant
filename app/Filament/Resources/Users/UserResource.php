<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = 'Admin';
    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 99;
    protected static ?string $modelLabel = 'Admin';
    protected static ?string $pluralModelLabel = 'Admin';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Admin')
                ->schema([

                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->minLength(8)
                        ->helperText('Kosongkan jika tidak ingin mengubah password.')
                        ->maxLength(255),

                    TextInput::make('password_confirmation')
                        ->label('Konfirmasi Password')
                        ->password()
                        ->same('password')
                        ->dehydrated(false)
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->maxLength(255),

                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),

            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function ($record) {
                        // Cegah hapus diri sendiri
                        if ($record->getKey() === auth()->id()) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Tidak bisa menghapus akun sendiri!')
                                ->send();
                            throw new \Exception('Cannot delete yourself');
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }
}