<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Expedition;
use App\Models\MeetingPoint;
use App\Models\Route;
use App\Models\User;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_guest_cannot_create_booking_and_must_login(): void
    {
        $expedition = Expedition::where('type', 'open')->first();
        $payload = [
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'customer_name' => 'Tamu',
            'customer_email' => 'tamu@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'participants' => [
                ['full_name' => 'Tamu', 'nik' => '3301234567890001', 'is_leader' => true],
            ],
        ];

        $response = $this->postJson(route('bookings.store'), $payload);
        $response->assertUnauthorized();
    }

    public function test_user_can_create_open_trip_booking_with_mandatory_nik(): void
    {
        $user = User::factory()->create();
        $expedition = Expedition::where('type', 'open')->first();
        $meetingPoint = MeetingPoint::where('mountain_id', $expedition->mountain_id)->first();
        $addon = Addon::first();

        $payload = [
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'meeting_point_id' => $meetingPoint->id,
            'customer_name' => 'Arkan Isa Alvaro',
            'customer_email' => 'arkan@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'participants' => [
                [
                    'full_name' => 'Arkan Isa Alvaro',
                    'nik' => '3301234567890001',
                    'is_leader' => true,
                ],
                [
                    'full_name' => 'Budi Santoso',
                    'nik' => '3301234567890002',
                    'is_leader' => false,
                ],
            ],
            'addons' => [
                ['id' => $addon->id, 'quantity' => 1],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);

        $response->assertCreated();
        $response->assertJsonStructure(['success', 'booking_code', 'redirect_url']);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'customer_name' => 'Arkan Isa Alvaro',
            'pax_count' => 2,
            'total_booking_fee' => 2 * $expedition->mountain->booking_fee_per_pax,
            'status' => 'open',
        ]);

        $this->assertDatabaseCount('booking_participants', 2);
    }

    public function test_booking_fails_if_pax_count_exceeds_available_quota(): void
    {
        $user = User::factory()->create();
        $expedition = Expedition::where('type', 'open')->first();
        $expedition->update(['quota_max' => 5, 'quota_booked' => 5]);

        $payload = [
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'customer_name' => 'Arkan',
            'customer_email' => 'arkan@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'participants' => [
                ['full_name' => 'Arkan', 'nik' => '3301234567890001', 'is_leader' => true],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);
        $response->assertStatus(422);
    }

    public function test_detail_page_syncs_route_ids_with_database_models(): void
    {
        $response = $this->get(route('ekspedisi.show', 'mt-merbabu'));
        $response->assertOk();

        $expedition = $response->original->getData()['expedition'];
        $this->assertNotEmpty($expedition['routes']);

        // Pastikan ID rute bukan mock string seperti 'selo' atau 'suwanting', melainkan ID database integer
        foreach ($expedition['routes'] as $route) {
            $this->assertIsInt($route['id']);
            $this->assertDatabaseHas('routes', [
                'id' => $route['id'],
                'name' => $route['name'],
            ]);
        }
    }

    public function test_user_can_create_booking_with_non_primary_route_such_as_suwanting(): void
    {
        $user = User::factory()->create();
        $expedition = Expedition::where('type', 'open')->first();
        $suwantingRoute = Route::where('mountain_id', $expedition->mountain_id)
            ->where('name', 'like', '%Suwanting%')
            ->firstOrFail();

        $payload = [
            'expedition_id' => $expedition->id,
            'route_id' => $suwantingRoute->id,
            'customer_name' => 'Pendaki Suwanting',
            'customer_email' => 'suwanting@example.com',
            'customer_phone' => '081234567890',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'participants' => [
                ['full_name' => 'Pendaki Suwanting', 'nik' => '3301234567890001', 'is_leader' => true],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);
        $response->assertCreated();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'route_id' => $suwantingRoute->id,
            'status' => 'open',
        ]);
    }
}
