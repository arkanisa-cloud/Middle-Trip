<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\PaymentTransaction;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
        Config::set('midtrans.server_key', 'SB-Mid-server-TEST12345');
    }

    public function test_valid_settlement_webhook_updates_dp_transaction_and_booking_to_reserved(): void
    {
        $expedition = Expedition::where('type', 'open')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-WH-TEST-01',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'Pendaki Webhook',
            'customer_email' => 'webhook@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 300000,
            'grand_total' => 1000000,
            'status' => 'open',
        ]);

        $orderId = 'MT-DP-'.$booking->id.'-123456';
        PaymentTransaction::create([
            'booking_id' => $booking->id,
            'transaction_code' => $orderId,
            'payment_stage' => 'booking_fee',
            'payment_method' => 'bank_transfer',
            'amount' => 300000,
            'status' => 'pending',
        ]);

        $serverKey = 'SB-Mid-server-TEST12345';
        $signature = hash('sha512', $orderId.'200'.'300000.00'.$serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '300000.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
        ];

        $response = $this->postJson('/midtrans/notification', $payload);

        $response->assertOk();
        $response->assertJson(['status' => 'ok']);

        $this->assertEquals('reserved', $booking->fresh()->status);
        $transaction = PaymentTransaction::where('transaction_code', $orderId)->first();
        $this->assertEquals('success', $transaction->status);
        $this->assertEquals('qris', $transaction->payment_method);
        $this->assertNotNull($transaction->paid_at);
    }

    public function test_valid_settlement_webhook_updates_settlement_and_private_to_paid(): void
    {
        $expedition = Expedition::where('type', 'private')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-WH-TEST-02',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'private',
            'customer_name' => 'Private Webhook',
            'customer_email' => 'pvt@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 300000,
            'grand_total' => 1200000,
            'status' => 'open',
        ]);

        $orderId = 'MT-PVT-'.$booking->id.'-654321';
        PaymentTransaction::create([
            'booking_id' => $booking->id,
            'transaction_code' => $orderId,
            'payment_stage' => 'full_payment',
            'payment_method' => 'gopay',
            'amount' => 1200000,
            'status' => 'pending',
        ]);

        $serverKey = 'SB-Mid-server-TEST12345';
        $signature = hash('sha512', $orderId.'200'.'1200000.00'.$serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '1200000.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'gopay',
        ];

        $response = $this->postJson('/midtrans/notification', $payload);

        $response->assertOk();
        $this->assertEquals('paid', $booking->fresh()->status);
        $this->assertEquals('success', PaymentTransaction::where('transaction_code', $orderId)->first()->status);
    }

    public function test_invalid_signature_is_rejected_with_403(): void
    {
        $payload = [
            'order_id' => 'MT-DP-999-123456',
            'status_code' => '200',
            'gross_amount' => '300000.00',
            'signature_key' => 'invalid-signature-hash',
            'transaction_status' => 'settlement',
        ];

        $response = $this->postJson('/midtrans/notification', $payload);
        $response->assertForbidden();
    }

    public function test_webhook_handles_expired_or_failed_transaction(): void
    {
        $expedition = Expedition::where('type', 'open')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-WH-TEST-03',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'Failed Webhook',
            'customer_email' => 'failed@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
        ]);

        $orderId = 'MT-DP-'.$booking->id.'-777777';
        PaymentTransaction::create([
            'booking_id' => $booking->id,
            'transaction_code' => $orderId,
            'payment_stage' => 'booking_fee',
            'payment_method' => 'bank_transfer',
            'amount' => 150000,
            'status' => 'pending',
        ]);

        $serverKey = 'SB-Mid-server-TEST12345';
        $signature = hash('sha512', $orderId.'200'.'150000.00'.$serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '150000.00',
            'signature_key' => $signature,
            'transaction_status' => 'expire',
            'payment_type' => 'bank_transfer',
        ];

        $response = $this->postJson('/midtrans/notification', $payload);
        $response->assertOk();

        $this->assertEquals('open', $booking->fresh()->status);
        $this->assertEquals('expired', PaymentTransaction::where('transaction_code', $orderId)->first()->status);
    }
}
