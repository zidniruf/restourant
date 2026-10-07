<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // === CUSTOMER ===
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();

            // === BOOKING ===
            $table->date('booking_date');
            $table->time('booking_time')->nullable();
            $table->integer('guest_count');
            $table->text('note')->nullable();
            $table->foreignId('restaurant_table_id')
                ->nullable()
                ->constrained('restaurant_tables')
                ->nullOnDelete();
            $table->foreignId('restaurant_session_id')
                ->nullable()
                ->constrained('restaurant_sessions')
                ->nullOnDelete();

            // === STATUS ===
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'paid',
                'completed',
                'confirmed',
                'expired',
                'cancelled',
            ])->default('pending');

            // === REJECTION ===
            $table->text('rejection_reason')->nullable();

            // === PAYMENT ===
            $table->string('payment_url')->nullable();
            $table->string('payment_link')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->decimal('total_price', 12, 2)->default(0);
            $table->decimal('dp_amount', 15, 2)->nullable();
            $table->timestamp('paid_at')->nullable();

            // === QR / TOKEN ===
            $table->string('qr_token')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('unique_token')->nullable()->unique();
            $table->timestamp('checked_in_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
