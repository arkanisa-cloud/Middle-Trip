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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->after('phone');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('customer_phone', 25)->nullable()->change();
            $table->string('customer_nik', 20)->nullable()->change();
        });

        Schema::table('booking_participants', function (Blueprint $table) {
            $table->string('full_name', 150)->nullable()->change();
            $table->string('nik', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nik');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('customer_phone', 25)->nullable(false)->change();
            $table->string('customer_nik', 20)->nullable(false)->change();
        });

        Schema::table('booking_participants', function (Blueprint $table) {
            $table->string('full_name', 150)->nullable(false)->change();
            $table->string('nik', 20)->nullable(false)->change();
        });
    }
};
