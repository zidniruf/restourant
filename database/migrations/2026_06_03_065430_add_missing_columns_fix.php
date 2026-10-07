<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'hero_background')) {
                $table->string('hero_background')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('settings', 'hero_featured_image')) {
                $table->string('hero_featured_image')->nullable()->after('hero_background');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['hero_background', 'hero_featured_image']);
        });
    }
};