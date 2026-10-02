<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->date('departure_date')->nullable()->after('trip_type');
            $table->date('return_date')->nullable()->after('departure_date');
        });

        // Backfill existing bookings with expedition departure/return dates if available
        if (Schema::hasTable('bookings') && Schema::hasTable('expeditions')) {
            DB::statement('
                UPDATE bookings b
                INNER JOIN expeditions e ON b.expedition_id = e.id
                SET b.departure_date = e.departure_date,
                    b.return_date = e.return_date
                WHERE b.departure_date IS NULL
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropColumn(['departure_date', 'return_date']);
        });
    }
};
