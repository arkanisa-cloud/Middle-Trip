<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\PaymentTransaction;
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
}
