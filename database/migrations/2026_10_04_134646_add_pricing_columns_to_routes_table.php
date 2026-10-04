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
            $table->unsignedInteger('price_camping_open')->nullable()->after('duration_hours');
            $table->unsignedInteger('price_tektok_open')->nullable()->after('price_camping_open');
            $table->unsignedInteger('price_camping_private')->nullable()->after('price_tektok_open');
            $table->unsignedInteger('price_tektok_private')->nullable()->after('price_camping_private');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table): void {
            $table->dropColumn([
                'price_camping_open',
                'price_tektok_open',
                'price_camping_private',
                'price_tektok_private',
            ]);
        });
    }
};
