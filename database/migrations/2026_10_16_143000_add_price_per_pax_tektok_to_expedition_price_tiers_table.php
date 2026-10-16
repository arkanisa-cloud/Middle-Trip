<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expedition_price_tiers', function (Blueprint $table): void {
            if (! Schema::hasColumn('expedition_price_tiers', 'price_per_pax_tektok')) {
                $table->unsignedInteger('price_per_pax_tektok')->nullable()->after('price_per_pax');
            }
        });
    }

    public function down(): void
    {
        Schema::table('expedition_price_tiers', function (Blueprint $table): void {
            $table->dropColumn('price_per_pax_tektok');
        });
    }
};
