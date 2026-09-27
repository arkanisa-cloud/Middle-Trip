<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_expedition_and_booking_tables_exist_with_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('mountains', [
            'booking_fee_per_pax',
            'price_lock_days_before_departure',
        ]));

        $this->assertTrue(Schema::hasTable('expedition_price_tiers'));
        $this->assertTrue(Schema::hasColumns('expedition_price_tiers', [
            'id', 'mountain_id', 'min_pax', 'max_pax', 'price_per_pax',
        ]));

        $this->assertTrue(Schema::hasTable('expeditions'));
        $this->assertTrue(Schema::hasColumns('expeditions', [
            'id', 'mountain_id', 'route_id', 'type', 'hiking_type',
            'departure_date', 'return_date', 'quota_max', 'quota_booked', 'current_locked_price', 'status',
        ]));

        $this->assertTrue(Schema::hasTable('meeting_points'));
        $this->assertTrue(Schema::hasColumns('meeting_points', [
            'id', 'mountain_id', 'name', 'additional_price_per_pax', 'is_default',
        ]));

        $this->assertTrue(Schema::hasTable('addons'));
        $this->assertTrue(Schema::hasColumns('addons', [
            'id', 'name', 'price', 'is_active',
        ]));

        $this->assertTrue(Schema::hasTable('bookings'));
        $this->assertTrue(Schema::hasColumns('bookings', [
            'id', 'booking_code', 'user_id', 'expedition_id', 'route_id', 'meeting_point_id',
            'customer_name', 'customer_email', 'customer_phone', 'customer_nik',
            'pax_count', 'booking_fee_per_pax', 'total_booking_fee', 'shuttle_fee_total',
            'addons_fee_total', 'locked_price_per_pax', 'remaining_payment_total', 'grand_total',
            'status', 'payment_deadline',
        ]));

        $this->assertTrue(Schema::hasTable('booking_participants'));
        $this->assertTrue(Schema::hasColumns('booking_participants', [
            'id', 'booking_id', 'full_name', 'nik', 'is_leader',
        ]));

        $this->assertTrue(Schema::hasTable('booking_addons'));
        $this->assertTrue(Schema::hasTable('payment_transactions'));
    }
}
