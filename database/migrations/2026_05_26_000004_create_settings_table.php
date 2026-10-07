<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // === SITE INFO ===
            $table->string('site_name')->nullable();
            $table->string('logo')->nullable();

            // === HERO SECTION ===
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable();
            $table->boolean('show_hero_food_image')->default(true);

            // === SOCIAL MEDIA ===
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();

            // === CONTACT & ADDRESS ===
            $table->text('address')->nullable();
            $table->text('opening_hours')->nullable();
            $table->text('maps_link')->nullable();
            $table->longText('google_maps_embed')->nullable();

            // === BUSINESS HOURS ===
            $table->json('business_hours')->nullable();
            $table->json('holiday_days')->nullable();
            $table->text('holiday_note')->nullable();

            // === RESERVATION ===
            $table->boolean('is_reservation_enabled')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
