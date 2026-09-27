<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
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

        // 2. Pay DP -> transitions to 'reserved'
        $payDpResponse = $this->post(route('checkout.pay_dp', $booking->booking_code), [
            'payment_method' => 'BCA Virtual Account',
        ]);
        $payDpResponse->assertRedirect(route('checkout.status', $booking->booking_code));
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

        // 5. Pay Remaining settlement -> transitions to 'paid'
        $settleResponse = $this->post(route('checkout.settle', $booking->booking_code), [
            'payment_method' => 'QRIS',
        ]);
        $settleResponse->assertRedirect(route('checkout.success', $booking->booking_code));
        $this->assertEquals('paid', $booking->fresh()->status);

        // 6. Visit Step 3: Success page
        $responseStep3 = $this->get(route('checkout.success', $booking->booking_code));
        $responseStep3->assertOk();
        $responseStep3->assertSee('Pembayaran Lunas');
        $responseStep3->assertSee('Gabung Grup WhatsApp Koordinasi');
    }
}
