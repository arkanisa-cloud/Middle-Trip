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
            $table->text('overview')->nullable()->after('description');
            $table->json('elevation_checkpoints')->nullable()->after('overview');
            $table->json('facilities_included')->nullable()->after('elevation_checkpoints');
            $table->json('facilities_excluded')->nullable()->after('facilities_included');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mountains', function (Blueprint $table): void {
            $table->dropColumn([
                'overview',
                'elevation_checkpoints',
                'facilities_included',
                'facilities_excluded',
            ]);
        });
    }
};
