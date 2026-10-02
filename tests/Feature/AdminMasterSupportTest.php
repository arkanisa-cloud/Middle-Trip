<?php

use App\Models\Mountain;
use App\Models\User;

test('admin can manage meeting points for a mountain', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $mountain = Mountain::create([
        'name' => 'Gunung Lawu',
        'slug' => 'gunung-lawu',
        'elevation' => 3265,
        'province' => 'Jawa Tengah',
        'cover_image' => 'https://example.com/lawu.jpg',
        'base_price' => 450000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.meeting-points.store'), [
        'mountain_id' => $mountain->id,
        'name' => 'Stasiun Solo Balapan',
        'location_type' => 'station',
        'additional_price_per_pax' => 75000,
        'is_default' => false,
    ]);

    $response->assertRedirect(route('admin.meeting-points.index'));
    $this->assertDatabaseHas('meeting_points', ['name' => 'Stasiun Solo Balapan', 'mountain_id' => $mountain->id]);
});

test('admin can manage addons gear catalog', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.addons.store'), [
        'name' => 'Trekking Pole Carbon',
        'category' => 'gear',
        'price' => 35000,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('admin.addons.index'));
    $this->assertDatabaseHas('addons', ['name' => 'Trekking Pole Carbon', 'price' => 35000]);
});
