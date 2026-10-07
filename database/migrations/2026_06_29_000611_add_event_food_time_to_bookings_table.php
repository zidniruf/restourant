<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        // event_time sudah ada, skip
        // Cek dulu apakah food_ready_time sudah ada
        if (!Schema::hasColumn('bookings', 'food_ready_time')) {
            $table->time('food_ready_time')->nullable()->after('event_time');
        }
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        if (Schema::hasColumn('bookings', 'food_ready_time')) {
            $table->dropColumn('food_ready_time');
        }
    });
}
};
