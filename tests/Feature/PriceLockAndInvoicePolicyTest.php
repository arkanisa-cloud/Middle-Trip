<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\ExpeditionPriceTier;
use App\Models\Mountain;
use App\Models\PaymentTransaction;
use App\Models\Route;
use App\Models\User;
use App\Services\BookingService;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PriceLockAndInvoicePolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);

        Http::fake([
            'api.fonnte.com/*' => Http::response([
                'status' => true,
                'registered' => true,
            ], 200),
        ]);
    }

    public function test_price_lock_cancels_all_unpaid_dp_bookings_and_locks_reserved_bookings(): void
    {
        $expedition = Expedition::where('type', 'open')->where('status', 'open')->first();

        // Booking 1: Unpaid DP (status = open)
        $unpaidBooking = Booking::create([
            'booking_code' => 'MT-UNPAID-01',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'customer_name' => 'Pendaki Belum Bayar',
            'customer_email' => 'unpaid.pendaki@gmail.com',
            'customer_phone' => '081298765432',
            'customer_nik' => '3301011508950001',
            'pax_count' => 2,
            'trip_type' => 'open',
            'status' => 'open',
            'booking_fee_per_pax' => 200000,
            'total_booking_fee' => 400000,
            'grand_total' => 1000000,
        ]);

        PaymentTransaction::create([
            'booking_id' => $unpaidBooking->id,
            'transaction_code' => 'TX-SNAP-UNPAID',
            'payment_stage' => 'booking_fee',
            'payment_method' => 'midtrans',
            'amount' => 400000,
            'status' => 'pending',
        ]);

        // Booking 2: Paid DP (status = reserved)
        $reservedBooking = Booking::create([
            'booking_code' => 'MT-RESERVED-01',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'customer_name' => 'Pendaki Sudah Bayar DP',
            'customer_email' => 'reserved.pendaki@gmail.com',
            'customer_phone' => '081298765433',
            'customer_nik' => '3301011508950002',
            'pax_count' => 2,
            'trip_type' => 'open',
            'status' => 'reserved',
            'booking_fee_per_pax' => 200000,
            'total_booking_fee' => 400000,
            'grand_total' => 1000000,
        ]);

        // Execute price lock
        app(BookingService::class)->executePriceLock($expedition);

        $expedition->refresh();
        $this->assertEquals('price_locked', $expedition->status);

        // Unpaid booking must be cancelled
        $unpaidBooking->refresh();
        $this->assertEquals('cancelled', $unpaidBooking->status);
        $this->assertStringContainsString('Dibatalkan otomatis karena pendaftaran batch ekspedisi telah ditutup & harga dikunci', $unpaidBooking->notes);

        // Pending transaction must be marked expired
        $tx = PaymentTransaction::where('booking_id', $unpaidBooking->id)->first();
        $this->assertEquals('expired', $tx->status);

        // Reserved booking must be updated to price_locked with settlement details
        $reservedBooking->refresh();
        $this->assertEquals('price_locked', $reservedBooking->status);
        $this->assertNotNull($reservedBooking->locked_price_per_pax);
        $this->assertNotNull($reservedBooking->payment_deadline);
    }

    public function test_cannot_pay_dp_when_expedition_is_price_locked(): void
    {
        $expedition = Expedition::where('type', 'open')->first();
        $expedition->update(['status' => 'price_locked', 'current_locked_price' => 500000]);

        $booking = Booking::create([
            'booking_code' => 'MT-TEST-LOCK-01',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'customer_name' => 'Pendaki Terlambat',
            'customer_email' => 'terlambat.bayar@gmail.com',
            'customer_phone' => '081298765434',
            'customer_nik' => '3301011508950003',
            'pax_count' => 1,
            'trip_type' => 'open',
            'status' => 'open',
            'booking_fee_per_pax' => 200000,
            'total_booking_fee' => 200000,
            'grand_total' => 600000,
        ]);

        // Accessing step 1 cancels booking and redirects
        $response = $this->get(route('checkout.step1', $booking->booking_code));
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);

        // Reset to open for payDp test
        $booking->update(['status' => 'open']);

        $responseJson = $this->postJson(route('checkout.pay_dp', $booking->booking_code));
        $responseJson->assertStatus(422);
        $responseJson->assertJsonFragment(['status' => 'cancelled']);

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
    }

    public function test_cannot_create_new_booking_on_price_locked_expedition(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'phone' => '081298712345',
            'nik' => '3301011508950001',
        ]);
        $expedition = Expedition::where('type', 'open')->first();
        $expedition->update(['status' => 'price_locked', 'current_locked_price' => 500000]);

        $payload = [
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'pax_count' => 1,
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi.santoso@gmail.com',
            'customer_phone' => '081298712345',
            'customer_nik' => '3301011508950001',
            'participants' => [
                ['full_name' => 'Budi Santoso', 'nik' => '3301011508950001', 'is_leader' => true],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('bookings.store'), $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['expedition_id']);
    }

    public function test_customer_cannot_print_invoice_when_only_dp_is_paid(): void
    {
        $user = User::factory()->create([
            'name' => 'Ahmad Dani',
            'email' => 'ahmad.dani@gmail.com',
        ]);
        $expedition = Expedition::where('type', 'open')->first();

        // Booking with DP paid (status = reserved)
        $booking = Booking::create([
            'user_id' => $user->id,
            'booking_code' => 'MT-PRINT-DP-ONLY',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '081234567890',
            'customer_nik' => '3301011508950001',
            'pax_count' => 1,
            'trip_type' => 'open',
            'status' => 'reserved',
            'booking_fee_per_pax' => 250000,
            'total_booking_fee' => 250000,
            'grand_total' => 600000,
            'remaining_payment_total' => 350000,
        ]);

        // Customer attempts to open print page
        $response = $this->actingAs($user)->get(route('bookings.print', $booking->booking_code));

        // Must redirect to checkout.status and not display invoice
        $response->assertRedirect(route('checkout.status', $booking->booking_code));
        $response->assertSessionHas('warning', 'Invoice dan E-Tiket resmi baru dapat dicetak setelah Anda melunasi pembayaran.');

        // Profile orders list must NOT contain "Cetak PDF" for this booking
        $profileResponse = $this->actingAs($user)->get(route('profile.edit', ['tab' => 'orders']));
        $profileResponse->assertOk();
        $profileResponse->assertDontSee(route('bookings.print', $booking->booking_code));

        // When booking is fully paid (status = paid)
        $booking->update(['status' => 'paid']);

        $printResponse = $this->actingAs($user)->get(route('bookings.print', $booking->booking_code));
        $printResponse->assertOk();
        $printResponse->assertSee('Invoice Resmi');

        // Profile orders list now shows "Cetak PDF"
        $profileResponseAfterPaid = $this->actingAs($user)->get(route('profile.edit', ['tab' => 'orders']));
        $profileResponseAfterPaid->assertOk();
        $profileResponseAfterPaid->assertSee(route('bookings.print', $booking->booking_code));
    }

    public function test_execute_price_lock_calculates_dynamic_tektok_price_correctly(): void
    {
        $mountain = Mountain::create([
            'name' => 'Gunung Merbabu Tektok Test',
            'slug' => 'gunung-merbabu-tektok-test',
            'elevation' => 3142,
            'province' => 'Jawa Tengah',
            'cover_image' => 'mountain.jpg',
            'base_price' => 500000,
            'price_tektok' => 380000,
            'booking_fee_per_pax' => 100000,
        ]);

        $route = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Selo',
            'slug' => 'via-selo-tektok-test',
            'grade' => 'Grade A',
            'is_primary' => true,
        ]);

        ExpeditionPriceTier::create([
            'mountain_id' => $mountain->id,
            'min_pax' => 1,
            'max_pax' => 3,
            'price_per_pax' => 650000,
        ]);
        ExpeditionPriceTier::create([
            'mountain_id' => $mountain->id,
            'min_pax' => 4,
            'max_pax' => 6,
            'price_per_pax' => 550000,
        ]);
        ExpeditionPriceTier::create([
            'mountain_id' => $mountain->id,
            'min_pax' => 7,
            'max_pax' => 10,
            'price_per_pax' => 475000,
        ]);

        // Open Trip Tektok expedition with 5 booked quota (should hit tier 4-6 pax: 550.000 camping -> 418.000 tektok)
        $expedition = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'type' => 'open',
            'hiking_type' => 'tektok',
            'departure_date' => now()->addDays(2),
            'return_date' => now()->addDays(2),
            'quota_max' => 10,
            'quota_booked' => 5,
            'status' => 'open',
        ]);

        $booking = Booking::create([
            'booking_code' => 'MT-TEKTOK-LOCK-01',
            'expedition_id' => $expedition->id,
            'route_id' => $route->id,
            'customer_name' => 'Pendaki Tektok Cepat',
            'customer_email' => 'tektok.cepat@gmail.com',
            'customer_phone' => '081298765434',
            'customer_nik' => '3301011508950009',
            'pax_count' => 2,
            'trip_type' => 'open',
            'hiking_type' => 'tektok',
            'status' => 'reserved',
            'booking_fee_per_pax' => 100000,
            'total_booking_fee' => 200000,
            'grand_total' => 760000,
        ]);

        app(BookingService::class)->executePriceLock($expedition);

        $expedition->refresh();
        $booking->refresh();

        // 550.000 * (380.000 / 500.000) = 418.000
        $this->assertEquals(418000, $expedition->current_locked_price);
        $this->assertEquals(418000, $booking->locked_price_per_pax);
        $this->assertEquals(836000, $booking->grand_total);
        $this->assertEquals(636000, $booking->remaining_payment_total);
    }

    public function test_execute_price_lock_uses_explicit_price_per_pax_tektok_when_defined(): void
    {
        $mountain = Mountain::create([
            'name' => 'Gunung Prau Explicit Tektok Test',
            'slug' => 'gunung-prau-explicit-tektok-test',
            'elevation' => 2565,
            'province' => 'Jawa Tengah',
            'cover_image' => 'mountain.jpg',
            'base_price' => 500000,
            'price_tektok' => 380000,
            'booking_fee_per_pax' => 100000,
        ]);

        $route = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Dieng',
            'slug' => 'via-dieng-test',
            'grade' => 'Grade A',
            'is_primary' => true,
        ]);

        // Tier with explicit tektok price
        ExpeditionPriceTier::create([
            'mountain_id' => $mountain->id,
            'min_pax' => 1,
            'max_pax' => 3,
            'price_per_pax' => 600000,
            'price_per_pax_tektok' => 450000,
        ]);
        ExpeditionPriceTier::create([
            'mountain_id' => $mountain->id,
            'min_pax' => 4,
            'max_pax' => 6,
            'price_per_pax' => 500000,
            'price_per_pax_tektok' => 350000, // Explicitly defined, not 50%
        ]);

        $expedition = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'type' => 'open',
            'hiking_type' => 'tektok',
            'departure_date' => now()->addDays(2),
            'return_date' => now()->addDays(2),
            'quota_max' => 10,
            'quota_booked' => 5,
            'status' => 'open',
        ]);

        $booking = Booking::create([
            'booking_code' => 'MT-EXPLICIT-TEKTOK-01',
            'expedition_id' => $expedition->id,
            'route_id' => $route->id,
            'customer_name' => 'Pendaki Explicit',
            'customer_email' => 'explicit@gmail.com',
            'customer_phone' => '081298765435',
            'customer_nik' => '3301011508950010',
            'pax_count' => 3,
            'trip_type' => 'open',
            'hiking_type' => 'tektok',
            'status' => 'reserved',
            'booking_fee_per_pax' => 100000,
            'total_booking_fee' => 300000,
            'grand_total' => 1050000,
        ]);

        app(BookingService::class)->executePriceLock($expedition);

        $expedition->refresh();
        $booking->refresh();

        // Must use explicit price_per_pax_tektok = 350.000
        $this->assertEquals(350000, $expedition->current_locked_price);
        $this->assertEquals(350000, $booking->locked_price_per_pax);
        $this->assertEquals(1050000, $booking->grand_total);
        $this->assertEquals(750000, $booking->remaining_payment_total);
    }
}
