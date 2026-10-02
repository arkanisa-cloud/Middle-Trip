<?php

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use App\Models\User;

test('admin dashboard renders metrics, recent bookings, and upcoming batches', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $mountain = Mountain::create([
        'name' => 'Mt. Merbabu',
        'slug' => 'mt-merbabu-test',
        'elevation' => 3142,
        'province' => 'Jawa Tengah',
        'cover_image' => 'https://example.com/merbabu.jpg',
        'base_price' => 500000,
        'booking_fee_per_pax' => 150000,
    ]);

    $route = Route::create([
        'mountain_id' => $mountain->id,
        'name' => 'Via Selo',
        'slug' => 'selo-test',
        'grade' => 'Grade A',
        'is_primary' => true,
    ]);

    $expedition = Expedition::create([
        'mountain_id' => $mountain->id,
        'route_id' => $route->id,
        'type' => 'open',
        'hiking_type' => 'camping',
        'departure_date' => now()->addDays(5)->toDateString(),
        'return_date' => now()->addDays(7)->toDateString(),
        'quota_max' => 10,
        'quota_booked' => 3,
        'status' => 'open',
    ]);

    Booking::create([
        'booking_code' => 'MT-TEST-001',
        'user_id' => $admin->id,
        'expedition_id' => $expedition->id,
        'route_id' => $route->id,
        'trip_type' => 'open',
        'customer_name' => 'Alvaro Dev',
        'customer_email' => 'alvaro@example.com',
        'customer_phone' => '08123456789',
        'customer_nik' => '3301010101990001',
        'pax_count' => 3,
        'booking_fee_per_pax' => 150000,
        'total_booking_fee' => 450000,
        'grand_total' => 1500000,
        'status' => 'reserved',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertViewIs('admin.dashboard');
    $response->assertViewHas(['metrics', 'recentBookings', 'upcomingPriceLocks']);
    $response->assertSee('MT-TEST-001');
    $response->assertSee('Alvaro Dev');
});
