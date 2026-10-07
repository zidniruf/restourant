<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('settings', function (Blueprint $table) {
        $table->string('hero_featured_image')->nullable()->after('logo');
    });
}

public function down(): void
{
    Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn('hero_featured_image');
    });
}
};
