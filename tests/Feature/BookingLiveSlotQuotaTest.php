<?php

namespace Tests\Feature;

use App\Models\Expedition;
use App\Models\PaymentTransaction;
use App\Models\User;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingLiveSlotQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_live_slot_does_not_increase_on_order_until_booking_fee_is_paid(): void
    {
        $user = User::factory()->create();

        /** @var Expedition $expedition */
        $expedition = Expedition::where('type', 'open')->firstOrFail();
        $expedition->update([
            'quota_max' => 10,
            'quota_booked' => 0,
        ]);
        $mountain = $expedition->mountain;

        // 1. Periksa halaman detail awal: kuota masih 0 dari 10
        $initialDetailResponse = $this->get(route('ekspedisi.show', $mountain->slug));
        $initialDetailResponse->assertOk();
        $initialDetailResponse->assertSee('0 dari 10 peserta');

        // 2. User melakukan pemesanan (buat booking baru dengan status 'open')
        $paxCount = 2;
        $bookingPayload = [
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'customer_name' => 'Pendaki Penguji',
            'customer_email' => 'penguji@example.com',
            'customer_phone' => '081234567890',
            'customer_nik' => '3301234567890001',
            'pax_count' => $paxCount,
            'participants' => [
                ['full_name' => 'Pendaki Penguji', 'nik' => '3301234567890001', 'is_leader' => true],
                ['full_name' => 'Peserta Dua', 'nik' => '3301234567890002', 'is_leader' => false],
            ],
        ];

        $storeResponse = $this->actingAs($user)->postJson(route('bookings.store'), $bookingPayload);
        $storeResponse->assertCreated();

        $bookingCode = $storeResponse->json('booking_code');
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'status' => 'open',
            'pax_count' => $paxCount,
        ]);

        // Verifikasi bahwa kuota di database BELUM bertambah
        $this->assertEquals(0, $expedition->fresh()->quota_booked);

        // Verifikasi bahwa bar live slot di halaman detail gunung BELUM bertambah
        $detailAfterOrderResponse = $this->get(route('ekspedisi.show', $mountain->slug));
        $detailAfterOrderResponse->assertOk();
        $detailAfterOrderResponse->assertSee('0 dari 10 peserta');

        // 3. User memulai pembayaran DP via Midtrans Snap
        Http::fake([
            'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
                'token' => 'snap-test-token-quota',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/snap-test-token-quota',
            ], 201),
        ]);

        $payDpResponse = $this->actingAs($user)->postJson(route('checkout.pay_dp', $bookingCode));
        $payDpResponse->assertOk();
        $payDpResponse->assertJson([
            'status' => 'success',
            'snap_token' => 'snap-test-token-quota',
        ]);

        // Verifikasi bahwa kuota di database BELUM bertambah hanya karena pay_dp (karena belum dibayar)
        $this->assertEquals(0, $expedition->fresh()->quota_booked);

        // Verifikasi bahwa user yang belum bayar dilarang mengakses halaman status reserved
        $statusBeforePaymentResponse = $this->actingAs($user)->get(route('checkout.status', $bookingCode));
        $statusBeforePaymentResponse->assertRedirect(route('checkout.step1', $bookingCode));
        $statusBeforePaymentResponse->assertSessionHas('warning');

        // Simulasi webhook notifikasi Midtrans settlement untuk mengubah status booking menjadi 'reserved'
        $transaction = PaymentTransaction::where('transaction_code', $payDpResponse->json('order_id'))->first();
        $serverKey = config('midtrans.server_key');
        $signature = hash('sha512', $transaction->transaction_code.'200'.number_format($transaction->amount, 2, '.', '').$serverKey);

        $this->postJson('/midtrans/notification', [
            'order_id' => $transaction->transaction_code,
            'status_code' => '200',
            'gross_amount' => number_format($transaction->amount, 2, '.', ''),
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
        ])->assertOk();

        // Verifikasi status booking berubah menjadi 'reserved'
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'status' => 'reserved',
        ]);

        // Verifikasi bahwa kuota di database SEKARANG bertambah menjadi 2
        $this->assertEquals(2, $expedition->fresh()->quota_booked);

        // Verifikasi bahwa user sekarang dapat mengakses halaman status reserved
        $statusAfterPaymentResponse = $this->actingAs($user)->get(route('checkout.status', $bookingCode));
        $statusAfterPaymentResponse->assertOk();

        // Verifikasi bahwa bar live slot di halaman detail gunung SEKARANG bertambah menjadi 2 dari 10 peserta
        $detailAfterPaymentResponse = $this->get(route('ekspedisi.show', $mountain->slug));
        $detailAfterPaymentResponse->assertOk();
        $detailAfterPaymentResponse->assertSee('2 dari 10 peserta');
        $detailAfterPaymentResponse->assertSee('Tersisa 8 slot lagi');
    }
}
