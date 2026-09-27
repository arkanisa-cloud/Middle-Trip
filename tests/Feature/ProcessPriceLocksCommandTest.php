<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessPriceLocksCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_price_lock_command_locks_price_and_calculates_remaining_balance(): void
    {
        $mountain = Mountain::where('slug', 'mt-merbabu')->first();
        // Set departure in 2 days (<= 3 days threshold)
        $expedition = Expedition::where('mountain_id', $mountain->id)->first();
        $expedition->update([
            'departure_date' => now()->addDays(2)->toDateString(),
            'status' => 'open',
            'quota_booked' => 6,
        ]);

        $booking = Booking::create([
            'booking_code' => 'MT-LOCK-TEST',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'Arkan',
            'customer_email' => 'arkan@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 300000,
            'shuttle_fee_total' => 0,
            'addons_fee_total' => 0,
            'grand_total' => 1000000,
            'status' => 'reserved',
        ]);

        $this->artisan('expeditions:process-price-locks')->assertSuccessful();

        $this->assertEquals('price_locked', $expedition->fresh()->status);
        $this->assertEquals(550000, $expedition->fresh()->current_locked_price); // 6 pax = Tier 2 (550k)

        $freshBooking = $booking->fresh();
        $this->assertEquals('price_locked', $freshBooking->status);
        $this->assertEquals(550000, $freshBooking->locked_price_per_pax);
        // Remaining = (2 * 550k) - 300k DP = 800k
        $this->assertEquals(800000, $freshBooking->remaining_payment_total);
        $this->assertNotNull($freshBooking->payment_deadline);
    }

    public function test_expire_unpaid_bookings_command_expires_overdue_bookings(): void
    {
        $expedition = Expedition::where('type', 'open')->first();
        $expedition->update([
            'status' => 'price_locked',
            'quota_booked' => 6,
        ]);

        $overdueBooking = Booking::create([
            'booking_code' => 'MT-EXP-TEST',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'Overdue Customer',
            'customer_email' => 'overdue@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890002',
            'pax_count' => 2,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 300000,
            'locked_price_per_pax' => 550000,
            'remaining_payment_total' => 800000,
            'payment_deadline' => now()->subHour(), // Overdue
            'grand_total' => 1100000,
            'status' => 'price_locked',
        ]);

        $this->artisan('bookings:expire-unpaid')->assertSuccessful();

        $this->assertEquals('expired', $overdueBooking->fresh()->status);
        $this->assertEquals(4, $expedition->fresh()->quota_booked); // Quota released
    }
}
