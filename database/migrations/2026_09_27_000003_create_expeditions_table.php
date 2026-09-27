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
        Schema::create('expeditions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mountain_id')->constrained('mountains')->cascadeOnDelete();
            $table->foreignId('route_id')->constrained('routes')->cascadeOnDelete();
            $table->enum('type', ['open', 'private'])->default('open');
            $table->enum('hiking_type', ['camping', 'tektok'])->default('camping');
            $table->date('departure_date');
            $table->date('return_date');
            $table->unsignedInteger('quota_max');
            $table->unsignedInteger('quota_booked')->default(0);
            $table->unsignedInteger('current_locked_price')->nullable();
            $table->enum('status', ['open', 'price_locked', 'completed', 'cancelled'])->default('open');
            $table->timestamps();

            $table->index(['mountain_id', 'departure_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};
