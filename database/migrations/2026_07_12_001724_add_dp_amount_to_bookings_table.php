<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('bookings', 'dp_amount')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->decimal('dp_amount', 12, 2)->nullable()->after('total_price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'dp_amount')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('dp_amount');
            });
        }
    }
};