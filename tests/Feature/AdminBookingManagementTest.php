<?php

use App\Models\Booking;
use App\Models\BookingParticipant;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use App\Models\User;

test('admin can view booking list and filter by status', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $mountain = Mountain::create([
        'name' => 'Gunung Ciremai',
        'slug' => 'gunung-ciremai',
        'elevation' => 3078,
        'province' => 'Jawa Barat',
        'cover_image' => 'https://example.com/ciremai.jpg',
        'base_price' => 500000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
    ]);

    $route = Route::create([
        'mountain_id' => $mountain->id,
        'name' => 'Via Apuy',
        'slug' => 'apuy',
        'grade' => 'Grade B',
        'is_primary' => true,
    ]);

    $expedition = Expedition::create([
        'mountain_id' => $mountain->id,
        'route_id' => $route->id,
        'type' => 'open',
        'hiking_type' => 'camping',
        'departure_date' => now()->addDays(10)->toDateString(),
        'return_date' => now()->addDays(12)->toDateString(),
        'quota_max' => 10,
        'quota_booked' => 2,
        'status' => 'open',
    ]);

    $booking = Booking::create([
        'booking_code' => 'MT-BOOK-999',
        'user_id' => $admin->id,
        'expedition_id' => $expedition->id,
        'route_id' => $route->id,
        'trip_type' => 'open',
        'customer_name' => 'Budi Pendaki',
        'customer_email' => 'budi@example.com',
        'customer_phone' => '081298765432',
        'customer_nik' => '3201010101990002',
        'pax_count' => 2,
        'booking_fee_per_pax' => 150000,
        'total_booking_fee' => 300000,
        'grand_total' => 1000000,
        'status' => 'reserved',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.bookings.index', ['status' => 'reserved']));
    $response->assertOk();
    $response->assertSee('MT-BOOK-999');
    $response->assertSee('Budi Pendaki');
});

test('admin can view detail booking and participant SIMAKSI data', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $mountain = Mountain::create([
        'name' => 'Gunung Sindoro',
        'slug' => 'gunung-sindoro',
        'elevation' => 3153,
        'province' => 'Jawa Tengah',
        'cover_image' => 'https://example.com/sindoro.jpg',
        'base_price' => 500000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
    ]);

    $route = Route::create([
        'mountain_id' => $mountain->id,
        'name' => 'Via Kledung',
        'slug' => 'kledung',
        'grade' => 'Grade B',
        'is_primary' => true,
    ]);

    $expedition = Expedition::create([
        'mountain_id' => $mountain->id,
        'route_id' => $route->id,
        'type' => 'open',
        'hiking_type' => 'camping',
        'departure_date' => now()->addDays(8)->toDateString(),
        'return_date' => now()->addDays(10)->toDateString(),
        'quota_max' => 10,
        'quota_booked' => 1,
        'status' => 'open',
    ]);

    $booking = Booking::create([
        'booking_code' => 'MT-BOOK-888',
        'user_id' => $admin->id,
        'expedition_id' => $expedition->id,
        'route_id' => $route->id,
        'trip_type' => 'open',
        'customer_name' => 'Rian Hiker',
        'customer_email' => 'rian@example.com',
        'customer_phone' => '081233334444',
        'customer_nik' => '3301010101880001',
        'pax_count' => 1,
        'booking_fee_per_pax' => 150000,
        'total_booking_fee' => 150000,
        'grand_total' => 550000,
        'status' => 'reserved',
    ]);

    BookingParticipant::create([
        'booking_id' => $booking->id,
        'full_name' => 'Rian Hiker',
        'nik' => '3301010101880001',
        'gender' => 'L',
        'is_leader' => true,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.bookings.show', $booking->id));
    $response->assertOk();
    $response->assertSee('MT-BOOK-888');
    $response->assertSee('Rian Hiker');
    $response->assertSee('3301010101880001');
});
