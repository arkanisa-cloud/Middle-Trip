<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\PaymentTransaction;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
        Config::set('midtrans.server_key', 'SB-Mid-server-TEST12345');

        Http::fake([
            'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
                'token' => 'mock-snap-token-xyz',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/mock-snap-token-xyz',
            ], 201),
        ]);
    }

    public function test_customer_can_progress_through_full_checkout_lifecycle(): void
    {
        $expedition = Expedition::where('type', 'open')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-TEST-001',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'Arkan Isa Alvaro',
            'customer_email' => 'arkan@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 300000,
            'grand_total' => 1000000,
            'status' => 'open',
        ]);

        // 1. Visit Step 1: Payment of DP
        $responseStep1 = $this->get(route('checkout.step1', $booking->booking_code));
        $responseStep1->assertOk();
        $responseStep1->assertSee('Data Pemesan');
        $responseStep1->assertSee('Bayar Booking Fee');
        $responseStep1->assertSee('Pilih Metode Pembayaran');
        $responseStep1->assertSee('Bank BCA');

        // 2. Pay DP -> returns Snap token and records pending transaction
        $payDpResponse = $this->postJson(route('checkout.pay_dp', $booking->booking_code), [
            'payment_method' => 'bca',
        ]);
        $payDpResponse->assertOk();
        $payDpResponse->assertJson([
            'status' => 'success',
            'snap_token' => 'mock-snap-token-xyz',
        ]);

        $this->assertDatabaseHas('payment_transactions', [
            'booking_id' => $booking->id,
            'payment_stage' => 'booking_fee',
            'payment_method' => 'bca',
            'status' => 'pending',
        ]);

        // Simulate Midtrans Webhook confirms DP payment
        $transaction = PaymentTransaction::where('booking_id', $booking->id)->first();
        $serverKey = 'SB-Mid-server-TEST12345';
        $signature = hash('sha512', $transaction->transaction_code.'200'.'300000.00'.$serverKey);

        $this->postJson('/midtrans/notification', [
            'order_id' => $transaction->transaction_code,
            'status_code' => '200',
            'gross_amount' => '300000.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
        ])->assertOk();

        $this->assertEquals('reserved', $booking->fresh()->status);

        // 3. Visit Step 2: Status page
        $responseStep2 = $this->get(route('checkout.status', $booking->booking_code));
        $responseStep2->assertOk();
        $responseStep2->assertSee('Booking Berhasil');

        // 4. Simulate Price Lock reached
        $booking->update([
            'status' => 'price_locked',
            'locked_price_per_pax' => 475000,
            'remaining_payment_total' => 650000,
            'payment_deadline' => now()->addHours(48),
        ]);

        // 5. Pay Remaining settlement -> returns Snap token and records pending settlement transaction
        $settleResponse = $this->postJson(route('checkout.settle', $booking->booking_code));
        $settleResponse->assertOk();
        $settleResponse->assertJson([
            'status' => 'success',
            'snap_token' => 'mock-snap-token-xyz',
        ]);

        $settleTransaction = PaymentTransaction::where('booking_id', $booking->id)
            ->where('payment_stage', 'settlement')
            ->first();
        $this->assertNotNull($settleTransaction);

        // Simulate Midtrans Webhook confirms settlement payment
        $settleSignature = hash('sha512', $settleTransaction->transaction_code.'200'.'650000.00'.$serverKey);
        $this->postJson('/midtrans/notification', [
            'order_id' => $settleTransaction->transaction_code,
            'status_code' => '200',
            'gross_amount' => '650000.00',
            'signature_key' => $settleSignature,
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
        ])->assertOk();

        $this->assertEquals('paid', $booking->fresh()->status);

        // 6. Visit Step 3: Success page
        $responseStep3 = $this->get(route('checkout.success', $booking->booking_code));
        $responseStep3->assertOk();
        $responseStep3->assertSee('Pembayaran Lunas');
        $responseStep3->assertSee('Gabung Grup WhatsApp Koordinasi');
    }

    public function test_customer_can_fill_manual_participant_details_when_paying_dp(): void
    {
        $expedition = Expedition::where('type', 'open')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-TEST-MANUAL-01',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'Koordinator Awal',
            'customer_email' => 'koor@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 3,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 450000,
            'grand_total' => 1500000,
            'status' => 'open',
        ]);

        $payload = [
            'customer_name' => 'Fajar Pratama',
            'customer_email' => 'fajar@example.com',
            'customer_phone' => '081298765432',
            'customer_nik' => '3302010101900001',
            'participants' => [
                ['full_name' => 'Fajar Pratama (Ketua)', 'nik' => '3302010101900001', 'is_leader' => 1],
                ['full_name' => 'Dimas Anggara', 'nik' => '3302010101900002', 'is_leader' => 0],
                ['full_name' => 'Rina Salsabila', 'nik' => '3302010101900003', 'is_leader' => 0],
            ],
        ];

        $response = $this->postJson(route('checkout.pay_dp', $booking->booking_code), $payload);
        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'snap_token' => 'mock-snap-token-xyz',
        ]);

        $freshBooking = $booking->fresh();
        $this->assertEquals('Fajar Pratama', $freshBooking->customer_name);
        $this->assertEquals('3302010101900001', $freshBooking->customer_nik);

        $this->assertDatabaseCount('booking_participants', 3);
        $this->assertDatabaseHas('booking_participants', [
            'booking_id' => $booking->id,
            'full_name' => 'Dimas Anggara',
            'nik' => '3302010101900002',
            'is_leader' => false,
        ]);
        $this->assertDatabaseHas('booking_participants', [
            'booking_id' => $booking->id,
            'full_name' => 'Rina Salsabila',
            'nik' => '3302010101900003',
            'is_leader' => false,
        ]);
    }

    public function test_customer_can_pay_private_trip_with_snap(): void
    {
        $expedition = Expedition::where('type', 'private')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-TEST-PVT-01',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'private',
            'customer_name' => 'Private Lead',
            'customer_email' => 'lead@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 850000,
            'status' => 'open',
        ]);

        $pageResponse = $this->get(route('checkout.private', $booking->booking_code));
        $pageResponse->assertOk();
        $pageResponse->assertSee('Pilih Metode Pembayaran');
        $pageResponse->assertSee('Bank BCA');

        $response = $this->postJson(route('checkout.pay_private', $booking->booking_code), [
            'payment_method' => 'qris',
        ]);
        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'snap_token' => 'mock-snap-token-xyz',
        ]);

        $this->assertDatabaseHas('payment_transactions', [
            'booking_id' => $booking->id,
            'payment_stage' => 'full_payment',
            'payment_method' => 'qris',
            'amount' => 850000,
            'status' => 'pending',
        ]);
    }
}
