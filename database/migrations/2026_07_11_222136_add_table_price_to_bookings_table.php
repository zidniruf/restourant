<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('table_price', 12, 2)->default(0)->after('total_price');
            $table->unsignedInteger('table_duration_hours')->nullable()->after('table_price');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['table_price', 'table_duration_hours']);
        });
    }
};