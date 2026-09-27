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
        Schema::create('booking_participants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('full_name', 150);
            $table->string('nik', 20);
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->boolean('is_leader')->default(false);
            $table->timestamps();

            $table->index(['booking_id', 'is_leader']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_participants');
    }
};
