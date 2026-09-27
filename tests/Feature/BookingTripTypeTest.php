<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Expedition;
use App\Models\MeetingPoint;
use App\Models\Mountain;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTripTypeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_open_trip_booking_requires_dp_and_redirects_to_step1(): void
    {
        $mountain = Mountain::where('slug', 'mt-merbabu')->first();
        $openExpedition = Expedition::where('mountain_id', $mountain->id)->where('type', 'open')->first();
        $meetingPoint = MeetingPoint::where('mountain_id', $mountain->id)->first();

        $payload = [
            'expedition_id' => $openExpedition->id,
            'route_id' => $openExpedition->route_id,
            'meeting_point_id' => $meetingPoint->id,
            'trip_type' => 'open',
            'customer_name' => 'Arkan Isa Alvaro',
            'customer_email' => 'arkan@example.com',
            'customer_phone' => '081234567890',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'participants' => [
                ['full_name' => 'Arkan Isa Alvaro', 'nik' => '3301234567890001', 'is_leader' => true],
                ['full_name' => 'Budi Santoso', 'nik' => '3301234567890002', 'is_leader' => false],
            ],
        ];

        $response = $this->postJson(route('bookings.store'), $payload);

        $response->assertCreated();
        $bookingCode = $response->json('booking_code');
        $this->assertEquals(route('checkout.step1', $bookingCode), $response->json('redirect_url'));

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'trip_type' => 'open',
            'total_booking_fee' => 2 * $mountain->booking_fee_per_pax,
            'status' => 'open',
        ]);
    }

    public function test_private_trip_booking_charges_full_payment_without_dp_and_redirects_to_private_checkout(): void
    {
        $mountain = Mountain::where('slug', 'mt-merbabu')->first();
        $privateExpedition = Expedition::where('mountain_id', $mountain->id)->where('type', 'private')->first();
        $meetingPoint = MeetingPoint::where('mountain_id', $mountain->id)->first();
        $addon = Addon::first();

        $pax = 4; // Tier 4-6 pax = 550.000 per pax
        $payload = [
            'expedition_id' => $privateExpedition->id,
            'route_id' => $privateExpedition->route_id,
            'meeting_point_id' => $meetingPoint->id,
            'trip_type' => 'private',
            'customer_name' => 'Bambang Pamungkas',
            'customer_email' => 'bambang@example.com',
            'customer_phone' => '081987654321',
            'customer_nik' => '3301234567890010',
            'pax_count' => $pax,
            'participants' => [
                ['full_name' => 'Bambang Pamungkas', 'nik' => '3301234567890010', 'is_leader' => true],
                ['full_name' => 'Peserta Dua', 'nik' => '3301234567890011', 'is_leader' => false],
                ['full_name' => 'Peserta Tiga', 'nik' => '3301234567890012', 'is_leader' => false],
                ['full_name' => 'Peserta Empat', 'nik' => '3301234567890013', 'is_leader' => false],
            ],
            'addons' => [
                ['id' => $addon->id, 'quantity' => 2],
            ],
        ];

        $response = $this->postJson(route('bookings.store'), $payload);

        $response->assertCreated();
        $bookingCode = $response->json('booking_code');
        $this->assertEquals(route('checkout.private', $bookingCode), $response->json('redirect_url'));

        $expectedTierPrice = 550000;
        $expectedGrandTotal = ($pax * $expectedTierPrice) + ($meetingPoint->additional_price_per_pax * $pax) + ($addon->price * 2);

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'trip_type' => 'private',
            'total_booking_fee' => 0, // Tanpa DP
            'locked_price_per_pax' => $expectedTierPrice,
            'grand_total' => $expectedGrandTotal,
            'remaining_payment_total' => $expectedGrandTotal,
            'status' => 'open',
        ]);

        // Visit private checkout page
        $privatePage = $this->get(route('checkout.private', $bookingCode));
        $privatePage->assertOk();
        $privatePage->assertSee('Pembayaran Private Trip');
        $privatePage->assertSee('Tanpa DP');

        // Pay 100% full payment
        $payResponse = $this->post(route('checkout.pay_private', $bookingCode), [
            'payment_method' => 'BCA Virtual Account',
        ]);

        $payResponse->assertRedirect(route('checkout.success', $bookingCode));

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'status' => 'paid',
        ]);
    }
}
