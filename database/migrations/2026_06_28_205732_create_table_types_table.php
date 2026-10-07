<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
// database/migrations/xxxx_create_table_types_table.php
public function up(): void
{
    Schema::create('table_types', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); // Nama tipe, misal: "Per Meja", "VIP", dll
        $table->string('color')->default('warning'); // Warna badge
        $table->timestamps();
    });

    // Seed tipe bawaan lama supaya data existing tidak hilang
    DB::table('table_types')->insert([
        ['name' => 'Per Meja',  'color' => 'warning', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Per Lantai','color' => 'info',    'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Area',      'color' => 'success', 'created_at' => now(), 'updated_at' => now()],
    ]);
}

public function down(): void
{
    Schema::dropIfExists('table_types');
}
};
