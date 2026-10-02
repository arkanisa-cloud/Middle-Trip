<?php

use App\Models\Expedition;
use App\Models\ExpeditionPriceTier;
use App\Models\Mountain;
use App\Models\Route;
use App\Models\User;

test('admin can create new expedition batch', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $mountain = Mountain::create([
        'name' => 'Gunung Prau',
        'slug' => 'gunung-prau-sched',
        'elevation' => 2590,
        'province' => 'Jawa Tengah',
        'cover_image' => 'https://example.com/prau.jpg',
        'base_price' => 450000,
        'booking_fee_per_pax' => 100000,
        'price_lock_days_before_departure' => 3,
    ]);

    $route = Route::create([
        'mountain_id' => $mountain->id,
        'name' => 'Via Dieng',
        'slug' => 'dieng',
        'grade' => 'Grade A',
        'is_primary' => true,
    ]);

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

test('admin can trigger price lock on open expedition', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $mountain = Mountain::create([
        'name' => 'Gunung Merbabu Test Lock',
        'slug' => 'gunung-merbabu-lock',
        'elevation' => 3142,
        'province' => 'Jawa Tengah',
        'cover_image' => 'https://example.com/merbabu.jpg',
        'base_price' => 550000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
    ]);

    ExpeditionPriceTier::create([
        'mountain_id' => $mountain->id,
        'min_pax' => 1,
        'max_pax' => 5,
        'price_per_pax' => 600000,
    ]);

    $route = Route::create([
        'mountain_id' => $mountain->id,
        'name' => 'Via Selo',
        'slug' => 'selo-lock',
        'grade' => 'Grade A',
        'is_primary' => true,
    ]);

    $expedition = Expedition::create([
        'mountain_id' => $mountain->id,
        'route_id' => $route->id,
        'type' => 'open',
        'hiking_type' => 'camping',
        'departure_date' => now()->addDays(3)->toDateString(),
        'return_date' => now()->addDays(5)->toDateString(),
        'quota_max' => 10,
        'quota_booked' => 3,
        'status' => 'open',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.expeditions.price_lock', $expedition->id));
    $response->assertRedirect();

    $this->assertDatabaseHas('expeditions', [
        'id' => $expedition->id,
        'status' => 'price_locked',
        'current_locked_price' => 600000,
    ]);
});
