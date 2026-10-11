<?php

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Pendaki Update',
            'email' => 'pendaki.update@gmail.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile?tab=settings');

    $user->refresh();

    $this->assertSame('Pendaki Update', $user->name);
    $this->assertSame('pendaki.update@gmail.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('profile update rejects dummy email address like coba@mail.com', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Dummy Update',
            'email' => 'coba@mail.com',
        ]);

    $response->assertSessionHasErrors(['email']);
    $user->refresh();
    $this->assertNotSame('coba@mail.com', $user->email);
});

test('profile update rejects dummy email coba@coba.com', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Dummy Update',
            'email' => 'coba@coba.com',
        ]);

    $response->assertSessionHasErrors(['email']);
    $user->refresh();
    $this->assertNotSame('coba@coba.com', $user->email);
});

test('profile phone number can be updated with valid phone number', function () {
    Http::fake([
        'https://api.fonnte.com/validate' => Http::response([
            'status' => true,
            'registered' => ['6281298765432'],
            'not_registered' => [],
        ], 200),
    ]);

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Pendaki Phone',
            'email' => $user->email,
            'phone' => '081298765432',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile?tab=settings');

    $user->refresh();
    $this->assertSame('081298765432', $user->phone);
});

test('profile update rejects dummy phone number', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Dummy Phone',
            'email' => $user->email,
            'phone' => '081234567890',
        ]);

    $response->assertSessionHasErrors(['phone']);
    $user->refresh();
    $this->assertNotSame('081234567890', $user->phone);
});

test('profile nik can be updated with valid nik', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Pendaki NIK',
            'email' => $user->email,
            'nik' => '3301011508950001',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile?tab=settings');

    $user->refresh();
    $this->assertSame('3301011508950001', $user->nik);
});

test('profile update rejects dummy nik', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Dummy NIK',
            'email' => $user->email,
            'nik' => '1234567890123456',
        ]);

    $response->assertSessionHasErrors(['nik']);
    $user->refresh();
    $this->assertNotSame('1234567890123456', $user->nik);
});

test('profile photo can be uploaded and updated', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'User Photo',
            'email' => $user->email,
            'avatar' => $file,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile?tab=settings');

    $user->refresh();

    $this->assertNotNull($user->avatar);
    Storage::disk('public')->assertExists($user->avatar);
});

test('profile photo can be removed', function () {
    Storage::fake('public');
    $user = User::factory()->create(['avatar' => 'avatars/dummy.jpg']);
    Storage::disk('public')->put('avatars/dummy.jpg', 'content');

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'User Photo',
            'email' => $user->email,
            'remove_avatar' => 1,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile?tab=settings');

    $user->refresh();

    $this->assertNull($user->avatar);
    Storage::disk('public')->assertMissing('avatars/dummy.jpg');
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile?tab=settings');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});

test('profile page displays user bookings when bookings exist', function () {
    $user = User::factory()->create();

    $mountain = Mountain::create([
        'name' => 'Gunung Sindoro Test',
        'slug' => 'gunung-sindoro-test-profile',
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
        'slug' => 'via-kledung-profile',
        'grade' => 'Grade A',
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
        'booking_code' => 'MDL-TEST-9999',
        'user_id' => $user->id,
        'expedition_id' => $expedition->id,
        'route_id' => $route->id,
        'trip_type' => 'open',
        'hiking_type' => 'camping',
        'customer_name' => $user->name,
        'customer_email' => $user->email,
        'customer_phone' => '081234567890',
        'customer_nik' => '3301234567890001',
        'pax_count' => 2,
        'booking_fee_per_pax' => 150000,
        'total_booking_fee' => 300000,
        'locked_price_per_pax' => 500000,
        'grand_total' => 1000000,
        'status' => 'open',
    ]);

    $response = $this->actingAs($user)->get('/profile');

    $response->assertOk();
    $response->assertSee('MDL-TEST-9999');
    $response->assertSee('Gunung Sindoro Test');
    $response->assertSee('Via Kledung');
    $response->assertSee('Menunggu Pembayaran');
});

test('profile page displays empty state when user has no bookings', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/profile');

    $response->assertOk();
    $response->assertSee('Belum Ada Riwayat Pesanan');
});
