<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('transaction_code', 50)->unique();
            $table->enum('payment_stage', ['booking_fee', 'settlement', 'full_payment']);
            $table->string('payment_method', 50);
            $table->unsignedInteger('amount');
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->json('payment_payload')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
