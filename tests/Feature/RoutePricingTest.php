<?php

namespace Tests\Feature;

use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutePricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_model_provides_effective_prices_with_fallback(): void
    {
        $mountain = Mountain::create([
            'name' => 'Gunung Prau',
            'slug' => 'gunung-prau',
            'elevation' => 2565,
            'province' => 'Jawa Tengah',
            'cover_image' => 'mountain.jpg',
            'base_price' => 500000,
            'price_private' => 750000,
            'price_tektok' => 400000,
            'booking_fee_per_pax' => 100000,
        ]);

        // Route 1: punya harga custom sendiri
        $route1 = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Patak Banteng',
            'slug' => 'via-patak-banteng',
            'grade' => 'Grade A',
            'is_primary' => true,
            'price_camping_open' => 450000,
            'price_tektok_open' => 350000,
            'price_camping_private' => 700000,
            'price_tektok_private' => 600000,
        ]);

        // Route 2: tanpa harga custom (fallback ke gunung)
        $route2 = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Dieng',
            'slug' => 'via-dieng',
            'grade' => 'Grade A',
            'is_primary' => false,
        ]);

        $this->assertEquals(450000, $route1->effective_price_camping_open);
        $this->assertEquals(350000, $route1->effective_price_tektok_open);
        $this->assertEquals(700000, $route1->effective_price_camping_private);
        $this->assertEquals(600000, $route1->effective_price_tektok_private);

        $this->assertEquals(500000, $route2->effective_price_camping_open);
        $this->assertEquals(400000, $route2->effective_price_tektok_open);
        $this->assertEquals(750000, $route2->effective_price_camping_private);
    }

    public function test_mountain_starting_price_takes_lowest_route_price_option_a(): void
    {
        $mountain = Mountain::create([
            'name' => 'Gunung Slamet',
            'slug' => 'gunung-slamet',
            'elevation' => 3428,
            'province' => 'Jawa Tengah',
            'cover_image' => 'mountain.jpg',
            'base_price' => 600000,
            'booking_fee_per_pax' => 150000,
        ]);

        // Route A: 550.000
        Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Bambangan',
            'slug' => 'via-bambangan',
            'grade' => 'Grade B',
            'price_camping_open' => 550000,
        ]);

        // Route B: 480.000 (termurah)
        Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Dipajaya',
            'slug' => 'via-dipajaya',
            'grade' => 'Grade B',
            'price_camping_open' => 480000,
        ]);

        $mountain->load('routes');

        $this->assertEquals(480000, $mountain->effective_starting_price);
        $this->assertEquals('Rp 480.000', $mountain->formatted_price);
        $this->assertEquals('Rp 480k', $mountain->formatted_short_price);
    }

    public function test_booking_service_uses_route_specific_price_for_open_trip(): void
    {
        $user = User::factory()->create();

        $mountain = Mountain::create([
            'name' => 'Gunung Sindoro',
            'slug' => 'gunung-sindoro',
            'elevation' => 3153,
            'province' => 'Jawa Tengah',
            'cover_image' => 'mountain.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 100000,
        ]);

        $routeExpensive = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Kledung',
            'slug' => 'via-kledung',
            'grade' => 'Grade A',
            'price_camping_open' => 550000,
            'price_tektok_open' => 420000,
        ]);

        $routeAffordable = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Sigedang',
            'slug' => 'via-sigedang',
            'grade' => 'Grade A',
            'price_camping_open' => 460000,
            'price_tektok_open' => 360000,
        ]);

        $expedition = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $routeAffordable->id,
            'type' => 'open',
            'departure_date' => now()->addDays(10),
            'return_date' => now()->addDays(11),
            'quota_max' => 15,
            'quota_booked' => 0,
            'status' => 'open',
        ]);

        $service = app(BookingService::class);

        // Booking on affordable route
        $booking = $service->createBooking([
            'expedition_id' => $expedition->id,
            'route_id' => $routeAffordable->id,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'customer_name' => 'Pendaki Test',
            'customer_email' => 'pendaki@test.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'participants' => [
                ['full_name' => 'Pendaki Test', 'nik' => '3301234567890001', 'is_leader' => true],
                ['full_name' => 'Peserta Dua', 'nik' => '3301234567890002', 'is_leader' => false],
            ],
        ], $user->id);

        // 2 pax * 460.000 = 920.000
        $this->assertEquals(920000, $booking->grand_total);
        $this->assertEquals(200000, $booking->total_booking_fee);
    }

    public function test_booking_service_uses_route_specific_price_for_private_trip(): void
    {
        $user = User::factory()->create();

        $mountain = Mountain::create([
            'name' => 'Gunung Sumbing',
            'slug' => 'gunung-sumbing',
            'elevation' => 3371,
            'province' => 'Jawa Tengah',
            'cover_image' => 'mountain.jpg',
            'base_price' => 550000,
            'price_private' => 850000,
            'has_private_trip' => true,
        ]);

        $route = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Garung',
            'slug' => 'via-garung',
            'grade' => 'Grade B',
            'price_camping_private' => 950000,
            'price_tektok_private' => 750000,
        ]);

        $service = app(BookingService::class);

        // Booking Private Camping: 3 pax * 950.000 = 2.850.000
        $bookingCamping = $service->createBooking([
            'route_id' => $route->id,
            'trip_type' => 'private',
            'hiking_type' => 'camping',
            'customer_name' => 'Private Leader',
            'customer_email' => 'private@test.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 3,
            'departure_date' => now()->addDays(5)->toDateString(),
            'participants' => [
                ['full_name' => 'Peserta 1', 'nik' => '3301234567890001', 'is_leader' => true],
                ['full_name' => 'Peserta 2', 'nik' => '3301234567890002', 'is_leader' => false],
                ['full_name' => 'Peserta 3', 'nik' => '3301234567890003', 'is_leader' => false],
            ],
        ], $user->id);

        $this->assertEquals(950000, $bookingCamping->locked_price_per_pax);
        $this->assertEquals(2850000, $bookingCamping->grand_total);

        // Booking Private Tektok: 2 pax * 750.000 = 1.500.000
        $bookingTektok = $service->createBooking([
            'route_id' => $route->id,
            'trip_type' => 'private',
            'hiking_type' => 'tektok',
            'customer_name' => 'Tektok Leader',
            'customer_email' => 'tektok@test.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'departure_date' => now()->addDays(6)->toDateString(),
            'participants' => [
                ['full_name' => 'Peserta A', 'nik' => '3301234567890001', 'is_leader' => true],
                ['full_name' => 'Peserta B', 'nik' => '3301234567890002', 'is_leader' => false],
            ],
        ], $user->id);

        $this->assertEquals(750000, $bookingTektok->locked_price_per_pax);
        $this->assertEquals(1500000, $bookingTektok->grand_total);
    }

    public function test_admin_can_store_mountain_routes_with_pricing(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $payload = [
            'name' => 'Gunung Merbabu Testing',
            'elevation' => 3145,
            'province' => 'Jawa Tengah',
            'grade' => 'Grade A',
            'cover_image' => 'mountains/merbabu.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 150000,
            'price_lock_days_before_departure' => 3,
            'has_open_trip' => 1,
            'has_private_trip' => 1,
            'routes' => [
                [
                    'name' => 'Via Selo',
                    'grade' => 'Grade A',
                    'distance_km' => 10.5,
                    'duration_hours' => '6 Jam',
                    'is_primary' => 1,
                    'price_camping_open' => 520000,
                    'price_tektok_open' => 410000,
                    'price_camping_private' => 750000,
                    'price_tektok_private' => 630000,
                ],
                [
                    'name' => 'Via Suwanting',
                    'grade' => 'Grade B',
                    'distance_km' => 12.0,
                    'duration_hours' => '8 Jam',
                    'is_primary' => 0,
                    'price_camping_open' => 580000,
                    'price_tektok_open' => 470000,
                    'price_camping_private' => 820000,
                    'price_tektok_private' => 710000,
                ],
            ],
        ];

        $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $payload);
        $response->assertRedirect(route('admin.mountains.index'));

        $mountain = Mountain::where('name', 'Gunung Merbabu Testing')->firstOrFail();
        $this->assertCount(2, $mountain->routes);

        $selo = $mountain->routes()->where('name', 'Via Selo')->first();
        $this->assertEquals(520000, $selo->price_camping_open);
        $this->assertEquals(410000, $selo->price_tektok_open);
        $this->assertEquals(750000, $selo->price_camping_private);
        $this->assertEquals(630000, $selo->price_tektok_private);

        $suwanting = $mountain->routes()->where('name', 'Via Suwanting')->first();
        $this->assertEquals(580000, $suwanting->price_camping_open);
        $this->assertEquals(470000, $suwanting->price_tektok_open);

        // Opsi A check: effective starting price should be 520.000
        $this->assertEquals(520000, $mountain->effective_starting_price);
    }

    public function test_route_booking_fee_cannot_exceed_half_of_camping_price(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Attempting to set booking fee (300.000) > 50% of price_camping_open (500.000 -> max 250.000)
        $payload = [
            'name' => 'Gunung Lawu Invalid DP',
            'elevation' => 3265,
            'province' => 'Jawa Tengah',
            'grade' => 'Grade A',
            'cover_image' => 'mountains/lawu.jpg',
            'routes' => [
                [
                    'name' => 'Via Candi Cetho',
                    'grade' => 'Grade A',
                    'is_primary' => 1,
                    'price_camping_open' => 500000,
                    'booking_fee_per_pax' => 300000, // Invalid: exceeds 250.000
                ],
            ],
        ];

        $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $payload);
        $response->assertSessionHasErrors('routes.0.booking_fee_per_pax');
    }

    public function test_booking_service_uses_route_specific_booking_fee_for_open_trip(): void
    {
        $user = User::factory()->create();

        $mountain = Mountain::create([
            'name' => 'Gunung Andong',
            'slug' => 'gunung-andong',
            'elevation' => 1726,
            'province' => 'Jawa Tengah',
            'cover_image' => 'mountain.jpg',
            'base_price' => 300000,
            'booking_fee_per_pax' => 100000,
        ]);

        $route = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Sawit',
            'slug' => 'via-sawit',
            'grade' => 'Grade A',
            'price_camping_open' => 350000,
            'booking_fee_per_pax' => 120000, // Route specific DP
        ]);

        $expedition = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'type' => 'open',
            'departure_date' => now()->addDays(8),
            'return_date' => now()->addDays(9),
            'quota_max' => 10,
            'quota_booked' => 0,
            'status' => 'open',
            'price_lock_days_before_departure' => 4,
        ]);

        $service = app(BookingService::class);

        $booking = $service->createBooking([
            'expedition_id' => $expedition->id,
            'route_id' => $route->id,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'customer_name' => 'Pendaki Andong',
            'customer_email' => 'andong@test.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 3,
            'participants' => [
                ['full_name' => 'Pendaki 1', 'nik' => '3301234567890001', 'is_leader' => true],
                ['full_name' => 'Pendaki 2', 'nik' => '3301234567890002', 'is_leader' => false],
                ['full_name' => 'Pendaki 3', 'nik' => '3301234567890003', 'is_leader' => false],
            ],
        ], $user->id);

        // Route booking fee is 120.000 per pax. 3 pax => 360.000
        $this->assertEquals(120000, $booking->booking_fee_per_pax);
        $this->assertEquals(360000, $booking->total_booking_fee);
        // Price Lock on expedition should be 4 days
        $this->assertEquals(4, $expedition->effective_price_lock_days);
    }
}

