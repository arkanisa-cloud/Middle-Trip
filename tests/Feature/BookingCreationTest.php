<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Expedition;
use App\Models\MeetingPoint;
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

    public function test_user_can_create_open_trip_booking_with_mandatory_nik(): void
    {
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

        $response = $this->postJson(route('bookings.store'), $payload);

        $response->assertCreated();
        $response->assertJsonStructure(['success', 'booking_code', 'redirect_url']);

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Arkan Isa Alvaro',
            'pax_count' => 2,
            'total_booking_fee' => 2 * $expedition->mountain->booking_fee_per_pax,
            'status' => 'open',
        ]);

        $this->assertDatabaseCount('booking_participants', 2);
    }

    public function test_booking_fails_if_pax_count_exceeds_available_quota(): void
    {
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

        $response = $this->postJson(route('bookings.store'), $payload);
        $response->assertStatus(422);
    }
}
