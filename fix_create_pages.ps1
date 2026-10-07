# ============================================================
# Script: Fix semua Create Pages - redirect ke index setelah create
# Jalankan di: C:\laragon\www\website1\restourant
# ============================================================

$base = "app/Filament/Resources"

# --- BOOKING ---
Set-Content "$base/Bookings/Pages/CreateBooking.php" @'
<?php
namespace App\Filament\Resources\Bookings\Pages;
use App\Filament\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
'@

# --- GALLERY ---
Set-Content "$base/Galleries/Pages/CreateGallery.php" @'
<?php
namespace App\Filament\Resources\Galleries\Pages;
use App\Filament\Resources\Galleries\GalleryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
'@

# --- ITEM ---
Set-Content "$base/Items/Pages/CreateItem.php" @'
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
'@

# --- PROMO ---
Set-Content "$base/Promos/Pages/CreatePromo.php" @'
<?php
namespace App\Filament\Resources\Promos\Pages;
use App\Filament\Resources\Promos\PromoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePromo extends CreateRecord
{
    protected static string $resource = PromoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
'@

# --- RESTAURANT SESSION ---
Set-Content "$base/RestaurantSessions/Pages/CreateRestaurantSession.php" @'
<?php
namespace App\Filament\Resources\RestaurantSessions\Pages;
use App\Filament\Resources\RestaurantSessions\RestaurantSessionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurantSession extends CreateRecord
{
    protected static string $resource = RestaurantSessionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
'@

# --- RESTAURANT TABLE ---
Set-Content "$base/RestaurantTables/Pages/CreateRestaurantTable.php" @'
<?php
namespace App\Filament\Resources\RestaurantTables\Pages;
use App\Filament\Resources\RestaurantTables\RestaurantTableResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurantTable extends CreateRecord
{
    protected static string $resource = RestaurantTableResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
'@

# --- SETTING ---
Set-Content "$base/Settings/Pages/CreateSetting.php" @'
<?php
namespace App\Filament\Resources\Settings\Pages;
use App\Filament\Resources\Settings\SettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
'@

# --- TESTIMONIAL ---
Set-Content "$base/Testimonials/Pages/CreateTestimonial.php" @'
<?php
namespace App\Filament\Resources\Testimonials\Pages;
use App\Filament\Resources\Testimonials\TestimonialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
'@

# --- USER ---
Set-Content "$base/Users/Pages/CreateUser.php" @'
<?php
namespace App\Filament\Resources\Users\Pages;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
'@

Write-Host ""
Write-Host "SELESAI! Semua Create pages sudah diupdate." -ForegroundColor Green
Write-Host "Setelah Create -> redirect ke halaman list." -ForegroundColor Cyan
