<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantSession;

class RestaurantSessionSeeder extends Seeder
{
    public function run(): void
    {
        RestaurantSession::create(['name' => 'Pagi', 'start_time' => '08:00:00', 'end_time' => '12:00:00']);
        RestaurantSession::create(['name' => 'Siang', 'start_time' => '12:00:00', 'end_time' => '15:00:00']);
        RestaurantSession::create(['name' => 'Sore', 'start_time' => '15:00:00', 'end_time' => '18:00:00']);
        RestaurantSession::create(['name' => 'Malam', 'start_time' => '18:00:00', 'end_time' => '22:00:00']);
    }
}