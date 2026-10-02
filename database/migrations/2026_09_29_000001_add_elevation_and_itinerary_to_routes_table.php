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
            $table->json('elevation_checkpoints')->nullable()->after('duration_hours');
            $table->json('itinerary')->nullable()->after('elevation_checkpoints');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table): void {
            $table->dropColumn([
                'elevation_checkpoints',
                'itinerary',
            ]);
        });
    }
};
