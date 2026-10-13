<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingExpirationFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Mountain $mountain;

    private Route $route;

    private Expedition $expedition;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->mountain = Mountain::create([
            'name' => 'Mt. Merbabu',
            'slug' => 'mt-merbabu',
            'elevation' => 3142,
            'province' => 'Jawa Tengah',
            'cover_image' => 'https://example.com/merbabu.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 150000,
            'has_open_trip' => true,
            'has_private_trip' => true,
        ]);

        $this->route = Route::create([
            'mountain_id' => $this->mountain->id,
            'name' => 'Via Selo',
            'slug' => 'selo',
            'grade' => 'Grade A',
            'is_primary' => true,
        ]);

        $this->expedition = Expedition::create([
            'mountain_id' => $this->mountain->id,
            'route_id' => $this->route->id,
            'type' => 'open',
            'hiking_type' => 'camping',
            'departure_date' => now()->addDays(10)->toDateString(),
            'return_date' => now()->addDays(12)->toDateString(),
            'quota_max' => 10,
            'quota_booked' => 2,
            'status' => 'open',
        ]);
    }

    public function test_booking_is_not_expired_when_freshly_created(): void
    {
        $booking = Booking::create([
            'booking_code' => 'MT-EXP-001',
            'user_id' => $this->user->id,
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Pendaki Tangguh',
            'customer_email' => 'pendaki@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
            'payment_deadline' => now()->addHours(24),
        ]);

        $this->assertFalse($booking->isExpired());
    }

    public function test_booking_is_expired_when_open_and_deadline_has_passed(): void
    {
        $booking = Booking::create([
            'booking_code' => 'MT-EXP-002',
            'user_id' => $this->user->id,
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Pendaki Santai',
            'customer_email' => 'santai@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
            'payment_deadline' => now()->subMinute(),
        ]);

        $this->assertTrue($booking->isExpired());
    }

    public function test_booking_is_expired_when_created_more_than_24_hours_ago(): void
    {
        $booking = Booking::create([
            'booking_code' => 'MT-EXP-003',
            'user_id' => $this->user->id,
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Pendaki Lama',
            'customer_email' => 'lama@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
            'created_at' => now()->subHours(25),
            'payment_deadline' => null,
        ]);

        $this->assertTrue($booking->isExpired());
    }

    public function test_command_expires_unpaid_bookings_and_syncs_quota(): void
    {
        $booking = Booking::create([
            'booking_code' => 'MT-EXP-004',
            'user_id' => $this->user->id,
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Pendaki Telat',
            'customer_email' => 'telat@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 300000,
            'grand_total' => 1000000,
            'status' => 'open',
            'payment_deadline' => now()->subHour(),
        ]);

        $this->artisan('bookings:expire-unpaid')
            ->expectsOutputToContain('Expired 1 unpaid bookings and synchronized quota.')
            ->assertSuccessful();

        $booking->refresh();
        $this->assertEquals('expired', $booking->status);
    }

    public function test_checkout_step1_redirects_with_error_if_booking_is_expired(): void
    {
        $booking = Booking::create([
            'booking_code' => 'MT-EXP-005',
            'user_id' => $this->user->id,
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Pendaki Kedaluwarsa',
            'customer_email' => 'kedaluwarsa@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
            'payment_deadline' => now()->subHours(2),
        ]);

        $response = $this->actingAs($this->user)->get(route('checkout.step1', $booking->booking_code));

        $response->assertRedirect(route('profile.edit', ['tab' => 'orders']));
        $response->assertSessionHas('error');

        $booking->refresh();
        $this->assertEquals('expired', $booking->status);
    }

    public function test_pay_dp_rejects_expired_booking(): void
    {
        $booking = Booking::create([
            'booking_code' => 'MT-EXP-006',
            'user_id' => $this->user->id,
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Pendaki Expire DP',
            'customer_email' => 'expiredp@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
            'payment_deadline' => now()->subHours(5),
        ]);

        $response = $this->actingAs($this->user)->postJson(route('checkout.pay_dp', $booking->booking_code), [
            'payment_method' => 'qris',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'expired',
        ]);

        $booking->refresh();
        $this->assertEquals('expired', $booking->status);
    }
}
