<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NewFeaturesAndFixesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Task 1: Test open trip booking config respects available quota.
     */
    public function test_booking_config_includes_available_quota_calculation(): void
    {
        $mountain = Mountain::create([
            'name' => 'Gunung Prau',
            'slug' => 'gunung-prau',
            'elevation' => 2565,
            'province' => 'Jawa Tengah',
            'grade' => 'Grade A',
            'cover_image' => 'mountains/prau.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 150000,
            'price_lock_days_before_departure' => 3,
            'has_open_trip' => true,
            'is_active' => true,
        ]);
        $route = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Patakbanteng',
            'slug' => 'patakbanteng',
            'grade' => 'Grade A',
            'is_primary' => true,
        ]);
        Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'departure_date' => now()->addDays(10)->toDateString(),
            'return_date' => now()->addDays(12)->toDateString(),
            'quota_max' => 10,
            'quota_booked' => 7,
            'status' => 'open',
            'price_tier_snapshot' => [],
        ]);

        $response = $this->get(route('ekspedisi.show', $mountain->slug));
        $response->assertOk();
        $response->assertViewHas('bookingConfig');

        $bookingConfig = $response->viewData('bookingConfig');
        $this->assertEquals(10, $bookingConfig['openQuotaMax']);
        $this->assertEquals(7, $bookingConfig['openQuotaBooked']);
        $this->assertEquals(3, $bookingConfig['availableQuota']);
    }

    /**
     * Task 2: Test user can cancel an open (unpaid DP) booking.
     */
    public function test_user_can_cancel_unpaid_booking(): void
    {
        $user = User::factory()->create();
        $mountain = Mountain::create([
            'name' => 'Gunung Merbabu',
            'slug' => 'gunung-merbabu',
            'elevation' => 3145,
            'province' => 'Jawa Tengah',
            'grade' => 'Grade B',
            'cover_image' => 'mountains/merbabu.jpg',
            'base_price' => 600000,
            'booking_fee_per_pax' => 200000,
            'price_lock_days_before_departure' => 3,
            'is_active' => true,
        ]);
        $route = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Selo',
            'slug' => 'selo',
            'grade' => 'Grade B',
            'is_primary' => true,
        ]);
        $expedition = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'departure_date' => now()->addDays(10)->toDateString(),
            'return_date' => now()->addDays(12)->toDateString(),
            'quota_max' => 10,
            'quota_booked' => 2,
            'status' => 'open',
            'price_tier_snapshot' => [],
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'expedition_id' => $expedition->id,
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'booking_code' => 'MT-TESTCANCEL',
            'customer_name' => 'Pendaki Merbabu',
            'customer_phone' => '08123456789',
            'customer_email' => 'pendaki@test.com',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'status' => 'open',
            'total_booking_fee' => 400000,
            'booking_fee_per_pax' => 200000,
            'grand_total' => 1200000,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.cancel', $booking->booking_code));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'booking_code' => 'MT-TESTCANCEL',
            'status' => 'cancelled',
        ]);
    }

    /**
     * Task 2: Test user cannot cancel a booking that is already reserved or paid.
     */
    public function test_user_cannot_cancel_already_paid_or_reserved_booking(): void
    {
        $user = User::factory()->create();
        $mountain = Mountain::create([
            'name' => 'Gunung Lawu',
            'slug' => 'gunung-lawu',
            'elevation' => 3265,
            'province' => 'Jawa Tengah',
            'grade' => 'Grade B',
            'cover_image' => 'mountains/lawu.jpg',
            'base_price' => 550000,
            'booking_fee_per_pax' => 200000,
            'price_lock_days_before_departure' => 3,
            'is_active' => true,
        ]);
        $route = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Candi Cetho',
            'slug' => 'candi-cetho',
            'grade' => 'Grade B',
            'is_primary' => true,
        ]);
        $expedition = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'departure_date' => now()->addDays(10)->toDateString(),
            'return_date' => now()->addDays(12)->toDateString(),
            'quota_max' => 10,
            'quota_booked' => 2,
            'status' => 'open',
            'price_tier_snapshot' => [],
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'expedition_id' => $expedition->id,
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'booking_code' => 'MT-TESTNOCANCEL',
            'customer_name' => 'Pendaki Lawu',
            'customer_phone' => '08123456789',
            'customer_email' => 'pendaki@test.com',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'status' => 'reserved',
            'total_booking_fee' => 400000,
            'booking_fee_per_pax' => 200000,
            'grand_total' => 1100000,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.cancel', $booking->booking_code));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('bookings', [
            'booking_code' => 'MT-TESTNOCANCEL',
            'status' => 'reserved',
        ]);
    }

    /**
     * Task 3: Test admin can update profile information and password.
     */
    public function test_admin_can_update_profile_and_password(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'name' => 'Original Admin',
            'email' => 'admin_test@middletrip.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        // 1. View admin profile edit page
        $viewResponse = $this->actingAs($admin)->get(route('admin.profile.edit'));
        $viewResponse->assertOk();
        $viewResponse->assertSee('Original Admin');

        // 2. Update profile name & email
        $updateResponse = $this->actingAs($admin)->patch(route('admin.profile.update'), [
            'name' => 'Super Admin MiddleTrip',
            'email' => 'superadmin@middletrip.com',
        ]);
        $updateResponse->assertRedirect(route('admin.profile.edit'));
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Super Admin MiddleTrip',
            'email' => 'superadmin@middletrip.com',
        ]);

        // 3. Update password
        $passResponse = $this->actingAs($admin)->put(route('admin.profile.password'), [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $passResponse->assertRedirect(route('admin.profile.edit'));
        $passResponse->assertSessionHas('success');

        $this->assertTrue(Hash::check('newpassword123', $admin->fresh()->password));
    }

    /**
     * Task 4: Test booking print e-ticket / invoice view.
     */
    public function test_booking_invoice_print_view_is_accessible(): void
    {
        $user = User::factory()->create();
        $mountain = Mountain::create([
            'name' => 'Gunung Rinjani',
            'slug' => 'gunung-rinjani',
            'elevation' => 3726,
            'province' => 'Nusa Tenggara Barat',
            'grade' => 'Grade C',
            'cover_image' => 'mountains/rinjani.jpg',
            'base_price' => 1200000,
            'booking_fee_per_pax' => 500000,
            'price_lock_days_before_departure' => 3,
            'is_active' => true,
        ]);
        $route = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Sembalun',
            'slug' => 'sembalun',
            'grade' => 'Grade C',
            'is_primary' => true,
        ]);
        $expedition = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'departure_date' => now()->addDays(10)->toDateString(),
            'return_date' => now()->addDays(12)->toDateString(),
            'quota_max' => 10,
            'quota_booked' => 2,
            'status' => 'open',
            'price_tier_snapshot' => [],
        ]);
        $booking = Booking::create([
            'user_id' => $user->id,
            'expedition_id' => $expedition->id,
            'route_id' => $route->id,
            'mountain_id' => $mountain->id,
            'status' => 'paid',
            'booking_code' => 'MT-PRINT123',
            'customer_name' => 'Pendaki Sejati',
            'customer_phone' => '08123456789',
            'customer_email' => 'sejati@test.com',
            'customer_nik' => '3301234567890001',
            'pax_count' => 2,
            'trip_type' => 'open',
            'hiking_type' => 'camping',
            'total_booking_fee' => 1000000,
            'booking_fee_per_pax' => 500000,
            'grand_total' => 2400000,
        ]);

        // Customer can view print page
        $response = $this->actingAs($user)->get(route('bookings.print', $booking->booking_code));
        $response->assertOk();
        $response->assertSee('MT-PRINT123');
        $response->assertSee('Gunung Rinjani');
        $response->assertSee('Pendaki Sejati');
        $response->assertSee('Invoice Resmi');
    }

    /**
     * Task 5: Test admin DP setting cannot exceed 50% of trip price.
     */
    public function test_admin_cannot_set_dp_more_than_half_of_base_price(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Attempt to create mountain with base_price = 500.000 and DP = 300.000 (> 50%)
        $response = $this->actingAs($admin)->post(route('admin.mountains.store'), [
            'name' => 'Gunung Sindoro',
            'elevation' => 3153,
            'province' => 'Jawa Tengah',
            'grade' => 'Grade B',
            'cover_image' => 'mountains/sindoro.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 300000, // Invalid: > 250.000
            'price_lock_days_before_departure' => 3,
        ]);

        $response->assertSessionHasErrors('booking_fee_per_pax');

        // Valid DP = 250.000 (<= 50%)
        $validResponse = $this->actingAs($admin)->post(route('admin.mountains.store'), [
            'name' => 'Gunung Sindoro',
            'elevation' => 3153,
            'province' => 'Jawa Tengah',
            'grade' => 'Grade B',
            'cover_image' => 'mountains/sindoro.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 250000, // Valid
            'price_lock_days_before_departure' => 3,
        ]);

        $validResponse->assertSessionDoesntHaveErrors(['booking_fee_per_pax']);
        $this->assertDatabaseHas('mountains', [
            'name' => 'Gunung Sindoro',
            'booking_fee_per_pax' => 250000,
        ]);
    }
}
