<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->time('event_time')->nullable()->after('guest_count');
        $table->time('food_ready_time')->nullable()->after('event_time');
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn(['event_time', 'food_ready_time']);
    });
}
};
