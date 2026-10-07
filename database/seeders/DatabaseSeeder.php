<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin login
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
            ]
        );

        // Default settings agar halaman home tidak error
        Setting::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Website Restourant',
                'hero_title' => 'Selamat Datang',
                'hero_subtitle' => 'Nikmati Hidangan Terbaik',
                'hero_description' => 'Restoran dengan cita rasa terbaik.',
                'hero_button_text' => 'Pesan Sekarang',
                'show_hero_food_image' => true,
                'is_reservation_enabled' => true,
            ]
        );

        $this->call([
            RestaurantSessionSeeder::class,
        ]);
    }
}