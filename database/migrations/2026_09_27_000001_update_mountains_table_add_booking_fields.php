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
        Schema::table('mountains', function (Blueprint $table): void {
            $table->unsignedInteger('booking_fee_per_pax')->default(150000)->after('price_private');
            $table->unsignedTinyInteger('price_lock_days_before_departure')->default(3)->after('booking_fee_per_pax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mountains', function (Blueprint $table): void {
            $table->dropColumn(['booking_fee_per_pax', 'price_lock_days_before_departure']);
        });
    }
};
