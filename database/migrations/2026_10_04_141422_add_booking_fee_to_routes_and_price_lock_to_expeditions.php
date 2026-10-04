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
        Schema::table('routes', function (Blueprint $table): void {
            $table->unsignedInteger('booking_fee_per_pax')->nullable()->after('price_tektok_private');
        });

        Schema::table('expeditions', function (Blueprint $table): void {
            $table->unsignedTinyInteger('price_lock_days_before_departure')->default(3)->nullable()->after('current_locked_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expeditions', function (Blueprint $table): void {
            $table->dropColumn('price_lock_days_before_departure');
        });

        Schema::table('routes', function (Blueprint $table): void {
            $table->dropColumn('booking_fee_per_pax');
        });
    }
};
