<?php

namespace Tests\Feature;

use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\User;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BatchHikingTypeSeparationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);

        Http::fake([
            'https://api.fonnte.com/validate' => Http::response([
                'status' => true,
                'registered' => ['6281298765432'],
                'not_registered' => [],
            ], 200),
        ]);
    }

    public function test_when_only_camping_batch_is_open_tektok_booking_is_rejected_and_camping_succeeds(): void
    {
        $user = User::factory()->create();
        $mountain = Mountain::where('has_open_trip', true)->first();
        $route = $mountain->primaryRoute ?? $mountain->routes()->first();

        // Close any existing open batches for this mountain
        Expedition::where('mountain_id', $mountain->id)->where('type', 'open')->delete();

        // Create only a camping batch
        $campingBatch = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'title' => 'Open Trip Camping',
            'type' => 'open',
            'hiking_type' => 'camping',
            'status' => 'open',
            'price' => 500000,
            'quota_max' => 10,
            'quota_booked' => 0,
            'departure_date' => now()->addDays(14)->toDateString(),
            'return_date' => now()->addDays(15)->toDateString(),
        ]);

        // Check show view config
        $response = $this->get(route('ekspedisi.show', $mountain->slug));
        $response->assertOk();
        $config = $response->viewData('bookingConfig');
        $this->assertTrue($config['hasOpenCampingSchedule']);
        $this->assertFalse($config['hasOpenTektokSchedule']);
        $this->assertEquals('camping', $config['defaultHikingType']);
        $this->assertEquals($campingBatch->id, $config['openCampingExpeditionId']);
        $this->assertNull($config['openTektokExpeditionId']);

        // Attempting to book tektok using open trip targeting the camping batch must fail validation
        $invalidPayload = [
            'expedition_id' => $campingBatch->id,
            'route_id' => $route->id,
            'trip_type' => 'open',
            'hiking_type' => 'tektok',
            'customer_name' => 'Pendaki Tektok',
            'customer_email' => 'tektok@gmail.com',
            'customer_phone' => '081298765432',
            'customer_nik' => '3301011508950001',
            'pax_count' => 1,
            'participants' => [
                ['full_name' => 'Pendaki Tektok', 'nik' => '3301011508950001', 'is_leader' => true],
            ],
        ];

        $postInvalid = $this->actingAs($user)->postJson(route('bookings.store'), $invalidPayload);
        $postInvalid->assertStatus(422)
            ->assertJsonValidationErrors(['hiking_type']);

        // Booking with matching camping hiking_type succeeds
        $validPayload = $invalidPayload;
        $validPayload['hiking_type'] = 'camping';

        $postValid = $this->actingAs($user)->postJson(route('bookings.store'), $validPayload);
        $postValid->assertStatus(201)
            ->assertJson(['success' => true]);
    }

    public function test_when_only_tektok_batch_is_open_camping_booking_is_rejected_and_tektok_succeeds(): void
    {
        $user = User::factory()->create();
        $mountain = Mountain::where('has_open_trip', true)->first();
        $route = $mountain->primaryRoute ?? $mountain->routes()->first();

        // Close any existing open batches for this mountain
        Expedition::where('mountain_id', $mountain->id)->where('type', 'open')->delete();

        // Create only a tektok batch
        $tektokBatch = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'title' => 'Open Trip Tek-tok',
            'type' => 'open',
            'hiking_type' => 'tektok',
            'status' => 'open',
            'price' => 350000,
            'quota_max' => 10,
            'quota_booked' => 0,
            'departure_date' => now()->addDays(10)->toDateString(),
            'return_date' => now()->addDays(10)->toDateString(),
        ]);

        // Check show view config
        $response = $this->get(route('ekspedisi.show', $mountain->slug));
        $response->assertOk();
        $config = $response->viewData('bookingConfig');
        $this->assertFalse($config['hasOpenCampingSchedule']);
        $this->assertTrue($config['hasOpenTektokSchedule']);
        $this->assertEquals('tektok', $config['defaultHikingType']);
        $this->assertNull($config['openCampingExpeditionId']);
        $this->assertEquals($tektokBatch->id, $config['openTektokExpeditionId']);

        // Attempting to book camping using open trip targeting the tektok batch must fail validation
        $invalidPayload = [
            'expedition_id' => $tektokBatch->id,
            'route_id' => $route->id,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'customer_name' => 'Pendaki Camping',
            'customer_email' => 'camping@gmail.com',
            'customer_phone' => '081298765432',
            'customer_nik' => '3301011508950001',
            'pax_count' => 1,
            'participants' => [
                ['full_name' => 'Pendaki Camping', 'nik' => '3301011508950001', 'is_leader' => true],
            ],
        ];

        $postInvalid = $this->actingAs($user)->postJson(route('bookings.store'), $invalidPayload);
        $postInvalid->assertStatus(422)
            ->assertJsonValidationErrors(['hiking_type']);

        // Booking with matching tektok hiking_type succeeds
        $validPayload = $invalidPayload;
        $validPayload['hiking_type'] = 'tektok';

        $postValid = $this->actingAs($user)->postJson(route('bookings.store'), $validPayload);
        $postValid->assertStatus(201)
            ->assertJson(['success' => true]);
    }

    public function test_private_trip_allows_both_camping_and_tektok_regardless_of_open_batches(): void
    {
        $user = User::factory()->create();
        $mountain = Mountain::where('has_private_trip', true)->first();
        $route = $mountain->primaryRoute ?? $mountain->routes()->first();

        // Delete all open batches
        Expedition::where('mountain_id', $mountain->id)->where('type', 'open')->delete();

        $privateExpedition = Expedition::firstOrCreate(
            ['mountain_id' => $mountain->id, 'type' => 'private'],
            [
                'route_id' => $route->id,
                'title' => 'Private Trip '.$mountain->name,
                'hiking_type' => 'camping',
                'status' => 'open',
                'price' => $mountain->price_private ?? 1000000,
                'quota_max' => 50,
                'quota_booked' => 0,
            ]
        );

        // Private trip camping
        $payloadCamping = [
            'expedition_id' => $privateExpedition->id,
            'route_id' => $route->id,
            'trip_type' => 'private',
            'hiking_type' => 'camping',
            'departure_date' => now()->addDays(7)->toDateString(),
            'customer_name' => 'Private Camper',
            'customer_email' => 'private@gmail.com',
            'customer_phone' => '081298765432',
            'customer_nik' => '3301011508950001',
            'pax_count' => 2,
            'participants' => [
                ['full_name' => 'Private Camper', 'nik' => '3301011508950001', 'is_leader' => true],
                ['full_name' => 'Camper 2', 'nik' => '', 'is_leader' => false],
            ],
        ];

        $resCamping = $this->actingAs($user)->postJson(route('bookings.store'), $payloadCamping);
        $resCamping->assertStatus(201);

        // Private trip tektok
        $payloadTektok = $payloadCamping;
        $payloadTektok['hiking_type'] = 'tektok';

        $resTektok = $this->actingAs($user)->postJson(route('bookings.store'), $payloadTektok);
        $resTektok->assertStatus(201);
    }
}
