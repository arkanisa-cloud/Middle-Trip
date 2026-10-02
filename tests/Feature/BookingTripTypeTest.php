<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\Expedition;
use App\Models\MeetingPoint;
use App\Models\Mountain;
use App\Models\PaymentTransaction;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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
        $user = User::factory()->create();
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

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);

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
        $user = User::factory()->create();
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

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);

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

        // Pay 100% full payment via Midtrans Snap
        Http::fake([
            'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
                'token' => 'snap-test-token-private',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/snap-test-token-private',
            ], 201),
        ]);

        $payResponse = $this->postJson(route('checkout.pay_private', $bookingCode));
        $payResponse->assertOk();
        $payResponse->assertJson([
            'status' => 'success',
            'snap_token' => 'snap-test-token-private',
        ]);

        // Settle payment via webhook
        $booking = Booking::where('booking_code', $bookingCode)->first();
        $transaction = PaymentTransaction::where('booking_id', $booking->id)->first();
        $serverKey = config('midtrans.server_key');
        $signature = hash('sha512', $transaction->transaction_code.'200'.number_format($transaction->amount, 2, '.', '').$serverKey);

        $this->postJson('/midtrans/notification', [
            'order_id' => $transaction->transaction_code,
            'status_code' => '200',
            'gross_amount' => number_format($transaction->amount, 2, '.', ''),
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'bca_va',
        ])->assertOk();

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'status' => 'paid',
        ]);
    }

    public function test_user_can_choose_custom_departure_date_for_private_trip_and_bypass_open_trip_quota(): void
    {
        $user = User::factory()->create();
        $mountain = Mountain::where('slug', 'mt-merbabu')->first();
        $openExpedition = Expedition::where('mountain_id', $mountain->id)->where('type', 'open')->first();
        // Set open trip quota full
        $openExpedition->update(['quota_max' => 5, 'quota_booked' => 5]);

        $customDeparture = now()->addDays(25)->toDateString();
        $route = $mountain->primaryRoute ?? $mountain->routes()->first();

        $payload = [
            'expedition_id' => $openExpedition->id,
            'route_id' => $route->id,
            'trip_type' => 'private',
            'departure_date' => $customDeparture,
            'customer_name' => 'Pendaki Privat',
            'customer_email' => 'privat@example.com',
            'customer_phone' => '081234567890',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'participants' => [
                ['full_name' => 'Pendaki Privat', 'nik' => '3301234567890001', 'is_leader' => true],
                ['full_name' => 'Rekan Privat', 'nik' => '3301234567890002', 'is_leader' => false],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);

        $response->assertCreated();
        $bookingCode = $response->json('booking_code');

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'trip_type' => 'private',
            'departure_date' => $customDeparture,
        ]);

        $privatePage = $this->get(route('checkout.private', $bookingCode));
        $privatePage->assertOk();
        $privatePage->assertSee(Carbon::parse($customDeparture)->translatedFormat('d F Y'));
    }

    public function test_visiting_detail_does_not_auto_generate_private_trip_batch(): void
    {
        $mountain = Mountain::where('slug', 'mt-merbabu')->first();

        // Hapus batch private trip yang dibuat seeder
        Expedition::where('mountain_id', $mountain->id)->where('type', 'private')->delete();
        $this->assertEquals(0, Expedition::where('mountain_id', $mountain->id)->where('type', 'private')->count());

        // Kunjungi halaman detail
        $detailResponse = $this->get(route('ekspedisi.show', $mountain->slug));
        $detailResponse->assertOk();
        $detailResponse->assertViewHas('bookingConfig', function ($config) {
            return $config['hasPrivateTrip'] === true
                && $config['privateExpeditionId'] === null;
        });

        // Pastikan tidak ada batch private trip yang ter-generate otomatis
        $this->assertDatabaseMissing('expeditions', [
            'mountain_id' => $mountain->id,
            'type' => 'private',
        ]);
    }

    public function test_private_trip_can_be_booked_without_creating_private_expedition_batch(): void
    {
        $user = User::factory()->create();
        $mountain = Mountain::where('slug', 'mt-merbabu')->first();

        // Hapus batch private trip yang dibuat seeder
        Expedition::where('mountain_id', $mountain->id)->where('type', 'private')->delete();

        $openExpedition = Expedition::where('mountain_id', $mountain->id)->where('type', 'open')->first();
        $initialOpenQuota = $openExpedition->quota_booked;
        $route = $mountain->routes()->first();
        $customDate = now()->addDays(10)->toDateString();

        $payload = [
            'expedition_id' => $openExpedition->id,
            'route_id' => $route->id,
            'trip_type' => 'private',
            'hiking_type' => 'camping',
            'departure_date' => $customDate,
            'customer_name' => 'Pendaki Tanpa Batch',
            'customer_email' => 'nobatch@example.com',
            'customer_phone' => '081234567899',
            'customer_nik' => '3301234567899999',
            'pax_count' => 2,
            'participants' => [
                ['full_name' => 'Pendaki Tanpa Batch', 'nik' => '3301234567899999', 'is_leader' => true],
                ['full_name' => 'Peserta Dua', 'nik' => '3301234567899998', 'is_leader' => false],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);
        $response->assertCreated();
        $bookingCode = $response->json('booking_code');

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'trip_type' => 'private',
            'route_id' => $route->id,
            'departure_date' => $customDate,
        ]);

        // Kuota open expedition tidak boleh berkurang/bertambah
        $openExpedition->refresh();
        $this->assertEquals($initialOpenQuota, $openExpedition->quota_booked);

        // Dan tidak ada batch private trip baru yang ter-generate di database
        $this->assertDatabaseMissing('expeditions', [
            'mountain_id' => $mountain->id,
            'type' => 'private',
        ]);
    }
}
