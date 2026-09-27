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
        Schema::create('mountains', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('elevation');
            $table->string('province');
            $table->string('cover_image');
            $table->text('description')->nullable();

            $table->boolean('has_open_trip')->default(true);
            $table->boolean('has_private_trip')->default(false);
            $table->unsignedInteger('base_price');
            $table->unsignedInteger('price_private')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->unsignedTinyInteger('featured_order')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mountains');
    }
};
