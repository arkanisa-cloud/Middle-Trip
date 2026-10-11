<?php

namespace Tests\Feature;

use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Mountain $mountain;

    protected Route $route;

    protected Expedition $expedition;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'https://api.fonnte.com/validate' => Http::response([
                'status' => true,
                'registered' => ['6281298765432'],
                'not_registered' => [],
            ], 200),
        ]);

        $this->user = User::factory()->create();

        $this->mountain = Mountain::create([
            'name' => 'Gunung Merbabu',
            'slug' => 'gunung-merbabu',
            'elevation' => 3145,
            'province' => 'Jawa Tengah',
            'cover_image' => 'merbabu.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 150000,
        ]);

        $this->route = Route::create([
            'mountain_id' => $this->mountain->id,
            'name' => 'Via Selo',
            'slug' => 'via-selo',
            'grade' => 'Grade A',
            'is_primary' => true,
        ]);

        $this->expedition = Expedition::create([
            'mountain_id' => $this->mountain->id,
            'route_id' => $this->route->id,
            'type' => 'open',
            'departure_date' => now()->addDays(10)->format('Y-m-d'),
            'return_date' => now()->addDays(12)->format('Y-m-d'),
            'quota_max' => 10,
            'quota_booked' => 0,
            'status' => 'open',
        ]);
    }

    public function test_booking_with_valid_data_passes(): void
    {
        $payload = [
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Alvaro Pendaki',
            'customer_email' => 'alvaro@gmail.com',
            'customer_phone' => '081298765432',
            'customer_nik' => '3301011508950001',
            'pax_count' => 1,
            'participants' => [
                [
                    'full_name' => 'Alvaro Pendaki',
                    'nik' => '3301011508950001',
                    'is_leader' => true,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)->postJson('/bookings', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_booking_with_disposable_email_is_rejected(): void
    {
        $payload = [
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Penyerang Cyber',
            'customer_email' => 'attacker@tempmail.com',
            'customer_phone' => '081298765432',
            'customer_nik' => '3301011508950001',
            'pax_count' => 1,
            'participants' => [
                [
                    'full_name' => 'Penyerang Cyber',
                    'nik' => '3301011508950001',
                    'is_leader' => true,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)->postJson('/bookings', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_email']);
    }

    public function test_booking_with_coba_email_is_rejected(): void
    {
        $payload = [
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Penyerang Cyber',
            'customer_email' => 'coba@mail.com',
            'customer_phone' => '081298765432',
            'customer_nik' => '3301011508950001',
            'pax_count' => 1,
            'participants' => [
                [
                    'full_name' => 'Penyerang Cyber',
                    'nik' => '3301011508950001',
                    'is_leader' => true,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)->postJson('/bookings', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_email']);
    }

    public function test_booking_with_dummy_nik_is_rejected(): void
    {
        $payload = [
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Penyerang Cyber',
            'customer_email' => 'penyerang@gmail.com',
            'customer_phone' => '081298765432',
            'customer_nik' => '1234567890123456', // Dummy
            'pax_count' => 1,
            'participants' => [
                [
                    'full_name' => 'Penyerang Cyber',
                    'nik' => '1234567890123456',
                    'is_leader' => true,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)->postJson('/bookings', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_nik', 'participants.0.nik']);
    }

    public function test_booking_with_dummy_phone_is_rejected(): void
    {
        $payload = [
            'expedition_id' => $this->expedition->id,
            'route_id' => $this->route->id,
            'trip_type' => 'open',
            'customer_name' => 'Penyerang Cyber',
            'customer_email' => 'penyerang@gmail.com',
            'customer_phone' => '081234567890', // Dummy sequence
            'customer_nik' => '3301011508950001',
            'pax_count' => 1,
            'participants' => [
                [
                    'full_name' => 'Penyerang Cyber',
                    'nik' => '3301011508950001',
                    'is_leader' => true,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)->postJson('/bookings', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_phone']);
    }
}
