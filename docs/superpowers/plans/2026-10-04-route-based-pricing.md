# Route-Based Pricing Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement route-specific pricing for Open Trip & Private Trip (Camping and Tektok) allowing admin to configure prices per via and enabling real-time price updates on the customer detail page when switching routes.

**Architecture:** Add 4 price columns to `routes` table with graceful fallback to mountain base prices. Update `Route` and `Mountain` models with helper accessors. Extend the Admin Mountain management form to input prices per via. Enhance `ExpeditionController` and `detail.blade.php` to reactively switch prices when selecting a via. Update `BookingService` to calculate booking totals using the selected route's prices.

**Tech Stack:** Laravel 11/12/13, Eloquent ORM, Blade, Alpine.js, Tailwind CSS, Pest/PHPUnit.

**Spec:** `docs/superpowers/specs/2026-10-04-route-based-pricing-design.md`

## Global Constraints
- Laravel best practices from `.claude/skills/laravel-best-practices` must be adhered to.
- Use explicit return type declarations and typed properties.
- Do not break existing bookings, mountain creation, or expedition queries (graceful fallback when route price is null).
- All tests must pass via `./vendor/bin/sail test`.

---

### Task 1: Database Migration for Route Pricing Columns

**Files:**
- Create: `database/migrations/2026_10_04_000001_add_pricing_columns_to_routes_table.php`
- Test: `tests/Feature/RoutePricingTest.php`

**Interfaces:**
- Produces: `routes.price_camping_open`, `routes.price_tektok_open`, `routes.price_camping_private`, `routes.price_tektok_private`

- [ ] **Step 1: Write migration for route pricing columns**
Create migration adding `price_camping_open`, `price_tektok_open`, `price_camping_private`, `price_tektok_private` as nullable unsigned integers to `routes`.

- [ ] **Step 2: Run migration**
Run `./vendor/bin/sail artisan migrate`.

- [ ] **Step 3: Verify migration status**
Verify the table has the columns.

---

### Task 2: Models Update (`Route` and `Mountain`)

**Files:**
- Modify: `app/Models/Route.php`
- Modify: `app/Models/Mountain.php`
- Test: `tests/Feature/RoutePricingTest.php`

**Interfaces:**
- Consumes: `routes` columns
- Produces:
  - `Route::getEffectivePriceCampingOpenAttribute()`
  - `Route::getEffectivePriceTektokOpenAttribute()`
  - `Route::getEffectivePriceCampingPrivateAttribute()`
  - `Route::getEffectivePriceTektokPrivateAttribute()`
  - `Mountain::getEffectiveStartingPriceAttribute()`
  - `Mountain::getFormattedPriceAttribute()`
  - `Mountain::getFormattedShortPriceAttribute()`

- [ ] **Step 1: Update `Route` casts and accessor methods**
Add casts for the 4 price fields and provide fallback to `$this->mountain` if null.

- [ ] **Step 2: Update `Mountain` to calculate starting price from routes (Opsi A)**
In `Mountain.php`, add `effective_starting_price` which finds `min(price_camping_open)` from routes where price > 0, fallback to `base_price`. Update `formatted_short_price` and `formatted_price` to use this attribute.

- [ ] **Step 3: Write tests for Model pricing calculation**
Write test asserting that a route with distinct prices returns its own prices and mountain starting price uses the minimum route price.

---

### Task 3: Admin CMS (Controller & Blade Views)

**Files:**
- Modify: `app/Http/Controllers/Admin/MountainController.php`
- Modify: `resources/views/admin/mountains/create.blade.php`
- Modify: `resources/views/admin/mountains/edit.blade.php`

**Interfaces:**
- Consumes: `routes.*.price_camping_open`, `routes.*.price_tektok_open`, `routes.*.price_camping_private`, `routes.*.price_tektok_private`

- [ ] **Step 1: Update validation & storage in `MountainController.php`**
In `rules()` for store and update, add validation for `routes.*.price_camping_open`, `routes.*.price_tektok_open`, `routes.*.price_camping_private`, and `routes.*.price_tektok_private`. In `store` and `update` loops, save them to the `Route` records.

- [ ] **Step 2: Update `create.blade.php` route repeater UI**
Add a sleek grid of 4 price input fields for each route item (Open Camping, Open Tektok, Private Camping, Private Tektok) with labels and placeholders.

- [ ] **Step 3: Update `edit.blade.php` route repeater UI**
Bind the existing route prices into Alpine/Blade so admin can see and edit the 4 prices per via.

---

### Task 4: Customer Detail Page & Reactive UI

**Files:**
- Modify: `app/Http/Controllers/ExpeditionController.php`
- Modify: `resources/views/customer/detail.blade.php`

**Interfaces:**
- Consumes: route prices
- Produces: Dynamic reactive price display on route selection

- [ ] **Step 1: Update `ExpeditionController` dataset**
Include `price_camping_open`, `price_tektok_open`, `price_camping_private`, `price_tektok_private` in `routesData` for `expeditionData.routes`.

- [ ] **Step 2: Update JavaScript in `detail.blade.php`**
Modify `calculateCurrentPrice()` to inspect the selected route first (`selectedRouteId`). If the selected route has prices set, use those prices; otherwise fallback to expedition-level prices.

- [ ] **Step 3: Trigger `updatePriceDisplay()` in `selectJalurOption()`**
Ensure that when a user selects a different via in the dropdown, `updatePriceDisplay()` is executed and modal booking configuration is synchronized.

- [ ] **Step 4: Update Alpine booking modal price reaction**
Ensure that in the booking modal, switching routes or selecting hike types dynamically calculates the total according to the selected via.

---

### Task 5: Backend `BookingService` Calculation

**Files:**
- Modify: `app/Services/BookingService.php`

**Interfaces:**
- Consumes: `data['route_id']`, `data['trip_type']`, `data['hiking_type']`, `data['pax_count']`
- Produces: Correct `locked_price_per_pax` and `grand_total` based on selected route.

- [ ] **Step 1: Update `BookingService::createBooking()`**
Find the `Route` model using `$data['route_id']`. If the route has custom prices (`price_camping_open`, `price_tektok_open`, `price_camping_private`, `price_tektok_private`), compute base price from the route instead of global mountain flat price.

---

### Task 6: Feature Tests & Verification

**Files:**
- Create/Update: `tests/Feature/RoutePricingTest.php`

- [ ] **Step 1: Write comprehensive tests**
Test:
1. Creating a mountain with multiple routes having different prices.
2. Checking starting price on mountain displays lowest via price.
3. Booking on via A charges via A's price; booking on via B charges via B's price.
4. Booking private trip on via A uses via A's private price.

- [ ] **Step 2: Run test suite**
Run `./vendor/bin/sail test tests/Feature/RoutePricingTest.php` and verify all tests pass.
