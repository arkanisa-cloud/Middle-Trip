# Detail Page & Booking Subsystem Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun seluruh arsitektur data, model Eloquent, seeder, service booking, integrasi Alpine.js pada halaman detail gunung dan modal pemesanan, 3-screen checkout flow (Open Trip & Private Trip), serta perintah Artisan otomatis untuk Price Lock.

**Architecture:** Menerapkan arsitektur service-oriented di Laravel 13 dengan `BookingService` untuk isolasi logika transaksi kuota, model Eloquent dengan casts dan accessor relasional, controller RESTful dengan Form Request validation, antarmuka Blade reaktif bertenaga Alpine.js, dan Artisan commands terjadwal untuk lifecycle otomatis batch.

**Tech Stack:** Laravel 13, PHP 8.5, MySQL 8.4, Laravel Sail, Tailwind CSS v4, Alpine.js, PHPUnit 12, Laravel Pint.

**Spec:** [`docs/PRD.md`](../PRD.md), [`docs/database.md`](../database.md), [`docs/logic.md`](../logic.md), [`docs/superpowers/specs/2026-09-27-detail-and-booking-design.md`](../specs/2026-09-27-detail-and-booking-design.md).

## Global Constraints
- Seluruh perintah artisan, composer, npm, dan test dijalankan via Laravel Sail (`./vendor/bin/sail ...`).
- PHP 8.5 rules: Constructor property promotion, explicit return types dan parameter type hinting pada semua method, kurung kurawal `{}` wajib pada semua control structure.
- Kode diformat dengan `./vendor/bin/sail pint --dirty --format agent` setiap kali mengubah file PHP.
- Jangan mengubah file yang sudah berjalan di `home.blade.php`, `shop.blade.php`, dan `HomeController.php`.

---

### Task 1: Database Migrations for Core Expedition & Booking Subsystem

**Files:**
- Create: `database/migrations/2026_09_27_000001_update_mountains_table_add_booking_fields.php`
- Create: `database/migrations/2026_09_27_000002_create_expedition_price_tiers_table.php`
- Create: `database/migrations/2026_09_27_000003_create_expeditions_table.php`
- Create: `database/migrations/2026_09_27_000004_create_meeting_points_table.php`
- Create: `database/migrations/2026_09_27_000005_create_addons_table.php`
- Create: `database/migrations/2026_09_27_000006_create_bookings_table.php`
- Create: `database/migrations/2026_09_27_000007_create_booking_participants_table.php`
- Create: `database/migrations/2026_09_27_000008_create_booking_addons_table.php`
- Create: `database/migrations/2026_09_27_000009_create_payment_transactions_table.php`
- Test: `tests/Feature/DatabaseSchemaTest.php`

**Interfaces:**
- Consumes: Existing `mountains`, `routes`, `users` tables.
- Produces: Tables `expedition_price_tiers`, `expeditions`, `meeting_points`, `addons`, `bookings`, `booking_participants`, `booking_addons`, `payment_transactions`, plus added columns on `mountains`.

- [ ] **Step 1: Write the failing test for database tables existence**

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_expedition_and_booking_tables_exist_with_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('mountains', [
            'booking_fee_per_pax',
            'price_lock_days_before_departure',
        ]));

        $this->assertTrue(Schema::hasTable('expedition_price_tiers'));
        $this->assertTrue(Schema::hasColumns('expedition_price_tiers', [
            'id', 'mountain_id', 'min_pax', 'max_pax', 'price_per_pax',
        ]));

        $this->assertTrue(Schema::hasTable('expeditions'));
        $this->assertTrue(Schema::hasColumns('expeditions', [
            'id', 'mountain_id', 'route_id', 'type', 'hiking_type',
            'departure_date', 'return_date', 'quota_max', 'quota_booked', 'current_locked_price', 'status',
        ]));

        $this->assertTrue(Schema::hasTable('meeting_points'));
        $this->assertTrue(Schema::hasColumns('meeting_points', [
            'id', 'mountain_id', 'name', 'additional_price_per_pax', 'is_default',
        ]));

        $this->assertTrue(Schema::hasTable('addons'));
        $this->assertTrue(Schema::hasColumns('addons', [
            'id', 'name', 'price', 'is_active',
        ]));

        $this->assertTrue(Schema::hasTable('bookings'));
        $this->assertTrue(Schema::hasColumns('bookings', [
            'id', 'booking_code', 'user_id', 'expedition_id', 'route_id', 'meeting_point_id',
            'customer_name', 'customer_email', 'customer_phone', 'customer_nik',
            'pax_count', 'booking_fee_per_pax', 'total_booking_fee', 'shuttle_fee_total',
            'addons_fee_total', 'locked_price_per_pax', 'remaining_payment_total', 'grand_total',
            'status', 'payment_deadline',
        ]));

        $this->assertTrue(Schema::hasTable('booking_participants'));
        $this->assertTrue(Schema::hasColumns('booking_participants', [
            'id', 'booking_id', 'full_name', 'nik', 'is_leader',
        ]));

        $this->assertTrue(Schema::hasTable('booking_addons'));
        $this->assertTrue(Schema::hasTable('payment_transactions'));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/sail artisan test --filter=DatabaseSchemaTest`  
Expected: FAIL with missing tables or columns.

- [ ] **Step 3: Create the 9 migration files matching `docs/database.md`**

1. `2026_09_27_000001_update_mountains_table_add_booking_fields.php`:
   Add `booking_fee_per_pax` (unsignedInteger, default 150000) and `price_lock_days_before_departure` (unsignedTinyInteger, default 3).
2. `2026_09_27_000002_create_expedition_price_tiers_table.php`:
   Columns `mountain_id` (foreignId cascade), `min_pax`, `max_pax`, `price_per_pax`.
3. `2026_09_27_000003_create_expeditions_table.php`:
   Columns `mountain_id`, `route_id`, `type` (`open`, `private`), `hiking_type` (`camping`, `tektok`), `departure_date`, `return_date`, `quota_max`, `quota_booked` (default 0), `current_locked_price` (nullable), `status` (`open`, `price_locked`, `completed`, `cancelled`).
4. `2026_09_27_000004_create_meeting_points_table.php`:
   Columns `mountain_id`, `name`, `location_type` (default `basecamp`), `additional_price_per_pax` (default 0), `is_default` (default false).
5. `2026_09_27_000005_create_addons_table.php`:
   Columns `name`, `category` (default `gear`), `price`, `is_active` (default true).
6. `2026_09_27_000006_create_bookings_table.php`:
   Columns `booking_code` (unique), `user_id` (nullable), `expedition_id`, `route_id`, `meeting_point_id` (nullable), `trip_type`, `customer_name`, `customer_email`, `customer_phone`, `customer_nik`, `pax_count`, `booking_fee_per_pax`, `total_booking_fee`, `shuttle_fee_total`, `addons_fee_total`, `locked_price_per_pax` (nullable), `remaining_payment_total` (nullable), `grand_total`, `status` (`open`, `reserved`, `price_locked`, `paid`, `expired`, `cancelled`), `payment_deadline` (nullable), `notes` (nullable).
7. `2026_09_27_000007_create_booking_participants_table.php`:
   Columns `booking_id`, `full_name`, `nik`, `gender` (nullable), `is_leader` (default false).
8. `2026_09_27_000008_create_booking_addons_table.php`:
   Columns `booking_id`, `addon_id`, `price`, `quantity` (default 1).
9. `2026_09_27_000009_create_payment_transactions_table.php`:
   Columns `booking_id`, `transaction_code` (unique), `payment_stage`, `payment_method`, `amount`, `status`, `paid_at` (nullable), `payment_payload` (nullable, json).

- [ ] **Step 4: Run migration and verify test passes**

Run: `./vendor/bin/sail artisan migrate`  
Run: `./vendor/bin/sail artisan test --filter=DatabaseSchemaTest`  
Expected: PASS

- [ ] **Step 5: Format code with Laravel Pint**

Run: `./vendor/bin/sail pint --dirty --format agent`

---

### Task 2: Eloquent Models, Relationships, and Dynamic Price Tier Logic

**Files:**
- Create: `app/Models/ExpeditionPriceTier.php`
- Create: `app/Models/Expedition.php`
- Create: `app/Models/MeetingPoint.php`
- Create: `app/Models/Addon.php`
- Create: `app/Models/Booking.php`
- Create: `app/Models/BookingParticipant.php`
- Create: `app/Models/PaymentTransaction.php`
- Modify: `app/Models/Mountain.php`
- Modify: `app/Models/Route.php`
- Test: `tests/Unit/ExpeditionPriceTierTest.php`
- Test: `tests/Unit/BookingModelTest.php`

**Interfaces:**
- Consumes: Tables created in Task 1.
- Produces:
  - `Mountain::priceTiers()`, `Mountain::expeditions()`, `Mountain::meetingPoints()`, `Mountain::getTierPriceForPax(int $pax): int`
  - `Expedition::mountain()`, `Expedition::route()`, `Expedition::bookings()`, `Expedition::calculatePriceLockPrice(): int`
  - `Booking::expedition()`, `Booking::participants()`, `Booking::addons()`, `Booking::transactions()`

- [ ] **Step 1: Write the failing tests for models and dynamic pricing logic**

```php
<?php

namespace Tests\Unit;

use App\Models\Expedition;
use App\Models\ExpeditionPriceTier;
use App\Models\Mountain;
use App\Models\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionPriceTierTest extends TestCase
{
    use RefreshDatabase;

    public function test_mountain_returns_correct_tier_price_for_pax_count(): void
    {
        $mountain = Mountain::create([
            'name' => 'Mt. Merbabu',
            'slug' => 'mt-merbabu',
            'elevation' => 3142,
            'province' => 'Jawa Tengah',
            'cover_image' => 'https://example.com/merbabu.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 150000,
            'price_lock_days_before_departure' => 3,
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

        $this->assertEquals(650000, $mountain->getTierPriceForPax(2));
        $this->assertEquals(550000, $mountain->getTierPriceForPax(5));
        $this->assertEquals(475000, $mountain->getTierPriceForPax(8));
        // Fallback boundary
        $this->assertEquals(475000, $mountain->getTierPriceForPax(12));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/sail artisan test --filter=ExpeditionPriceTierTest`  
Expected: FAIL with "Class ExpeditionPriceTier not found"

- [ ] **Step 3: Implement all Eloquent Models and update `Mountain.php` & `Route.php`**

Implement:
- `Mountain::getTierPriceForPax(int $pax): int`: queries `priceTiers` for matching range and falls back to lowest price.
- `Expedition::calculatePriceLockPrice(): int`: calculates final per pax price based on `$this->quota_booked` via `mountain->getTierPriceForPax()`.
- Add all required casts and relationship methods (`hasMany`, `belongsTo`, `belongsToMany`).

- [ ] **Step 4: Run unit tests to verify they pass**

Run: `./vendor/bin/sail artisan test --filter=ExpeditionPriceTierTest`  
Expected: PASS

- [ ] **Step 5: Format code with Laravel Pint**

Run: `./vendor/bin/sail pint --dirty --format agent`

---

### Task 3: Comprehensive Seeder for Real Mt. Merbabu, Sindoro, and Prau

**Files:**
- Create: `database/seeders/ExpeditionSubsystemSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Test: `tests/Feature/ExpeditionSeederTest.php`

**Interfaces:**
- Consumes: Models from Task 2.
- Produces: Complete working dataset for Mt. Merbabu (Via Selo, Suwanting, Thekelan, Wekas), price tiers (1-3: 650k, 4-6: 550k, 7-10: 475k), meeting points (Basecamp Free, Shuttle Solo +75k, Shuttle Jogja +100k), rental gear addons, and upcoming scheduled expeditions.

- [ ] **Step 1: Write test for Seeder integrity**

```php
<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Expedition;
use App\Models\MeetingPoint;
use App\Models\Mountain;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_expedition_subsystem_seeder_populates_full_dataset(): void
    {
        $this->seed(ExpeditionSubsystemSeeder::class);

        $merbabu = Mountain::where('slug', 'mt-merbabu')->first();
        $this->assertNotNull($merbabu);
        $this->assertEquals(150000, $merbabu->booking_fee_per_pax);
        $this->assertCount(3, $merbabu->priceTiers);
        $this->assertGreaterThanOrEqual(1, $merbabu->meetingPoints()->count());
        $this->assertGreaterThanOrEqual(1, $merbabu->expeditions()->count());

        $this->assertGreaterThanOrEqual(4, Addon::count());
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/sail artisan test --filter=ExpeditionSeederTest`  
Expected: FAIL with "Target class [ExpeditionSubsystemSeeder] does not exist".

- [ ] **Step 3: Implement `ExpeditionSubsystemSeeder.php`**

Populate:
- Mt. Merbabu, Mt. Sindoro, Mt. Prau.
- Price tiers per mountain.
- Addons: *Hydropack* (15.000), *Trekking Pole* (10.000), *Matras Gulung Tambahan* (12.000), *Headlamp* (10.000).
- Meeting points: *Basecamp Selo (Boyolali)* (0), *Shuttle Stasiun Solo Balapan* (75.000), *Shuttle Stasiun Tugu Jogja* (100.000).
- Open Trip and Private Trip batches with realistic future dates (`departure_date = now()->addDays(14)`).

- [ ] **Step 4: Run seeder and verify test passes**

Run: `./vendor/bin/sail artisan test --filter=ExpeditionSeederTest`  
Expected: PASS

- [ ] **Step 5: Format code with Laravel Pint**

Run: `./vendor/bin/sail pint --dirty --format agent`

---

### Task 4: Booking Engine Service, Form Request Validation & Booking Controller

**Files:**
- Create: `app/Services/BookingService.php`
- Create: `app/Http/Requests/StoreBookingRequest.php`
- Create: `app/Http/Controllers/BookingController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/BookingCreationTest.php`

**Interfaces:**
- Consumes: Models from Task 2, `StoreBookingRequest`.
- Produces:
  - `BookingService::createBooking(array $validatedData): Booking`
  - Endpoint `POST /bookings` returning `{ success: true, booking_code: "MT-...", redirect_url: "/checkout/MT-..." }`.

- [ ] **Step 1: Write feature test for booking creation**

```php
<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Expedition;
use App\Models\MeetingPoint;
use App\Models\Mountain;
use App\Models\Route;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_user_can_create_open_trip_booking_with_mandatory_nik(): void
    {
        $expedition = Expedition::where('type', 'open')->first();
        $meetingPoint = MeetingPoint::where('mountain_id', $expedition->mountain_id)->first();
        $addon = Addon::first();

        $payload = [
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'meeting_point_id' => $meetingPoint->id,
            'customer_name' => 'Arkan Isa Alvaro',
            'customer_email' => 'arkan@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'participants' => [
                [
                    'full_name' => 'Arkan Isa Alvaro',
                    'nik' => '3301234567890001',
                    'is_leader' => true,
                ],
                [
                    'full_name' => 'Budi Santoso',
                    'nik' => '3301234567890002',
                    'is_leader' => false,
                ],
            ],
            'addons' => [
                ['id' => $addon->id, 'quantity' => 1],
            ],
        ];

        $response = $this->postJson(route('bookings.store'), $payload);

        $response->assertCreated();
        $response->assertJsonStructure(['success', 'booking_code', 'redirect_url']);

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Arkan Isa Alvaro',
            'pax_count' => 2,
            'total_booking_fee' => 2 * $expedition->mountain->booking_fee_per_pax,
            'status' => 'open',
        ]);

        $this->assertDatabaseCount('booking_participants', 2);
    }

    public function test_booking_fails_if_pax_count_exceeds_available_quota(): void
    {
        $expedition = Expedition::where('type', 'open')->first();
        $expedition->update(['quota_max' => 5, 'quota_booked' => 5]);

        $payload = [
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'customer_name' => 'Arkan',
            'customer_email' => 'arkan@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'participants' => [
                ['full_name' => 'Arkan', 'nik' => '3301234567890001', 'is_leader' => true],
            ],
        ];

        $response = $this->postJson(route('bookings.store'), $payload);
        $response->assertStatus(422);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/sail artisan test --filter=BookingCreationTest`  
Expected: FAIL with "Route [bookings.store] not defined".

- [ ] **Step 3: Implement `StoreBookingRequest`, `BookingService`, and `BookingController`**

1. `StoreBookingRequest.php`: Validate NIK 16 digits, email, phone, participant array count equals `pax_count`.
2. `BookingService.php`:
   - Generate unique code: `MT-` . date('Ymd') . '-' . strtoupper(Str::random(5)).
   - Wrapped in `DB::transaction()` with `lockForUpdate()` on `expeditions`.
   - Calculate `total_booking_fee = pax_count * mountain.booking_fee_per_pax`.
   - Calculate preliminary `shuttle_fee_total` and `addons_fee_total`.
   - Insert booking and participants, increment `expedition->quota_booked`.
3. `BookingController.php`:
   - `store(StoreBookingRequest $request)` returns JSON `{ success: true, booking_code, redirect_url }`.
4. `routes/web.php`:
   - Add `Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');`

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/sail artisan test --filter=BookingCreationTest`  
Expected: PASS

- [ ] **Step 5: Format code with Laravel Pint**

Run: `./vendor/bin/sail pint --dirty --format agent`

---

### Task 5: Detail Page & Alpine.js Interactive Booking Modal Integration

**Files:**
- Modify: `app/Http/Controllers/ExpeditionController.php`
- Modify: `resources/views/customer/detail.blade.php`
- Test: `tests/Feature/ExpeditionDetailViewTest.php`

**Interfaces:**
- Consumes: Models from Task 2, `ExpeditionController@show`.
- Produces: Dynamic interactive detail page passing real mountain and active expedition data into Alpine.js component (`bookingModalComponent`).

- [ ] **Step 1: Write test for Detail Page rendering real database data**

```php
<?php

namespace Tests\Feature;

use App\Models\Mountain;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionDetailViewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_detail_page_loads_with_real_mountain_and_booking_modal(): void
    {
        $response = $this->get(route('ekspedisi.show', 'mt-merbabu'));

        $response->assertOk();
        $response->assertSee('Mt. Merbabu');
        $response->assertSee('Booking Sekarang');
        $response->assertSee('x-data="bookingModalComponent', false);
        $response->assertSee('paxCountDisplay', false);
    }
}
```

- [ ] **Step 2: Run test to verify current state**

Run: `./vendor/bin/sail artisan test --filter=ExpeditionDetailViewTest`

- [ ] **Step 3: Update `ExpeditionController@show` and integrate Alpine.js in `detail.blade.php`**

1. In `ExpeditionController@show`:
   Fetch real Eloquent `Mountain::with(['routes', 'priceTiers', 'meetingPoints', 'expeditions'])->where('slug', $slug)->firstOrFail()`.
2. In `resources/views/customer/detail.blade.php`:
   - Replace static script with clean `x-data="bookingModalComponent({ ... })"`.
   - Dynamically render meeting points from `$mountain->meetingPoints`.
   - Dynamically render addons from `Addon::active()->get()`.
   - Dynamic participant fields generator (Ketua + Anggota NIK input fields).
   - Submit form asynchronously via `fetch('/bookings')` and redirect to checkout page upon success.

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/sail artisan test --filter=ExpeditionDetailViewTest`  
Expected: PASS

- [ ] **Step 5: Format code with Laravel Pint**

Run: `./vendor/bin/sail pint --dirty --format agent`

---

### Task 6: 3-Screen Checkout Workflow for Open Trip & Private Trip

**Files:**
- Create: `app/Http/Controllers/CheckoutController.php`
- Create: `resources/views/customer/checkout/step1_payment.blade.php`
- Create: `resources/views/customer/checkout/step2_status.blade.php`
- Create: `resources/views/customer/checkout/step3_success.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/CheckoutFlowTest.php`

**Interfaces:**
- Consumes: Existing HTML prototypes `payment_open_trip_1.html`, `payment_open_trip_2.html`, `payment_open_trip_3.html`.
- Produces:
  - `GET /checkout/{booking_code}` (Step 1: Bayar DP)
  - `POST /checkout/{booking_code}/pay-dp` (Simulate/process DP payment -> status `reserved`)
  - `GET /checkout/{booking_code}/status` (Step 2: Menunggu / Pelunasan Price Lock)
  - `POST /checkout/{booking_code}/settle` (Process pelunasan -> status `paid`)
  - `GET /checkout/{booking_code}/success` (Step 3: Selesai & Gabung Grup WA)

- [ ] **Step 1: Write feature test for complete 3-step checkout flow**

```php
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
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/sail artisan test --filter=CheckoutFlowTest`  
Expected: FAIL with missing routes.

- [ ] **Step 3: Implement `CheckoutController` and the 3 Blade Views**

Port pixel-perfect markup from `payment_open_trip_1.html`, `payment_open_trip_2.html`, and `payment_open_trip_3.html` into clean Blade views utilizing Tailwind CSS v4 tokens and layouts.
Add payment processing simulation handling transaction record insertion and status transition.

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/sail artisan test --filter=CheckoutFlowTest`  
Expected: PASS

- [ ] **Step 5: Format code with Laravel Pint**

Run: `./vendor/bin/sail pint --dirty --format agent`

---

### Task 7: Automated Price Lock & Expiration Scheduled Commands

**Files:**
- Create: `app/Console/Commands/ProcessPriceLocksCommand.php`
- Create: `app/Console/Commands/ExpireUnpaidBookingsCommand.php`
- Modify: `routes/console.php`
- Test: `tests/Feature/ProcessPriceLocksCommandTest.php`

**Interfaces:**
- Consumes: `Expedition`, `Booking`, `Mountain`, `ExpeditionPriceTier`.
- Produces:
  - Artisan command `php artisan expeditions:process-price-locks`
  - Artisan command `php artisan bookings:expire-unpaid`

- [ ] **Step 1: Write feature test for Price Lock command**

```php
<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessPriceLocksCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_price_lock_command_locks_price_and_calculates_remaining_balance(): void
    {
        $mountain = Mountain::where('slug', 'mt-merbabu')->first();
        // Set departure in 2 days (<= 3 days threshold)
        $expedition = Expedition::where('mountain_id', $mountain->id)->first();
        $expedition->update([
            'departure_date' => now()->addDays(2)->toDateString(),
            'status' => 'open',
            'quota_booked' => 6,
        ]);

        $booking = Booking::create([
            'booking_code' => 'MT-LOCK-TEST',
            'expedition_id' => $expedition->id,
            'route_id' => $expedition->route_id,
            'trip_type' => 'open',
            'customer_name' => 'Arkan',
            'customer_email' => 'arkan@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 300000,
            'shuttle_fee_total' => 0,
            'addons_fee_total' => 0,
            'grand_total' => 1000000,
            'status' => 'reserved',
        ]);

        $this->artisan('expeditions:process-price-locks')->assertSuccessful();

        $this->assertEquals('price_locked', $expedition->fresh()->status);
        $this->assertEquals(550000, $expedition->fresh()->current_locked_price); // 6 pax = Tier 2 (550k)

        $freshBooking = $booking->fresh();
        $this->assertEquals('price_locked', $freshBooking->status);
        $this->assertEquals(550000, $freshBooking->locked_price_per_pax);
        // Remaining = (2 * 550k) - 300k DP = 800k
        $this->assertEquals(800000, $freshBooking->remaining_payment_total);
        $this->assertNotNull($freshBooking->payment_deadline);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/sail artisan test --filter=ProcessPriceLocksCommandTest`  
Expected: FAIL with "Command expeditions:process-price-locks is not defined".

- [ ] **Step 3: Implement `ProcessPriceLocksCommand` and `ExpireUnpaidBookingsCommand`**

1. `ProcessPriceLocksCommand.php`:
   - Find all `expeditions` with `status = 'open'` where `departure_date - mountain.price_lock_days_before_departure <= today()`.
   - Calculate tier price via `mountain->getTierPriceForPax($expedition->quota_booked)`.
   - Update `expeditions.status = 'price_locked'` and `current_locked_price`.
   - Loop bookings with `status = 'reserved'`: update `locked_price_per_pax`, compute `remaining_payment_total`, set `payment_deadline = now()->addHours(48)`, and set status to `price_locked`.
2. `ExpireUnpaidBookingsCommand.php`:
   - Find bookings with `status = 'price_locked'` where `payment_deadline < now()`.
   - Update `status = 'expired'`, decrement `expedition->quota_booked` by `booking->pax_count`.
3. Register schedule in `routes/console.php`.

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/sail artisan test --filter=ProcessPriceLocksCommandTest`  
Expected: PASS

- [ ] **Step 5: Run full test suite & Pint formatting**

Run: `./vendor/bin/sail test`  
Run: `./vendor/bin/sail pint --dirty --format agent`  
Expected: All tests pass, zero lint issues.
