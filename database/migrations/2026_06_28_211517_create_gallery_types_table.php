<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->default('info');
            $table->timestamps();
        });

        // Seed tipe bawaan lama
        DB::table('gallery_types')->insert([
            ['name' => 'Food',     'color' => 'warning', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Interior', 'color' => 'info',    'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Event',    'color' => 'success', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Team',     'color' => 'gray',    'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_types');
    }
};