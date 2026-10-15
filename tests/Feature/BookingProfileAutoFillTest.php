<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\User;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingProfileAutoFillTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_booking_creation_auto_fills_customer_info_from_user_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Alvaro Pendaki Hebat',
            'email' => 'alvaro.pendaki@gmail.com',
            'phone' => '081298712345',
            'nik' => '3301011508950001',
        ]);

        $expedition = Expedition::where('type', 'open')->first();

        // User submits booking from detail modal where customer fields are not explicitly set or empty
        $payload = [
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'pax_count' => 1,
            'participants' => [
                ['full_name' => '', 'nik' => '', 'is_leader' => true],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);

        $response->assertCreated();

        $bookingCode = $response->json('booking_code');
        $this->assertNotNull($bookingCode);

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'user_id' => $user->id,
            'customer_name' => 'Alvaro Pendaki Hebat',
            'customer_email' => 'alvaro.pendaki@gmail.com',
            'customer_phone' => '081298712345',
            'customer_nik' => '3301011508950001',
        ]);

        $this->assertDatabaseHas('booking_participants', [
            'full_name' => 'Alvaro Pendaki Hebat',
            'nik' => '3301011508950001',
            'is_leader' => true,
        ]);
    }

    public function test_step1_payment_view_auto_fills_data_pemesan_from_user_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Siti Pendaki Sejati',
            'email' => 'siti.pendaki@example.com',
            'phone' => '085712345678',
            'nik' => '3302345678900002',
        ]);

        $expedition = Expedition::where('type', 'open')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-TEST-001',
            'user_id' => $user->id,
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'departure_date' => now()->addDays(5)->toDateString(),
            'return_date' => now()->addDays(7)->toDateString(),
            'customer_name' => '',
            'customer_email' => '',
            'customer_phone' => null,
            'customer_nik' => null,
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
            'payment_deadline' => now()->addHours(24),
        ]);

        $response = $this->actingAs($user)->get(route('checkout.step1', $booking->booking_code));

        $response->assertOk();
        $response->assertSee('value="Siti Pendaki Sejati"', false);
        $response->assertSee('value="siti.pendaki@example.com"', false);
        $response->assertSee('085712345678', false);
        $response->assertSee('3302345678900002', false);
    }

    public function test_private_payment_view_auto_fills_data_pemesan_from_user_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Pendaki Soliter',
            'email' => 'budi.soliter@example.com',
            'phone' => '081398765432',
            'nik' => '3303456789010003',
        ]);

        $mountain = Mountain::first();
        $route = $mountain->routes->first();
        $booking = Booking::create([
            'booking_code' => 'MT-TEST-002',
            'user_id' => $user->id,
            'expedition_id' => null,
            'route_id' => $route->id,
            'trip_type' => 'private',
            'departure_date' => now()->addDays(10)->toDateString(),
            'return_date' => now()->addDays(12)->toDateString(),
            'customer_name' => '',
            'customer_email' => '',
            'customer_phone' => null,
            'customer_nik' => null,
            'pax_count' => 1,
            'booking_fee_per_pax' => 0,
            'total_booking_fee' => 0,
            'grand_total' => 1500000,
            'status' => 'open',
            'payment_deadline' => now()->addHours(24),
        ]);

        $response = $this->actingAs($user)->get(route('checkout.private', $booking->booking_code));

        $response->assertOk();
        $response->assertSee('value="Budi Pendaki Soliter"', false);
        $response->assertSee('value="budi.soliter@example.com"', false);
        $response->assertSee('081398765432', false);
        $response->assertSee('3303456789010003', false);
    }
}
