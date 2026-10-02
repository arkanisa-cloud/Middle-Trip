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
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['expedition_id']);
            }
            $table->unsignedBigInteger('expedition_id')->nullable()->change();
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('expedition_id')->references('id')->on('expeditions')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['expedition_id']);
            }
            $table->unsignedBigInteger('expedition_id')->nullable(false)->change();
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('expedition_id')->references('id')->on('expeditions')->restrictOnDelete();
            }
        });
    }
};
