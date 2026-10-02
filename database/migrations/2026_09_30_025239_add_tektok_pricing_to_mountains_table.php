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
            $table->unsignedInteger('price_tektok')->nullable()->after('price_private');
            $table->unsignedInteger('price_private_tektok')->nullable()->after('price_tektok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mountains', function (Blueprint $table): void {
            $table->dropColumn([
                'price_tektok',
                'price_private_tektok',
            ]);
        });
    }
};
