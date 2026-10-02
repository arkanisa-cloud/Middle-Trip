# Admin Panel System Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the complete MiddleTrip Admin Panel subsystem with admin authentication/authorization, an Alpine Forest & Terracotta layout adapting Spark Admin (`docs/referensi-interface/ui-admin`), and end-to-end management for Mountains, Routes, Dynamic Price Tiers, Meeting Points, Add-ons, Expedition Batches, and Bookings/SIMAKSI Manifes.

**Architecture:** A dedicated `/admin` route group protected by `auth` and `admin` middleware. Controllers organized under `App\Http\Controllers\Admin\*` interacting with existing Eloquent models (`Mountain`, `Route`, `ExpeditionPriceTier`, `Expedition`, `MeetingPoint`, `Addon`, `Booking`, `BookingParticipant`, `PaymentTransaction`). Layout rendered via Blade templates extending `layouts.admin` with Tailwind CSS v4 design tokens from `DESIGN.md`.

**Tech Stack:** Laravel 13, PHP 8.5, Tailwind CSS v4, Alpine.js, Blade components, Pest/PHPUnit, Laravel Breeze.

**Spec:** [`docs/PRD.md`](../PRD.md), [`docs/database.md`](../database.md), [`docs/logic.md`](../logic.md), [`docs/DESIGN.md`](../DESIGN.md), and UI reference [`docs/referensi-interface/ui-admin/`](../referensi-interface/ui-admin/).

## Global Constraints

- **Design System**: Typography "Plus Jakarta Sans", Terracotta primary `#9E3924` (hover `#862F1D`), Alpine Forest dark sidebar `#071A16` (card `#0D2721`, border `#153A32`), Stone canvas `#F8F9FA`, chromatic grade pills (Grade A `#EAF5EF`/`#226848`, Grade B `#FFF0E6`/`#B85320`, Grade C `#FDECEB`/`#B92F26`), buttons `rounded-full`.
- **PHP Conventions**: PHP 8.5, constructor property promotion, explicit return types and parameter types, curly braces for all control structures, array shapes in PHPDoc where appropriate.
- **Code Quality**: Run `vendor/bin/pint --dirty --format agent` on modified PHP files, all tests must pass with `./vendor/bin/sail artisan test --compact` or `php artisan test --compact`.
- **Surgical Changes**: Do not break existing public/customer routes (`/`, `/ekspedisi`, `/checkout/*`).

---

### Task 1: Admin Authorization & Middleware

**Files:**
- Create: `database/migrations/2026_09_28_000001_add_is_admin_to_users_table.php`
- Modify: `app/Models/User.php`
- Create: `app/Http/Middleware/EnsureUserIsAdmin.php`
- Modify: `bootstrap/app.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Test: `tests/Feature/AdminAuthTest.php`

**Interfaces:**
- Consumes: `Illuminate\Foundation\Auth\User`, `Illuminate\Http\Request`
- Produces: `User::$is_admin` (boolean), route middleware alias `'admin'` mapped to `EnsureUserIsAdmin::class`

- [ ] **Step 1: Write the failing test for Admin Authorization**

Create `tests/Feature/AdminAuthTest.php`:
```php
<?php

use App\Models\User;

test('guest cannot access admin routes and is redirected to login', function () {
    $response = $this->get('/admin');
    $response->assertRedirect('/login');
});

test('non-admin user cannot access admin routes and receives 403', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->get('/admin');
    $response->assertForbidden();
});

test('admin user can access admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get('/admin');
    $response->assertOk();
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/AdminAuthTest.php --compact`
Expected: FAIL (route /admin does not exist or columns not found).

- [ ] **Step 3: Create migration and update User model**

Create `database/migrations/2026_09_28_000001_add_is_admin_to_users_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
```

Run migration: `php artisan migrate`

Update `app/Models/User.php`:
Add `'is_admin'` to `#[Fillable([...])]` and cast `'is_admin' => 'boolean'` in `casts()`. Add helper method `public function isAdmin(): bool { return (bool) $this->is_admin; }`.

- [ ] **Step 4: Create Middleware and register in bootstrap/app.php**

Create `app/Http/Middleware/EnsureUserIsAdmin.php`:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->is_admin) {
            abort(403, 'Akses khusus administrator MiddleTrip.');
        }

        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:
```php
$middleware->alias([
    'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
]);
```

- [ ] **Step 5: Register initial `/admin` route & update seeders**

In `routes/web.php`:
```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});
```

Update `database/seeders/DatabaseSeeder.php` to seed:
```php
User::factory()->create([
    'name' => 'MiddleTrip Administrator',
    'email' => 'admin@middletrip.id',
    'is_admin' => true,
]);
```

- [ ] **Step 6: Run test to verify it passes**

Run: `php artisan test tests/Feature/AdminAuthTest.php --compact`
Expected: PASS (3 tests passed).

---

### Task 2: Master Layout Blade Admin & Reusable UI Components

**Files:**
- Create: `resources/views/layouts/admin.blade.php`
- Create: `resources/views/components/admin/sidebar.blade.php`
- Create: `resources/views/components/admin/topbar.blade.php`
- Create: `resources/views/components/admin/stat-card.blade.php`
- Create: `resources/views/components/admin/grade-badge.blade.php`
- Create: `resources/views/components/admin/status-badge.blade.php`

**Interfaces:**
- Consumes: Auth user data, current route name for active links, MiddleTrip design tokens (`bg-surface-forest`, `bg-canvas`, `text-primary`).
- Produces: Base layout with `@yield('content')`, `@yield('title')`, sidebar navigation, breadcrumbs, responsive drawer toggle.

- [ ] **Step 1: Create reusable badge & stat components**

Create:
- `resources/views/components/admin/grade-badge.blade.php`: renders chromatic difficulty badges (Grade A Emerald, Grade B Amber, Grade C Rose).
- `resources/views/components/admin/status-badge.blade.php`: renders booking & expedition status pills (`open`, `reserved`, `price_locked`, `paid`, `expired`, `completed`, `cancelled`).
- `resources/views/components/admin/stat-card.blade.php`: card with icon, title, value, change indicator, and clean border matching `ui-admin`.

- [ ] **Step 2: Create Admin Sidebar & Topbar**

Create `resources/views/components/admin/sidebar.blade.php`:
- Sidebar with `bg-surface-forest` (`#071A16`), subtle border `border-surface-forest-border` (`#153A32`), topo pattern subtle overlay.
- MiddleTrip brand badge with logo & "MiddleTrip Admin".
- Navigation groups:
  - **MENU**: Dashboard (`admin.dashboard`)
  - **MASTER DATA**: Gunung & Jalur (`admin.mountains.index`), Titik Kumpul Shuttle (`admin.meeting-points.index`), Add-ons Sewa (`admin.addons.index`)
  - **EKSPEDISI**: Jadwal Batch Trip (`admin.expeditions.index`)
  - **TRANSAKSI**: Reservasi & SIMAKSI (`admin.bookings.index`)
- Profile snippet at bottom with avatar, name, email, and logout button.

Create `resources/views/components/admin/topbar.blade.php`:
- Clean white navbar with breadcrumbs, link to "Lihat Web" (`route('home')`), search bar, notifications, and profile dropdown.

- [ ] **Step 3: Create Master Layout `resources/views/layouts/admin.blade.php`**

Combine Sidebar, Topbar, Flash notification alerts (success/error messages), and `@yield('content')` wrapped in stone canvas container (`bg-canvas font-sans text-ink min-h-screen`). Include `@vite(['resources/css/app.css', 'resources/js/app.js'])`.

- [ ] **Step 4: Verify visually and test layout compilation**

Ensure `resources/views/admin/dashboard.blade.php` extends `layouts.admin`.
Run test to verify rendering: `php artisan test tests/Feature/AdminAuthTest.php --compact`.

---

### Task 3: Admin Dashboard Controller & Real-Time Metrics

**Files:**
- Create: `app/Http/Controllers/Admin/DashboardController.php`
- Modify: `routes/web.php`
- Modify: `resources/views/admin/dashboard.blade.php`
- Test: `tests/Feature/AdminDashboardTest.php`

**Interfaces:**
- Consumes: Models `Mountain`, `Expedition`, `Booking`, `PaymentTransaction`
- Produces: Dashboard summary metrics array (`total_mountains`, `active_expeditions`, `total_bookings`, `total_revenue`, `recent_bookings`, `upcoming_price_locks`).

- [ ] **Step 1: Write test for Admin Dashboard metrics**

Create `tests/Feature/AdminDashboardTest.php`:
```php
<?php

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\User;

test('admin dashboard renders metrics and recent bookings', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    Mountain::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertViewIs('admin.dashboard');
    $response->assertViewHas(['metrics', 'recentBookings', 'upcomingPriceLocks']);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/AdminDashboardTest.php --compact`
Expected: FAIL.

- [ ] **Step 3: Implement DashboardController**

Create `app/Http/Controllers/Admin/DashboardController.php`:
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\PaymentTransaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'total_mountains' => Mountain::count(),
            'active_expeditions' => Expedition::whereIn('status', ['open', 'price_locked'])->count(),
            'total_bookings' => Booking::count(),
            'pending_settlement' => Booking::where('status', 'price_locked')->count(),
            'total_revenue' => (int) PaymentTransaction::where('status', 'success')->sum('amount'),
        ];

        $recentBookings = Booking::with(['expedition.mountain', 'route'])
            ->latest()
            ->take(6)
            ->get();

        $upcomingPriceLocks = Expedition::with(['mountain', 'route'])
            ->where('status', 'open')
            ->whereDate('departure_date', '>=', now())
            ->orderBy('departure_date', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('metrics', 'recentBookings', 'upcomingPriceLocks'));
    }
}
```

Update `routes/web.php` to route `/admin` to `[DashboardController::class, 'index']`.

- [ ] **Step 4: Design `resources/views/admin/dashboard.blade.php`**

Render:
1. 4 Metric Cards at top (Total Gunung, Ekspedisi Aktif, Total Booking, Estimasi Pendapatan).
2. Recent Bookings Table with customer name, mountain/route, pax, total price, and status badge.
3. Upcoming Price Lock Batches card with quota progress bar (`quota_booked / quota_max`), departure date, and days remaining.
4. Quick Action buttons (Tambah Gunung Baru, Buat Jadwal Ekspedisi, Cek Manifes).

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/AdminDashboardTest.php --compact`
Expected: PASS.

---

### Task 4: Master Data Gunung (`mountains`) & Jalur (`routes`) CRUD

**Files:**
- Create: `app/Http/Controllers/Admin/MountainController.php`
- Create: `app/Http/Requests/Admin/StoreMountainRequest.php`
- Create: `app/Http/Requests/Admin/UpdateMountainRequest.php`
- Create: `resources/views/admin/mountains/index.blade.php`
- Create: `resources/views/admin/mountains/create.blade.php`
- Create: `resources/views/admin/mountains/edit.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/AdminMountainCrudTest.php`

**Interfaces:**
- Consumes: `Mountain`, `Route`, `ExpeditionPriceTier` models
- Produces: Full CRUD interface with inline route creation (Via & Grade A/B/C) and Price Tier configurations.

- [ ] **Step 1: Write test for Mountain CRUD**

Create `tests/Feature/AdminMountainCrudTest.php`:
```php
<?php

use App\Models\Mountain;
use App\Models\User;

test('admin can see mountain list', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $mountain = Mountain::factory()->create(['name' => 'Gunung Rinjani']);

    $response = $this->actingAs($admin)->get(route('admin.mountains.index'));
    $response->assertOk();
    $response->assertSee('Gunung Rinjani');
});

test('admin can store new mountain with routes and price tiers', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $data = [
        'name' => 'Gunung Slamet',
        'elevation' => 3428,
        'province' => 'Jawa Tengah',
        'cover_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b',
        'description' => 'Gunung tertinggi kedua di Pulau Jawa.',
        'base_price' => 550000,
        'price_private' => 1200000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
        'has_open_trip' => true,
        'has_private_trip' => true,
        'is_featured' => true,
        'is_active' => true,
        'routes' => [
            ['name' => 'Via Bambangan', 'grade' => 'Grade B', 'is_primary' => true, 'distance_km' => 14.5, 'duration_hours' => '8-10 Jam'],
        ],
        'price_tiers' => [
            ['min_pax' => 1, 'max_pax' => 3, 'price_per_pax' => 650000],
            ['min_pax' => 4, 'max_pax' => 6, 'price_per_pax' => 550000],
            ['min_pax' => 7, 'max_pax' => 10, 'price_per_pax' => 480000],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $data);
    $response->assertRedirect(route('admin.mountains.index'));

    $this->assertDatabaseHas('mountains', ['name' => 'Gunung Slamet', 'elevation' => 3428]);
    $this->assertDatabaseHas('routes', ['name' => 'Via Bambangan', 'grade' => 'Grade B']);
    $this->assertDatabaseHas('expedition_price_tiers', ['min_pax' => 1, 'price_per_pax' => 650000]);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/AdminMountainCrudTest.php --compact`
Expected: FAIL.

- [ ] **Step 3: Implement MountainController and Form Requests**

Create `app/Http/Controllers/Admin/MountainController.php`:
- `index()`: list mountains with search & pagination, route count, active badge.
- `create()`: form to input mountain details, dynamic repeater for routes (Via, Grade, Distance) and dynamic price tiers (min pax, max pax, price).
- `store()`: transactional creation of Mountain + Routes + Price Tiers.
- `edit()`: load mountain with relationships.
- `update()`: sync updates.
- `destroy()`: soft or cascade deletion with safety check.

- [ ] **Step 4: Build Mountain Views**

Create:
- `resources/views/admin/mountains/index.blade.php`: Table with mountain cover thumbnail, name, elevation, province, active routes with Grade badges, and actions (Edit / Delete).
- `resources/views/admin/mountains/create.blade.php`: Styled form with card sections (Informasi Umum, Konfigurasi Booking Fee & Price Lock, Jalur Pendakian Repeater, Matriks Harga Bertingkat Repeater).
- `resources/views/admin/mountains/edit.blade.php`: Edit view with prefilled data.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/AdminMountainCrudTest.php --compact`
Expected: PASS.

---

### Task 5: Master Data Pendukung (Meeting Points & Add-ons)

**Files:**
- Create: `app/Http/Controllers/Admin/MeetingPointController.php`
- Create: `app/Http/Controllers/Admin/AddonController.php`
- Create: `resources/views/admin/meeting-points/index.blade.php`
- Create: `resources/views/admin/addons/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/AdminMasterSupportTest.php`

**Interfaces:**
- Consumes: `MeetingPoint`, `Addon`, `Mountain`
- Produces: CRUD endpoints for shuttle pickup spots and gear rental add-ons.

- [ ] **Step 1: Write test for Meeting Points and Addons**

Create `tests/Feature/AdminMasterSupportTest.php`:
```php
<?php

use App\Models\Addon;
use App\Models\MeetingPoint;
use App\Models\Mountain;
use App\Models\User;

test('admin can manage meeting points for a mountain', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $mountain = Mountain::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.meeting-points.store'), [
        'mountain_id' => $mountain->id,
        'name' => 'Stasiun Solo Balapan',
        'location_type' => 'station',
        'additional_price_per_pax' => 75000,
        'is_default' => false,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('meeting_points', ['name' => 'Stasiun Solo Balapan']);
});

test('admin can manage addons gear catalog', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.addons.store'), [
        'name' => 'Trekking Pole Carbon',
        'category' => 'gear',
        'price' => 35000,
        'is_active' => true,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('addons', ['name' => 'Trekking Pole Carbon', 'price' => 35000]);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/AdminMasterSupportTest.php --compact`
Expected: FAIL.

- [ ] **Step 3: Implement MeetingPointController and AddonController**

Implement controllers with CRUD operations and flash status messages.

- [ ] **Step 4: Create Views**

Create:
- `resources/views/admin/meeting-points/index.blade.php`: Table grouped or filterable by mountain with shuttle surcharge.
- `resources/views/admin/addons/index.blade.php`: Grid/table of rental gear with active status toggle.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/AdminMasterSupportTest.php --compact`
Expected: PASS.

---

### Task 6: Manajemen Batch Jadwal Ekspedisi (`expeditions`)

**Files:**
- Create: `app/Http/Controllers/Admin/ExpeditionScheduleController.php`
- Create: `resources/views/admin/expeditions/index.blade.php`
- Create: `resources/views/admin/expeditions/create.blade.php`
- Create: `resources/views/admin/expeditions/edit.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/AdminExpeditionScheduleTest.php`

**Interfaces:**
- Consumes: `Expedition`, `Mountain`, `Route`
- Produces: Batch schedule management, quota tracker, manual price-lock trigger button.

- [ ] **Step 1: Write test for Expedition Scheduling**

Create `tests/Feature/AdminExpeditionScheduleTest.php`:
```php
<?php

use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route as MountainRoute;
use App\Models\User;

test('admin can create new expedition batch', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $mountain = Mountain::factory()->create();
    $route = MountainRoute::factory()->create(['mountain_id' => $mountain->id]);

    $response = $this->actingAs($admin)->post(route('admin.expeditions.store'), [
        'mountain_id' => $mountain->id,
        'route_id' => $route->id,
        'type' => 'open',
        'hiking_type' => 'camping',
        'departure_date' => now()->addDays(14)->toDateString(),
        'return_date' => now()->addDays(16)->toDateString(),
        'quota_max' => 10,
        'status' => 'open',
    ]);

    $response->assertRedirect(route('admin.expeditions.index'));
    $this->assertDatabaseHas('expeditions', [
        'mountain_id' => $mountain->id,
        'quota_max' => 10,
        'status' => 'open',
    ]);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/AdminExpeditionScheduleTest.php --compact`
Expected: FAIL.

- [ ] **Step 3: Implement ExpeditionScheduleController**

Implement CRUD operations, validation rules for departure/return dates (`departure_date >= today`, `return_date >= departure_date`), and status update options (`open`, `price_locked`, `completed`, `cancelled`).

- [ ] **Step 4: Create Views**

Create:
- `resources/views/admin/expeditions/index.blade.php`: Tab filter (Semua, Open Trip, Private Trip, Price Locked), batch cards/table with progress bar kuota (`quota_booked` / `quota_max`), departure countdown, and action dropdown.
- `resources/views/admin/expeditions/create.blade.php`: Form with dynamic route dropdown populated based on mountain selection.
- `resources/views/admin/expeditions/edit.blade.php`: Edit form with status management.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/AdminExpeditionScheduleTest.php --compact`
Expected: PASS.

---

### Task 7: Manajemen Booking, Manifes Peserta (SIMAKSI), & Verifikasi Pembayaran

**Files:**
- Create: `app/Http/Controllers/Admin/BookingManagementController.php`
- Create: `resources/views/admin/bookings/index.blade.php`
- Create: `resources/views/admin/bookings/show.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/AdminBookingManagementTest.php`

**Interfaces:**
- Consumes: `Booking`, `BookingParticipant`, `PaymentTransaction`, `Expedition`
- Produces: Booking audit, participant SIMAKSI inspector, manual payment status update (`reserved` / `paid`).

- [ ] **Step 1: Write test for Booking Management & SIMAKSI Manifest**

Create `tests/Feature/AdminBookingManagementTest.php`:
```php
<?php

use App\Models\Booking;
use App\Models\BookingParticipant;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route as MountainRoute;
use App\Models\User;

test('admin can view booking list and filter by status', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $booking = Booking::factory()->create(['status' => 'reserved']);

    $response = $this->actingAs($admin)->get(route('admin.bookings.index', ['status' => 'reserved']));
    $response->assertOk();
    $response->assertSee($booking->booking_code);
});

test('admin can view detail booking and participant SIMAKSI data', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $booking = Booking::factory()->create();
    $participant = BookingParticipant::factory()->create([
        'booking_id' => $booking->id,
        'full_name' => 'Pendaki Tangguh',
        'nik' => '3301010101990001',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.bookings.show', $booking->id));
    $response->assertOk();
    $response->assertSee('Pendaki Tangguh');
    $response->assertSee('3301010101990001');
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/AdminBookingManagementTest.php --compact`
Expected: FAIL.

- [ ] **Step 3: Implement BookingManagementController**

Implement:
- `index()`: list bookings with filter tabs (`Semua`, `open`, `reserved`, `price_locked`, `paid`, `cancelled`), search by booking code, customer name, or phone.
- `show($id)`: comprehensive detail page showing order breakdown, trip details, participant SIMAKSI table (Nama, NIK, Status Ketua), and payment transaction history.
- `updateStatus()`: allow admin to manually approve/confirm payment if customer transfers manually.

- [ ] **Step 4: Create Views**

Create:
- `resources/views/admin/bookings/index.blade.php`: Data table with Booking Code pill, Customer info, Ekspedisi, Pax, Nominal DP / Total, and Status Badge.
- `resources/views/admin/bookings/show.blade.php`: Detail view with summary cards, table of SIMAKSI participants (copyable NIK, full names), transaction logs, and action buttons.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/AdminBookingManagementTest.php --compact`
Expected: PASS.

---

### Task 8: Code Quality, Pint Formatting, & Final System Verification

**Files:**
- Modified PHP files across the project
- All Feature Tests

- [ ] **Step 1: Run Laravel Pint Formatter**

Run: `vendor/bin/pint --dirty --format agent`
Expected: All newly created and modified PHP files formatted to clean Laravel standards.

- [ ] **Step 2: Run the complete test suite**

Run: `php artisan test --compact`
Expected: 100% of tests passing (including previous 52 tests and all new admin tests).

- [ ] **Step 3: Build frontend assets**

Run: `npm run build`
Expected: Build succeeds with 0 errors.
