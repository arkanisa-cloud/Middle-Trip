# Midtrans Snap Payment Gateway Integration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mengganti seluruh sistem pembayaran hardcoded menjadi Midtrans Sandbox Snap API yang siap pakai (hanya butuh Server Key & Client Key di `.env`).

**Architecture:** Menggunakan Laravel native HTTP Client (`Http::fake()` friendly, zero 3rd-party dependencies) untuk komunikasi backend dengan Snap REST API (`https://app.sandbox.midtrans.com/snap/v1/transactions`), modal popup frontend via `snap.js`, dan webhook HTTP notification di `/midtrans/notification` dengan verifikasi signature hash SHA-512.

**Tech Stack:** Laravel 13, PHP 8.5, Laravel Sail, Midtrans Snap REST API, Tailwind CSS v4, Plus Jakarta Sans, Pest / PHPUnit 12.

**Spec:** [docs/superpowers/specs/2026-10-01-midtrans-snap-integration-design.md](file:///home/alvaro/Documents/SMK%20XII/Project/MiddleTrip/docs/superpowers/specs/2026-10-01-midtrans-snap-integration-design.md)

## Global Constraints

- Backend harus menggunakan native Laravel `Illuminate\Support\Facades\Http` (jangan menambah dependency composer tanpa izin sesuai `AGENTS.md`).
- Kode PHP harus mematuhi standar PHP 8.5 (constructor property promotion, explicit return types, type hinting pada semua parameter, kurung kurawal `{}` pada semua kontrol alur).
- Signature webhook harus diverifikasi menggunakan hash SHA-512: `hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey)` dengan `hash_equals()`.
- Seluruh unit & feature test harus dapat dijalankan secara terisolasi tanpa koneksi internet langsung menggunakan `Http::fake()`.
- Formatting kode wajib mematuhi standar Laravel Pint (`./vendor/bin/sail pint`).

---

### Task 1: Environment & Config Setup

**Files:**
- Create: `config/midtrans.php`
- Modify: `.env.example:65-68`
- Modify: `.env:65-68`
- Modify: `bootstrap/app.php:15-19`
- Test: `tests/Feature/MidtransConfigTest.php`

**Interfaces:**
- Produces: `config('midtrans.*')` yang diakses oleh `MidtransService` dan Blade templates.
- Produces: Pengecualian CSRF untuk route `midtrans/notification`.

- [ ] **Step 1: Write the failing test for configuration**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class MidtransConfigTest extends TestCase
{
    public function test_midtrans_configuration_has_expected_keys(): void
    {
        $this->assertNotNull(config('midtrans.snap_url'));
        $this->assertNotNull(config('midtrans.snap_api_url'));
        $this->assertNotNull(config('midtrans.core_api_url'));
        $this->assertFalse(config('midtrans.is_production'));
        $this->assertStringContainsString('sandbox', config('midtrans.snap_url'));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/sail artisan test --filter=MidtransConfigTest`  
Expected: FAIL with null config values.

- [ ] **Step 3: Write minimal configuration and update environment files**

Create `config/midtrans.php`:
```php
<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => (bool) env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => (bool) env('MIDTRANS_IS_3DS', true),

    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',

    'snap_api_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions',

    'core_api_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://api.midtrans.com/v2'
        : 'https://api.sandbox.midtrans.com/v2',
];
```

Tambahkan ke `.env.example` dan `.env`:
```env
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

Update `bootstrap/app.php` untuk bypass CSRF pada webhook notification:
```php
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
        ]);
    })
```

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/sail artisan test --filter=MidtransConfigTest`  
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add config/midtrans.php .env.example .env bootstrap/app.php tests/Feature/MidtransConfigTest.php
git commit -m "feat(midtrans): add config and csrf exemption for webhook"
```

---

### Task 2: Service Layer Implementation (`MidtransService`)

**Files:**
- Create: `app/Services/MidtransService.php`
- Test: `tests/Feature/MidtransServiceTest.php`

**Interfaces:**
- Consumes: `config('midtrans.*')`, `App\Models\Booking`.
- Produces: `MidtransService::createSnapToken(Booking $booking, string $paymentStage, int $amount): array`
- Produces: `MidtransService::verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool`
- Produces: `MidtransService::processNotification(array $payload): array`

- [ ] **Step 1: Write the failing tests for MidtransService**

```php
<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Services\MidtransService;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
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

        $service = new MidtransService();
        $result = $service->createSnapToken($booking, 'booking_fee', 300000);

        $this->assertEquals('mock-snap-token-12345', $result['token']);
        $this->assertStringStartsWith('MT-DP-', $result['order_id']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://app.sandbox.midtrans.com/snap/v1/transactions'
                && $request['transaction_details']['gross_amount'] === 300000
                && $request->hasHeader('Authorization');
        });
    }

    public function test_verifies_signature_authenticity_correctly(): void
    {
        $serverKey = 'SB-Mid-server-TEST12345';
        Config::set('midtrans.server_key', $serverKey);

        $orderId = 'MT-TEST-001';
        $statusCode = '200';
        $grossAmount = '300000.00';
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $service = new MidtransService();
        $this->assertTrue($service->verifySignature($orderId, $statusCode, $grossAmount, $expectedSignature));
        $this->assertFalse($service->verifySignature($orderId, $statusCode, $grossAmount, 'fake-signature'));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/sail artisan test --filter=MidtransServiceTest`  
Expected: FAIL with `MidtransService` class not found.

- [ ] **Step 3: Implement `App\Services\MidtransService`**

Create `app/Services/MidtransService.php`:
```php
<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransService
{
    /**
     * Membuat transaksi Snap dan mengembalikan token beserta redirect_url.
     *
     * @return array{token: string, redirect_url: string, order_id: string}
     */
    public function createSnapToken(Booking $booking, string $paymentStage, int $amount): array
    {
        $serverKey = config('midtrans.server_key');

        if (empty($serverKey)) {
            throw new RuntimeException('Midtrans Server Key belum dikonfigurasi pada file .env.');
        }

        $stagePrefix = match ($paymentStage) {
            'settlement' => 'SETTLE',
            'full_payment' => 'PVT',
            default => 'DP',
        };

        $orderId = sprintf('MT-%s-%d-%d', $stagePrefix, $booking->id, time());

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'phone' => $booking->customer_phone,
            ],
            'item_details' => [
                [
                    'id' => sprintf('ITEM-%s-%d', $stagePrefix, $booking->id),
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => match ($paymentStage) {
                        'settlement' => 'Pelunasan Ekspedisi MiddleTrip',
                        'full_payment' => 'Pembayaran Penuh Private Trip',
                        default => 'Booking Fee (DP) Ekspedisi MiddleTrip',
                    },
                ],
            ],
            'credit_card' => [
                'secure' => (bool) config('midtrans.is_3ds', true),
            ],
        ];

        $apiUrl = config('midtrans.snap_api_url');

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->post($apiUrl, $payload);

        if (! $response->successful()) {
            $errorMessage = $response->json('error_messages.0') ?? 'Gagal menghubungi Midtrans Snap API.';
            throw new RuntimeException($errorMessage);
        }

        $data = $response->json();

        return [
            'token' => $data['token'],
            'redirect_url' => $data['redirect_url'] ?? '',
            'order_id' => $orderId,
        ];
    }

    /**
     * Memverifikasi keabsahan signature hash SHA-512 dari Midtrans webhook.
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = config('midtrans.server_key');
        $computedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        return hash_equals($computedSignature, $signatureKey);
    }

    /**
     * Menerjemahkan status Midtrans ke dalam status transaksi aplikasi.
     *
     * @param  array<string, mixed>  $payload
     * @return array{status: string, is_success: bool}
     */
    public function parseTransactionStatus(array $payload): array
    {
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? null;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                return ['status' => 'pending', 'is_success' => false];
            }

            if ($fraudStatus === 'accept') {
                return ['status' => 'success', 'is_success' => true];
            }
        }

        if ($transactionStatus === 'settlement') {
            return ['status' => 'success', 'is_success' => true];
        }

        if ($transactionStatus === 'pending') {
            return ['status' => 'pending', 'is_success' => false];
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'failure'], true)) {
            return ['status' => 'failed', 'is_success' => false];
        }

        if ($transactionStatus === 'expire') {
            return ['status' => 'expired', 'is_success' => false];
        }

        return ['status' => 'pending', 'is_success' => false];
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/sail artisan test --filter=MidtransServiceTest`  
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add app/Services/MidtransService.php tests/Feature/MidtransServiceTest.php
git commit -m "feat(midtrans): implement MidtransService with snap token & signature verification"
```

---

### Task 3: Webhook Notification Handler (`MidtransNotificationController`)

**Files:**
- Create: `app/Http/Controllers/MidtransNotificationController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/MidtransWebhookTest.php`

**Interfaces:**
- Consumes: `POST /midtrans/notification`, `App\Services\MidtransService`, `App\Models\PaymentTransaction`, `App\Models\Booking`.
- Produces: HTTP 200 `['status' => 'ok']` pada notifikasi valid, HTTP 403 jika signature invalid.

- [ ] **Step 1: Write the failing tests for webhook notification**

```php
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

        $orderId = 'MT-DP-' . $booking->id . '-123456';
        PaymentTransaction::create([
            'booking_id' => $booking->id,
            'transaction_code' => $orderId,
            'payment_stage' => 'booking_fee',
            'payment_method' => 'bank_transfer',
            'amount' => 300000,
            'status' => 'pending',
        ]);

        $serverKey = 'SB-Mid-server-TEST12345';
        $signature = hash('sha512', $orderId . '200' . '300000.00' . $serverKey);

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
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/sail artisan test --filter=MidtransWebhookTest`  
Expected: FAIL with 404 Route Not Found.

- [ ] **Step 3: Implement `MidtransNotificationController` and register route**

Create `app/Http/Controllers/MidtransNotificationController.php`:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentTransaction;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransNotificationController extends Controller
{
    public function __construct(
        public readonly MidtransService $midtrans,
    ) {}

    /**
     * Menangani webhook HTTP notification resmi dari Midtrans.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');

        if (! $this->midtrans->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            return response()->json(['message' => 'Invalid signature key.'], 403);
        }

        $transaction = PaymentTransaction::where('transaction_code', $orderId)->first();

        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        $parsed = $this->midtrans->parseTransactionStatus($payload);

        DB::transaction(function () use ($transaction, $parsed, $payload): void {
            $transaction->update([
                'status' => $parsed['status'],
                'payment_method' => $payload['payment_type'] ?? $transaction->payment_method,
                'payment_payload' => $payload,
                'paid_at' => $parsed['is_success'] ? now() : $transaction->paid_at,
            ]);

            if ($parsed['is_success']) {
                $booking = Booking::where('id', $transaction->booking_id)->first();
                if ($booking) {
                    if ($transaction->payment_stage === 'booking_fee' && $booking->status === 'open') {
                        $booking->update(['status' => 'reserved']);
                    } elseif (in_array($transaction->payment_stage, ['settlement', 'full_payment'], true)) {
                        $booking->update(['status' => 'paid']);
                    }
                }
            }
        });

        return response()->json(['status' => 'ok']);
    }
}
```

Daftarkan route di `routes/web.php`:
```php
use App\Http\Controllers\MidtransNotificationController;

Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle'])->name('midtrans.notification');
```

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/sail artisan test --filter=MidtransWebhookTest`  
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/MidtransNotificationController.php routes/web.php tests/Feature/MidtransWebhookTest.php
git commit -m "feat(midtrans): add webhook notification handler with SHA-512 verification"
```

---

### Task 4: Refactor Checkout Endpoints for Snap (`CheckoutController`)

**Files:**
- Modify: `app/Http/Controllers/CheckoutController.php`
- Modify: `tests/Feature/CheckoutFlowTest.php`

**Interfaces:**
- Consumes: `MidtransService::createSnapToken()`, Form inputs from `step1`, `status`, `private`.
- Produces: JSON response `{ status: 'success', snap_token: string, redirect_url: string, order_id: string }` jika request AJAX/expectsJson, atau redirect fallback.

- [ ] **Step 1: Write the updated test expectations in CheckoutFlowTest**

Update `tests/Feature/CheckoutFlowTest.php` to simulate Midtrans Snap Token responses using `Http::fake()` and verify JSON response with `snap_token`.

- [ ] **Step 2: Run test to verify failure**

Run: `./vendor/bin/sail artisan test --filter=CheckoutFlowTest`  
Expected: FAIL because `CheckoutController` is still using hardcoded immediate status updates.

- [ ] **Step 3: Update `CheckoutController` to use `MidtransService`**

Inject `MidtransService` via constructor.
Update `payDp`:
- Validate participant details.
- Lock quota.
- Create Snap Token via `MidtransService::createSnapToken($booking, 'booking_fee', $booking->total_booking_fee)`.
- Record `PaymentTransaction` with status `pending`.
- If request expects JSON: return `response()->json(['status' => 'success', 'snap_token' => $snapData['token'], 'redirect_url' => $snapData['redirect_url']])`.

Update `settle`:
- Verify booking is `price_locked`.
- Create Snap Token for `$booking->remaining_payment_total`.
- Record `PaymentTransaction` with status `pending`.
- Return JSON with `snap_token`.

Update `payPrivate`:
- Validate participant details.
- Create Snap Token for `$booking->grand_total`.
- Record `PaymentTransaction` with status `pending`.
- Return JSON with `snap_token`.

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/sail artisan test --filter=CheckoutFlowTest`  
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/CheckoutController.php tests/Feature/CheckoutFlowTest.php
git commit -m "refactor(checkout): update payDp, settle, and payPrivate to generate Snap tokens"
```

---

### Task 5: Frontend Views & Snap.js Integration

**Files:**
- Modify: `resources/views/customer/checkout/step1_payment.blade.php`
- Modify: `resources/views/customer/checkout/step2_status.blade.php`
- Modify: `resources/views/customer/checkout/private_payment.blade.php`

**Interfaces:**
- Consumes: `<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>`.
- Produces: Interactive Snap Modal popup on button click, with callbacks `onSuccess`, `onPending`, `onError`, `onClose`.

- [ ] **Step 1: Update `step1_payment.blade.php`**
  - Add `snap.js` script tag in `<head>`.
  - Remove hardcoded radio buttons (`BCA Virtual Account`, `QRIS`).
  - Add Midtrans Multi-Channel payment badge (QRIS, GoPay, VA BCA/Mandiri/BNI/BRI, Kartu Kredit).
  - Add JavaScript form submit interceptor:
    - HTML5 validation check.
    - Submit via `fetch()` to `route('checkout.pay_dp', $booking->booking_code)`.
    - Trigger `window.snap.pay(token, callbacks)`.
    - `onSuccess` & `onPending`: redirect to `route('checkout.status', $booking->booking_code)`.

- [ ] **Step 2: Update `step2_status.blade.php`**
  - Add `snap.js` script tag in `<head>`.
  - Connect "Bayar Pelunasan Sekarang" to call `checkout.settle` via `fetch()` and open `window.snap.pay(token)`.
  - `onSuccess`: redirect to `route('checkout.success', $booking->booking_code)`.

- [ ] **Step 3: Update `private_payment.blade.php`**
  - Add `snap.js` script tag in `<head>`.
  - Remove hardcoded radio buttons (`BCA VA`, `Mandiri VA`, `QRIS`).
  - Add Midtrans Multi-Channel payment badge.
  - Connect submit button to `checkout.pay_private` via `fetch()` and open `window.snap.pay(token)`.
  - `onSuccess`: redirect to `route('checkout.success', $booking->booking_code)`.

- [ ] **Step 4: Verify blade templates compile and render without errors**

Run: `./vendor/bin/sail artisan test --filter=CheckoutFlowTest`  
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add resources/views/customer/checkout/step1_payment.blade.php resources/views/customer/checkout/step2_status.blade.php resources/views/customer/checkout/private_payment.blade.php
git commit -m "feat(checkout): integrate snap.js popup modal and modern midtrans badges"
```

---

### Task 6: Full Suite Verification & Code Quality

**Files:**
- Test all suites: `tests/`
- Format all files with Pint: `app/`, `config/`, `routes/`, `tests/`

- [ ] **Step 1: Run complete automated test suite**

Run: `./vendor/bin/sail test`  
Expected: All tests PASS with 0 failures.

- [ ] **Step 2: Run Laravel Pint for code formatting**

Run: `./vendor/bin/sail pint --dirty --format agent`  
Expected: Clean formatting matching Laravel standards.

- [ ] **Step 3: Commit any formatting adjustments**

```bash
git add .
git commit -m "style: apply Laravel Pint formatting to Midtrans integration"
```
