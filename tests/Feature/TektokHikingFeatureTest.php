<?php

namespace Tests\Feature;

use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\User;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TektokHikingFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_mountain_model_calculates_tektok_pricing_correctly(): void
    {
        $mountain = Mountain::create([
            'name' => 'Gunung Test Tektok',
            'slug' => 'gunung-test-tektok',
            'elevation' => 3000,
            'province' => 'Jawa Tengah',
            'cover_image' => 'https://example.com/cover.jpg',
            'base_price' => 500000,
            'price_private' => 1000000,
            'price_tektok' => 350000,
            'price_private_tektok' => 700000,
            'booking_fee_per_pax' => 150000,
            'price_lock_days_before_departure' => 3,
        ]);

        $this->assertEquals(350000, $mountain->effective_price_tektok);
        $this->assertEquals(700000, $mountain->effective_price_private_tektok);

        // When not set, fallback 80% applies
        $fallbackMountain = Mountain::create([
            'name' => 'Gunung Fallback Tektok',
            'slug' => 'gunung-fallback-tektok',
            'elevation' => 2500,
            'province' => 'Jawa Tengah',
            'cover_image' => 'https://example.com/cover2.jpg',
            'base_price' => 500000,
            'price_private' => 1000000,
            'booking_fee_per_pax' => 150000,
            'price_lock_days_before_departure' => 3,
        ]);

        $this->assertEquals((int) round(500000 * 0.8), $fallbackMountain->effective_price_tektok);
        $this->assertEquals((int) round(1000000 * 0.85), $fallbackMountain->effective_price_private_tektok);
    }

    public function test_expedition_show_view_includes_camping_and_tektok_itineraries(): void
    {
        $mountain = Mountain::where('has_open_trip', true)->first();

        $response = $this->get(route('ekspedisi.show', $mountain->slug));
        $response->assertOk();
        $response->assertSee('Camping');
        $response->assertSee('Tek-tok');
        $response->assertSee('btn-tipe-camping');
        $response->assertSee('btn-tipe-tektok');
    }

    public function test_booking_creation_with_tektok_applies_tektok_pricing(): void
    {
        $user = User::factory()->create();
        $expedition = Expedition::where('type', 'open')->first();
        $mountain = $expedition->mountain;
        $mountain->update([
            'base_price' => 500000,
            'price_tektok' => 350000,
        ]);

        $payload = [
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'hiking_type' => 'tektok',
            'customer_name' => 'Pendaki Tektok',
            'customer_email' => 'tektok@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'participants' => [
                ['full_name' => 'Pendaki Tektok', 'nik' => '3301234567890001', 'is_leader' => true],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);
        $response->assertCreated();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'grand_total' => 350000,
        ]);
    }

    public function test_admin_can_save_tektok_pricing_and_itinerary(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $payload = [
            'name' => 'Gunung Prau Test',
            'elevation' => 2590,
            'province' => 'Jawa Tengah',
            'grade' => 'Grade A',
            'description' => 'Gunung dengan pemandangan sunrise terbaik',
            'base_price' => 450000,
            'price_private' => 600000,
            'price_tektok' => 300000,
            'price_private_tektok' => 450000,
            'booking_fee_per_pax' => 100000,
            'price_lock_days_before_departure' => 3,
            'routes' => [
                [
                    'name' => 'Via Patak Banteng',
                    'grade' => 'Grade A',
                    'distance_km' => 7.5,
                    'duration_hours' => '4 Jam',
                    'is_primary' => '1',
                    'water_note' => 'Pos 2',
                    'wind_note' => 'Puncak',
                    'signal_note' => 'Basecamp',
                    'checkpoints' => [
                        ['name' => 'Basecamp', 'elevation' => '2000'],
                        ['name' => 'Puncak', 'elevation' => '2590'],
                    ],
                    'itinerary_days' => [
                        [
                            'day' => 'Day 1',
                            'title' => 'Day 1: Basecamp ke Camp',
                            'desc' => 'Trekking santai',
                            'timeline' => '08:00 - Registrasi',
                        ],
                    ],
                    'itinerary_tektok_title' => 'Itinerary 1D Tek-tok Patak Banteng',
                    'itinerary_tektok_desc' => 'Pendakian cepat langsung turun 1 hari',
                    'itinerary_tektok_timeline' => "01:00 - Registrasi Basecamp\n05:30 - Sunrise Puncak\n08:00 - Turun ke Basecamp",
                ],
            ],
            'price_tiers' => [
                [
                    'min_pax' => 1,
                    'price_per_pax' => 450000,
                ],
            ],
        ];

        $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $payload);
        $response->assertRedirect(route('admin.mountains.index'));

        $this->assertDatabaseHas('mountains', [
            'name' => 'Gunung Prau Test',
            'price_tektok' => 300000,
            'price_private_tektok' => 450000,
        ]);

        $mountain = Mountain::where('name', 'Gunung Prau Test')->first();
        $route = $mountain->routes->first();
        $this->assertNotNull($route);
        $this->assertArrayHasKey('tektok', $route->itinerary);
        $this->assertEquals('Itinerary 1D Tek-tok Patak Banteng', $route->itinerary['tektok']['title']);
    }
}
