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
        Schema::create('meeting_points', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mountain_id')->constrained('mountains')->cascadeOnDelete();
            $table->string('name');
            $table->enum('location_type', ['basecamp', 'station', 'airport', 'terminal'])->default('basecamp');
            $table->unsignedInteger('additional_price_per_pax')->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['mountain_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_points');
    }
};
