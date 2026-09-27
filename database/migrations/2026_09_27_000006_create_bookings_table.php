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
        Schema::create('bookings', function (Blueprint $table): void {
            $table->id();
            $table->string('booking_code', 32)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('expedition_id')->constrained('expeditions')->restrictOnDelete();
            $table->foreignId('route_id')->constrained('routes')->restrictOnDelete();
            $table->foreignId('meeting_point_id')->nullable()->constrained('meeting_points')->nullOnDelete();
            $table->enum('trip_type', ['open', 'private'])->default('open');
            $table->string('customer_name', 150);
            $table->string('customer_email', 150);
            $table->string('customer_phone', 25);
            $table->string('customer_nik', 20);
            $table->unsignedTinyInteger('pax_count');
            $table->unsignedInteger('booking_fee_per_pax');
            $table->unsignedInteger('total_booking_fee');
            $table->unsignedInteger('shuttle_fee_total')->default(0);
            $table->unsignedInteger('addons_fee_total')->default(0);
            $table->unsignedInteger('locked_price_per_pax')->nullable();
            $table->unsignedInteger('remaining_payment_total')->nullable();
            $table->unsignedInteger('grand_total');
            $table->enum('status', ['open', 'reserved', 'price_locked', 'paid', 'expired', 'cancelled'])->default('open');
            $table->dateTime('payment_deadline')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['expedition_id', 'status']);
            $table->index('customer_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
