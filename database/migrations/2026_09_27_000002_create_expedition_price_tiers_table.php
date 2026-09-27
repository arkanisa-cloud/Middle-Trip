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
        Schema::create('expedition_price_tiers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mountain_id')->constrained('mountains')->cascadeOnDelete();
            $table->unsignedInteger('min_pax');
            $table->unsignedInteger('max_pax');
            $table->unsignedInteger('price_per_pax');
            $table->timestamps();

            $table->index(['mountain_id', 'min_pax', 'max_pax']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedition_price_tiers');
    }
};
