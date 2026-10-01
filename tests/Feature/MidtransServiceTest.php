<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Services\MidtransService;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class MidtransServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
        Config::set('midtrans.server_key', 'SB-Mid-server-TEST12345');
    }

    public function test_can_create_snap_token_successfully(): void
    {
        Http::fake([
            'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
                'token' => 'mock-snap-token-12345',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/mock-snap-token-12345',
            ], 201),
        ]);

        $expedition = Expedition::where('type', 'open')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-TEST-SNAP-01',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'Alvaro Dev',
            'customer_email' => 'alvaro@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 300000,
            'grand_total' => 1000000,
            'status' => 'open',
        ]);

        $service = new MidtransService;
        $result = $service->createSnapToken($booking, 'booking_fee', 300000);

        $this->assertEquals('mock-snap-token-12345', $result['token']);
        $this->assertStringStartsWith('MT-DP-', $result['order_id']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://app.sandbox.midtrans.com/snap/v1/transactions'
                && $request['transaction_details']['gross_amount'] === 300000
                && $request->hasHeader('Authorization');
        });
    }

    public function test_throws_exception_if_server_key_missing(): void
    {
        Config::set('midtrans.server_key', '');

        $expedition = Expedition::where('type', 'open')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-TEST-SNAP-NOKEY',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'No Key User',
            'customer_email' => 'nokey@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Midtrans Server Key belum dikonfigurasi pada file .env.');

        $service = new MidtransService;
        $service->createSnapToken($booking, 'booking_fee', 150000);
    }

    public function test_can_filter_enabled_payments_when_payment_method_is_specified(): void
    {
        Http::fake([
            'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
                'token' => 'mock-snap-token-bca',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/mock-snap-token-bca',
            ], 201),
        ]);

        $expedition = Expedition::where('type', 'open')->first();
        $booking = Booking::create([
            'booking_code' => 'MT-TEST-SNAP-BCA',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'Alvaro Dev',
            'customer_email' => 'alvaro@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
        ]);

        $service = new MidtransService;
        $service->createSnapToken($booking, 'booking_fee', 150000, 'bca');

        Http::assertSent(function ($request) {
            return ($request['enabled_payments'] ?? []) === ['bca_va'];
        });
    }

    public function test_verifies_signature_authenticity_correctly(): void
    {
        $serverKey = 'SB-Mid-server-TEST12345';
        Config::set('midtrans.server_key', $serverKey);

        $orderId = 'MT-TEST-001';
        $statusCode = '200';
        $grossAmount = '300000.00';
        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        $service = new MidtransService;
        $this->assertTrue($service->verifySignature($orderId, $statusCode, $grossAmount, $expectedSignature));
        $this->assertFalse($service->verifySignature($orderId, $statusCode, $grossAmount, 'fake-signature'));
    }

    public function test_parses_transaction_statuses_accurately(): void
    {
        $service = new MidtransService;

        $settlement = $service->parseTransactionStatus(['transaction_status' => 'settlement']);
        $this->assertEquals('success', $settlement['status']);
        $this->assertTrue($settlement['is_success']);

        $captureAccept = $service->parseTransactionStatus([
            'transaction_status' => 'capture',
            'fraud_status' => 'accept',
        ]);
        $this->assertEquals('success', $captureAccept['status']);
        $this->assertTrue($captureAccept['is_success']);

        $pending = $service->parseTransactionStatus(['transaction_status' => 'pending']);
        $this->assertEquals('pending', $pending['status']);
        $this->assertFalse($pending['is_success']);

        $expired = $service->parseTransactionStatus(['transaction_status' => 'expire']);
        $this->assertEquals('expired', $expired['status']);
        $this->assertFalse($expired['is_success']);
    }
}
