<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('capacity');
            $table->enum('pricing_type', ['flat', 'per_hour'])->nullable()->after('is_paid');
            $table->decimal('price', 12, 2)->nullable()->default(0)->after('pricing_type');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'pricing_type', 'price']);
        });
    }
};