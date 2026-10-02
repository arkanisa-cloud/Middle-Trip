<?php

use App\Enums\TrailGrade;
use App\Models\Booking;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin can see mountain list', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $mountain = Mountain::create([
        'name' => 'Gunung Rinjani',
        'slug' => 'gunung-rinjani',
        'elevation' => 3726,
        'province' => 'NTB',
        'cover_image' => 'https://example.com/rinjani.jpg',
        'base_price' => 750000,
        'booking_fee_per_pax' => 200000,
        'price_lock_days_before_departure' => 4,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.mountains.index'));
    $response->assertOk();
    $response->assertSee('Gunung Rinjani');
});

test('admin can store new mountain with routes and price tiers without max_pax', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $data = [
        'name' => 'Gunung Slamet',
        'elevation' => 3428,
        'province' => 'Jawa Tengah',
        'grade' => 'Grade B',
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
            ['name' => 'Via Bambangan', 'is_primary' => true, 'distance_km' => 14.5, 'duration_hours' => '8-10 Jam'],
        ],
        'price_tiers' => [
            ['min_pax' => 1, 'price_per_pax' => 650000],
            ['min_pax' => 4, 'price_per_pax' => 550000],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $data);
    $response->assertRedirect(route('admin.mountains.index'));

    $this->assertDatabaseHas('mountains', ['name' => 'Gunung Slamet', 'elevation' => 3428]);
    $this->assertDatabaseHas('routes', ['name' => 'Via Bambangan', 'grade' => 'Grade B']);
    $this->assertDatabaseHas('expedition_price_tiers', ['min_pax' => 1, 'max_pax' => null, 'price_per_pax' => 650000]);
});

test('admin can store new mountain with uploaded cover image file', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $file = UploadedFile::fake()->image('slamet.jpg');

    $data = [
        'name' => 'Gunung Lawu',
        'elevation' => 3265,
        'province' => 'Jawa Timur',
        'grade' => 'Grade A',
        'cover_image_file' => $file,
        'base_price' => 450000,
        'booking_fee_per_pax' => 100000,
        'price_lock_days_before_departure' => 3,
    ];

    $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $data);
    $response->assertRedirect(route('admin.mountains.index'));

    $mountain = Mountain::where('name', 'Gunung Lawu')->first();
    expect($mountain)->not->toBeNull();
    expect($mountain->cover_image)->toContain('/storage/mountains/');
});

test('newly added mountain detail page is accessible and does not 404', function () {
    $mountain = Mountain::create([
        'name' => 'Gunung Sindoro Baru',
        'slug' => 'gunung-sindoro-baru',
        'elevation' => 3153,
        'province' => 'Jawa Tengah',
        'cover_image' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa',
        'description' => 'Eksplorasi keindahan Sindoro.',
        'base_price' => 600000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
        'is_active' => true,
    ]);

    $response = $this->get(route('ekspedisi.show', $mountain->slug));
    $response->assertOk();
    $response->assertSee('Gunung Sindoro Baru');
    $response->assertSee('3.153 mdpl');
});

test('admin can store mountain with custom overview, checkpoints, and facilities', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $data = [
        'name' => 'Gunung Sumbing Indah',
        'elevation' => 3371,
        'province' => 'Jawa Tengah',
        'grade' => 'Grade B',
        'cover_image' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa',
        'overview' => 'Ulasan mendalam ekspedisi Sumbing via Bowongso.',
        'base_price' => 600000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
        'checkpoints' => [
            ['name' => 'Basecamp Bowongso', 'elevation' => 1600],
            ['name' => 'Pos 1 Taman Bogel', 'elevation' => 2000],
            ['name' => 'Pos 2 Gajahan', 'elevation' => 2500],
            ['name' => 'Puncak Rajawali', 'elevation' => 3371],
        ],
        'water_note' => 'Pos 2 Gajahan (Mata Air Melimpah)',
        'facilities_included' => [
            ['category' => 'Akomodasi Premium', 'items' => "Tenda Dome 4P\nMatras Tiup"],
        ],
        'facilities_excluded' => [
            ['category' => 'Pribadi', 'items' => 'Jaket Hangat Pribadi'],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $data);
    $response->assertRedirect(route('admin.mountains.index'));

    $mountain = Mountain::where('name', 'Gunung Sumbing Indah')->first();
    expect($mountain)->not->toBeNull();
    expect($mountain->overview)->toBe('Ulasan mendalam ekspedisi Sumbing via Bowongso.');
    expect($mountain->elevation_checkpoints['points'])->toHaveCount(4);
    expect($mountain->facilities_included['Akomodasi Premium'])->toContain('Tenda Dome 4P');

    // Test on customer page
    $publicResponse = $this->get(route('ekspedisi.show', $mountain->slug));
    $publicResponse->assertOk();
    $publicResponse->assertSee('Ulasan mendalam ekspedisi Sumbing via Bowongso.');
    $publicResponse->assertSee('Pos 1 Taman Bogel');
    $publicResponse->assertSee('Tenda Dome 4P');
});

test('admin can store mountain with multi-route checkpoints and itinerary, and edit them', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $data = [
        'name' => 'Gunung Merbabu Multi Jalur',
        'elevation' => 3142,
        'province' => 'Jawa Tengah',
        'grade' => 'Grade A',
        'cover_image' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa',
        'overview' => 'Ekspedisi Merbabu dengan berbagai pilihan jalur pendakian.',
        'base_price' => 500000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
        'routes' => [
            [
                'name' => 'Via Selo',
                'grade' => 'Grade A',
                'distance_km' => 13.8,
                'duration_hours' => '6-7 Jam',
                'is_primary' => true,
                'checkpoints' => [
                    ['name' => 'Basecamp Selo', 'elevation' => 1800],
                    ['name' => 'Pos 1 Dok-dok', 'elevation' => 2100],
                    ['name' => 'Sabana 1', 'elevation' => 2800],
                    ['name' => 'Puncak Kenteng Songo', 'elevation' => 3142],
                ],
                'water_note' => 'Pos 3 Selo',
                'wind_note' => 'Sabana 2 Terpaan Angin',
                'signal_note' => '4G di Basecamp Selo',
                'day1_title' => 'Day 1: Selo Menuju Sabana',
                'day1_desc' => 'Trekking santai via Selo.',
                'day1_timeline' => "08:00 - Registrasi Selo\n09:00 - Trekking Santai\n16:00 - Tiba di Sabana",
                'day2_title' => 'Day 2: Summit Kenteng Songo',
                'day2_desc' => 'Sunrise di Kenteng Songo.',
                'day2_timeline' => "03:30 - Summit Push\n05:30 - Golden Sunrise\n14:00 - Tiba di Basecamp",
            ],
            [
                'name' => 'Via Suwanting',
                'grade' => 'Grade B',
                'distance_km' => 14.5,
                'duration_hours' => '8-9 Jam',
                'is_primary' => false,
                'checkpoints' => [
                    ['name' => 'Basecamp Suwanting', 'elevation' => 1280],
                    ['name' => 'Pos 2 Lembah Mitigasi', 'elevation' => 2200],
                    ['name' => 'Sabana Suwanting', 'elevation' => 2900],
                    ['name' => 'Puncak Triangulasi', 'elevation' => 3138],
                ],
                'water_note' => 'Pos 2 Mitigasi Suwanting',
                'wind_note' => 'Tanjakan Cendani',
                'signal_note' => 'Basecamp Suwanting',
                'day1_title' => 'Day 1: Suwanting Menembus Hutan Lamtoro',
                'day1_desc' => 'Tantangan menanjak jalur barat.',
                'day1_timeline' => "07:30 - Registrasi Suwanting\n12:00 - Istirahat Pos 2\n16:30 - Tiba di Sabana Suwanting",
                'day2_title' => 'Day 2: Triangulasi & Kenteng Songo',
                'day2_desc' => 'Muncak fajar di Triangulasi.',
                'day2_timeline' => "03:45 - Summit Attack\n05:40 - Sunrise Triangulasi\n15:30 - Basecamp",
            ],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $data);
    $response->assertRedirect(route('admin.mountains.index'));

    $mountain = Mountain::where('name', 'Gunung Merbabu Multi Jalur')->with('routes')->first();
    expect($mountain)->not->toBeNull();
    expect($mountain->routes)->toHaveCount(2);

    $routeSelo = $mountain->routes->firstWhere('name', 'Via Selo');
    expect($routeSelo->elevation_checkpoints['points'])->toHaveCount(4);
    expect($routeSelo->elevation_checkpoints['water_note'])->toBe('Pos 3 Selo');
    expect($routeSelo->itinerary['days'][0]['title'])->toBe('Day 1: Selo Menuju Sabana');

    $routeSuwanting = $mountain->routes->firstWhere('name', 'Via Suwanting');
    expect($routeSuwanting->elevation_checkpoints['points'])->toHaveCount(4);
    expect($routeSuwanting->elevation_checkpoints['water_note'])->toBe('Pos 2 Mitigasi Suwanting');
    expect($routeSuwanting->itinerary['days'][0]['title'])->toBe('Day 1: Suwanting Menembus Hutan Lamtoro');

    // Customer page test
    $publicResponse = $this->get(route('ekspedisi.show', $mountain->slug));
    $publicResponse->assertOk();
    $publicResponse->assertSee('Via Selo');
    $publicResponse->assertSee('Via Suwanting');
    $publicResponse->assertSee('Pos 1 Dok-dok');

    // Test Edit Page
    $editPage = $this->actingAs($admin)->get(route('admin.mountains.edit', $mountain->id));
    $editPage->assertOk();
    $editPage->assertSee('Via Selo');
    $editPage->assertSee('Via Suwanting');
});

test('admin cannot delete a route that has active bookings and receives error toast', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $mountain = Mountain::create([
        'name' => 'Gunung Prau Booking Test',
        'slug' => 'gunung-prau-booking-test',
        'elevation' => 2565,
        'province' => 'Jawa Tengah',
        'cover_image' => 'https://example.com/prau.jpg',
        'base_price' => 500000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
    ]);
    $route1 = Route::create([
        'mountain_id' => $mountain->id,
        'name' => 'Via Patak Banteng',
        'slug' => 'via-patak-banteng',
        'grade' => 'Grade A',
        'is_primary' => true,
    ]);
    $route2 = Route::create([
        'mountain_id' => $mountain->id,
        'name' => 'Via Dieng',
        'slug' => 'via-dieng',
        'grade' => 'Grade A',
        'is_primary' => false,
    ]);

    $expedition = Expedition::create([
        'mountain_id' => $mountain->id,
        'route_id' => $route2->id,
        'title' => 'Prau Expedition',
        'type' => 'open',
        'status' => 'open',
        'departure_date' => now()->addDays(10),
        'return_date' => now()->addDays(11),
        'quota_max' => 10,
        'quota_booked' => 2,
        'current_locked_price' => 500000,
    ]);

    Booking::create([
        'booking_code' => 'MT-TEST-ROUTE-DEL',
        'user_id' => $admin->id,
        'expedition_id' => $expedition->id,
        'route_id' => $route2->id,
        'trip_type' => 'open',
        'customer_name' => 'Pendaki Uji Coba',
        'customer_email' => 'pendaki@test.com',
        'customer_phone' => '081234567890',
        'customer_nik' => '3301234567890001',
        'pax_count' => 2,
        'booking_fee_per_pax' => 150000,
        'total_booking_fee' => 300000,
        'grand_total' => 1000000,
        'status' => 'paid',
    ]);

    // Check edit view contains booking count
    $editPage = $this->actingAs($admin)->get(route('admin.mountains.edit', $mountain->id));
    $editPage->assertOk();
    $editPage->assertSee('Via Dieng');
    $editPage->assertSee('Booking');

    $routesData = collect($editPage->viewData('routesData'));
    $routeWithBooking = $routesData->firstWhere('id', $route2->id);
    expect($routeWithBooking)->not->toBeNull();
    expect($routeWithBooking['has_active_bookings'])->toBeTrue();
    expect($routeWithBooking['active_bookings_count'])->toBe(1);

    // Attempt to update mountain while omitting route2
    $updateData = [
        'name' => $mountain->name,
        'elevation' => $mountain->elevation,
        'province' => $mountain->province,
        'grade' => 'Grade A',
        'base_price' => 500000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
        'routes' => [
            ['id' => $route1->id, 'name' => $route1->name, 'grade' => 'Grade A', 'is_primary' => true],
            // route2 omitted intentionally
        ],
    ];

    $response = $this->actingAs($admin)->put(route('admin.mountains.update', $mountain->id), $updateData);
    $response->assertSessionHas('error');
    $response->assertRedirect();

    // Verify route2 is NOT deleted
    $this->assertDatabaseHas('routes', ['id' => $route2->id, 'name' => 'Via Dieng']);
});

test('admin can store new mountain with gallery photos', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $file1 = UploadedFile::fake()->image('pos1.jpg');
    $file2 = UploadedFile::fake()->image('puncak.jpg');

    $data = [
        'name' => 'Gunung Raung',
        'elevation' => 3344,
        'province' => 'Jawa Timur',
        'grade' => 'Grade C',
        'cover_image' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa',
        'base_price' => 850000,
        'booking_fee_per_pax' => 200000,
        'price_lock_days_before_departure' => 4,
        'gallery_files' => [$file1, $file2],
        'gallery_file_captions' => ['Pondok Mayit', 'Puncak Sejati Raung'],
    ];

    $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $data);
    $response->assertRedirect(route('admin.mountains.index'));

    $mountain = Mountain::where('name', 'Gunung Raung')->first();
    expect($mountain)->not->toBeNull();
    expect($mountain->gallery)->toBeArray();
    expect($mountain->gallery)->toHaveCount(2);
    expect($mountain->gallery[0]['caption'])->toBe('Pondok Mayit');
    expect($mountain->gallery[0]['url'])->toContain('/storage/mountains/gallery/');
    expect($mountain->gallery[1]['caption'])->toBe('Puncak Sejati Raung');
});

test('admin can update mountain gallery maintaining existing and uploading new files', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $mountain = Mountain::create([
        'name' => 'Gunung Arjuno',
        'slug' => 'gunung-arjuno',
        'elevation' => 3339,
        'province' => 'Jawa Timur',
        'cover_image' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa',
        'base_price' => 600000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
        'gallery' => [
            ['url' => 'https://example.com/pos2.jpg', 'caption' => 'Pos 2 Lembah Kidang'],
            ['url' => 'https://example.com/delete-me.jpg', 'caption' => 'Foto akan dihapus'],
        ],
    ]);

    $newFile = UploadedFile::fake()->image('ogal-agil.jpg');

    $updateData = [
        'name' => $mountain->name,
        'elevation' => $mountain->elevation,
        'province' => $mountain->province,
        'grade' => 'Grade B',
        'base_price' => $mountain->base_price,
        'booking_fee_per_pax' => $mountain->booking_fee_per_pax,
        'price_lock_days_before_departure' => 3,
        'existing_gallery' => [
            ['url' => 'https://example.com/pos2.jpg', 'caption' => 'Pos 2 Lembah Kidang Updated'],
        ],
        'gallery_files' => [$newFile],
        'gallery_file_captions' => ['Puncak Ogal Agil'],
    ];

    $response = $this->actingAs($admin)->put(route('admin.mountains.update', $mountain->id), $updateData);
    $response->assertRedirect(route('admin.mountains.index'));

    $mountain->refresh();
    expect($mountain->gallery)->toBeArray();
    expect($mountain->gallery)->toHaveCount(2);
    expect($mountain->gallery[0]['url'])->toBe('https://example.com/pos2.jpg');
    expect($mountain->gallery[0]['caption'])->toBe('Pos 2 Lembah Kidang Updated');
    expect($mountain->gallery[1]['caption'])->toBe('Puncak Ogal Agil');
    expect($mountain->gallery[1]['url'])->toContain('/storage/mountains/gallery/');
});

test('expedition detail view displays unique gallery items without duplicates', function () {
    $mountain = Mountain::create([
        'name' => 'Gunung Ciremai',
        'slug' => 'gunung-ciremai',
        'elevation' => 3078,
        'province' => 'Jawa Barat',
        'cover_image' => 'https://example.com/ciremai-cover.jpg',
        'base_price' => 500000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
        'is_active' => true,
        'gallery' => [
            ['url' => 'https://example.com/ciremai-pos1.jpg', 'caption' => 'Pos 1 Cibunar'],
            ['url' => 'https://example.com/ciremai-kawah.jpg', 'caption' => 'Kawah Ciremai'],
        ],
    ]);

    $response = $this->get(route('ekspedisi.show', $mountain->slug));
    $response->assertOk();

    $expedition = $response->viewData('expedition');
    expect($expedition['gallery'])->toBeArray();
    // Only cover and uploaded gallery items are present, no auto-generated fallback photos
    expect(count($expedition['gallery']))->toBe(3);

    // Cover image is the first gallery item
    expect($expedition['gallery'][0]['url'])->toBe('https://example.com/ciremai-cover.jpg');

    // Custom gallery items are present in exact order
    expect($expedition['gallery'][1]['url'])->toBe('https://example.com/ciremai-pos1.jpg');
    expect($expedition['gallery'][2]['url'])->toBe('https://example.com/ciremai-kawah.jpg');
});

test('admin can update mountain route elevation checkpoints and clear gallery entirely', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $mountain = Mountain::create([
        'name' => 'Gunung Cikuray',
        'slug' => 'gunung-cikuray',
        'elevation' => 2821,
        'province' => 'Jawa Barat',
        'cover_image' => 'https://example.com/cikuray.jpg',
        'base_price' => 450000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
        'is_active' => true,
        'gallery' => [
            ['url' => 'https://example.com/cikuray-1.jpg', 'caption' => 'Pos Pemancar'],
        ],
    ]);

    $route = Route::create([
        'mountain_id' => $mountain->id,
        'name' => 'Via Pemancar',
        'slug' => 'via-pemancar',
        'grade' => TrailGrade::GradeB,
        'is_primary' => true,
        'distance_km' => 8.5,
        'duration_hours' => '6-7 Jam',
    ]);

    $updateData = [
        'name' => 'Gunung Cikuray Garut',
        'elevation' => 2821,
        'province' => 'Jawa Barat',
        'grade' => 'Grade B',
        'base_price' => 480000,
        'booking_fee_per_pax' => 150000,
        'price_lock_days_before_departure' => 3,
        // existing_gallery dikosongkan untuk menghapus semua foto galeri
        'routes' => [
            [
                'id' => $route->id,
                'name' => 'Via Pemancar Baru',
                'grade' => 'Grade B',
                'is_primary' => true,
                'distance_km' => 9.0,
                'duration_hours' => '7 Jam',
                'checkpoints' => [
                    ['name' => 'Basecamp Pemancar', 'elevation' => 1400],
                    ['name' => 'Pos 1', 'elevation' => 1750],
                    ['name' => 'Pos 2', 'elevation' => 2100],
                    ['name' => 'Pos 3', 'elevation' => 2450],
                    ['name' => 'Puncak Cikuray', 'elevation' => 2821],
                ],
                'water_note' => 'Pos 2 Mata Air Terakhir',
                'wind_note' => 'Punggungan Terbuka',
                'signal_note' => '4G Kuat di Pos 1',
                'day1_title' => 'Day 1: Menuju Camp Pos 3',
                'day1_desc' => 'Trekking menanjak konstan khas Cikuray.',
                'day1_timeline' => "08:00 - Registrasi\n16:00 - Camp di Pos 3",
                'day2_title' => 'Day 2: Summit Attack Cikuray',
                'day2_desc' => 'Sunrise samudra awan tertinggi di Garut.',
                'day2_timeline' => "04:00 - Summit\n12:00 - Basecamp",
            ],
        ],
    ];

    $response = $this->actingAs($admin)->put(route('admin.mountains.update', $mountain->id), $updateData);
    $response->assertRedirect(route('admin.mountains.index'));

    $mountain->refresh();
    $route->refresh();

    // Check mountain updated
    expect($mountain->name)->toBe('Gunung Cikuray Garut');
    expect($mountain->gallery)->toBeNull(); // Galeri berhasil dihapus

    // Check mountain elevation checkpoints synced from primary route
    expect($mountain->elevation_checkpoints)->toBeArray();
    expect($mountain->elevation_checkpoints['points'])->toHaveCount(5);
    expect($mountain->elevation_checkpoints['points'][0]['name'])->toBe('Basecamp Pemancar');
    expect($mountain->elevation_checkpoints['points'][0]['elevation'])->toBe(1400);
    expect($mountain->elevation_checkpoints['points'][4]['name'])->toBe('Puncak Cikuray');
    expect($mountain->elevation_checkpoints['points'][4]['elevation'])->toBe(2821);
    expect($mountain->elevation_checkpoints['water_note'])->toBe('Pos 2 Mata Air Terakhir');

    // Check route updated
    expect($route->name)->toBe('Via Pemancar Baru');
    expect($route->elevation_checkpoints)->toBeArray();
    expect($route->elevation_checkpoints['points'])->toHaveCount(5);
    expect($route->itinerary)->toBeArray();
    expect($route->itinerary['days'])->toHaveCount(2);
});

test('admin can store and update mountain with custom multi-day itinerary such as 3D2N or 1D tektok', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $data = [
        'name' => 'Gunung Slamet Ekspedisi',
        'elevation' => 3428,
        'province' => 'Jawa Tengah',
        'grade' => 'Grade B',
        'cover_image' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa',
        'overview' => 'Ekspedisi atap Jawa Tengah dengan opsi camp 3D2N dan tektok.',
        'base_price' => 750000,
        'booking_fee_per_pax' => 200000,
        'price_lock_days_before_departure' => 4,
        'routes' => [
            [
                'name' => 'Via Guci',
                'grade' => 'Grade B',
                'distance_km' => 17.5,
                'duration_hours' => '10-12 Jam',
                'is_primary' => true,
                'checkpoints' => [
                    ['name' => 'Basecamp Guci', 'elevation' => 1500],
                    ['name' => 'Pos 2', 'elevation' => 2200],
                    ['name' => 'Pos 4 Camp', 'elevation' => 2900],
                    ['name' => 'Puncak Surono', 'elevation' => 3428],
                ],
                'itinerary_days' => [
                    [
                        'day' => 'Day 1',
                        'title' => 'Day 1: Basecamp Guci ke Pos 2',
                        'desc' => 'Trekking santai melalui jalur hutan pinus.',
                        'timeline' => "08:00 - Registrasi & Mulai Trekking\n15:00 - Tiba di Camp Pos 2",
                    ],
                    [
                        'day' => 'Day 2',
                        'title' => 'Day 2: Pos 2 ke Pos 4 Plawangan',
                        'desc' => 'Menembus vegetasi rapat menuju batas vegetasi.',
                        'timeline' => "09:00 - Lanjut Trekking\n14:00 - Tiba di Camp Pos 4",
                    ],
                    [
                        'day' => 'Day 3',
                        'title' => 'Day 3: Summit Attack & Turun Kembali',
                        'desc' => 'Menikmati kawah Slamet dan turun ke basecamp.',
                        'timeline' => "03:30 - Summit Push ke Puncak Surono\n06:00 - Kawah Slamet\n14:00 - Tiba di Basecamp",
                    ],
                ],
            ],
            [
                'name' => 'Via Bambangan Tektok',
                'grade' => 'Grade B',
                'distance_km' => 14.0,
                'duration_hours' => '12-14 Jam',
                'is_primary' => false,
                'checkpoints' => [
                    ['name' => 'Basecamp Bambangan', 'elevation' => 1500],
                    ['name' => 'Pos 7 Samarantu', 'elevation' => 2800],
                    ['name' => 'Puncak Surono', 'elevation' => 3428],
                ],
                'itinerary_days' => [
                    [
                        'day' => 'Day 1',
                        'title' => 'Day 1: Non-stop Summit Attack & Turun',
                        'desc' => 'Tektok maraton pulang-pergi dalam 1 hari.',
                        'timeline' => "23:00 - Start Malam dari Basecamp\n06:00 - Puncak Surono Sunrise\n13:00 - Finish di Basecamp",
                    ],
                ],
            ],
        ],
        'price_tiers' => [
            ['min_pax' => 1, 'price_per_pax' => 850000],
            ['min_pax' => 5, 'price_per_pax' => 700000],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.mountains.store'), $data);
    $response->assertRedirect(route('admin.mountains.index'));

    $mountain = Mountain::where('name', 'Gunung Slamet Ekspedisi')->first();
    expect($mountain)->not->toBeNull();

    $routeGuci = $mountain->routes()->where('name', 'Via Guci')->first();
    expect($routeGuci)->not->toBeNull();
    expect($routeGuci->itinerary)->toBeArray();
    expect($routeGuci->itinerary['duration_label'])->toBe('3D2N');
    expect($routeGuci->itinerary['days_count'])->toBe(3);
    expect($routeGuci->itinerary['days'])->toHaveCount(3);
    expect($routeGuci->itinerary['title'])->toBe('Itinerary 3D2N (Via Guci)');

    $routeBambangan = $mountain->routes()->where('name', 'Via Bambangan Tektok')->first();
    expect($routeBambangan)->not->toBeNull();
    expect($routeBambangan->itinerary)->toBeArray();
    expect($routeBambangan->itinerary['duration_label'])->toBe('1D (Tek-tok)');
    expect($routeBambangan->itinerary['days_count'])->toBe(1);
    expect($routeBambangan->itinerary['days'])->toHaveCount(1);
    expect($routeBambangan->itinerary['title'])->toBe('Itinerary 1D (Tek-tok) (Via Bambangan Tektok)');

    // Customer page test
    $publicResponse = $this->get(route('ekspedisi.show', $mountain->slug));
    $publicResponse->assertOk();
    $publicResponse->assertSee('Itinerary 3D2N (Via Guci)');
    $publicResponse->assertSee('Day 1: Basecamp Guci ke Pos 2');
    $publicResponse->assertSee('Day 2: Pos 2 ke Pos 4 Plawangan');
    $publicResponse->assertSee('Day 3: Summit Attack &amp; Turun Kembali', false);

    // Edit test: Tambah menjadi 4D3N
    $updateData = [
        'name' => 'Gunung Slamet Ekspedisi 4D',
        'elevation' => 3428,
        'province' => 'Jawa Tengah',
        'grade' => 'Grade B',
        'base_price' => 750000,
        'booking_fee_per_pax' => 200000,
        'price_lock_days_before_departure' => 4,
        'routes' => [
            [
                'id' => $routeGuci->id,
                'name' => 'Via Guci Long Trip',
                'grade' => 'Grade B',
                'distance_km' => 18.0,
                'duration_hours' => '12 Jam',
                'is_primary' => true,
                'checkpoints' => $routeGuci->elevation_checkpoints['points'],
                'itinerary_days' => [
                    ['day' => 'Day 1', 'title' => 'Day 1: Aklimatisasi', 'desc' => 'Camp 1', 'timeline' => '08:00 - Start'],
                    ['day' => 'Day 2', 'title' => 'Day 2: Trekking Hutan', 'desc' => 'Camp 2', 'timeline' => '08:00 - Start'],
                    ['day' => 'Day 3', 'title' => 'Day 3: Summit Attack', 'desc' => 'Camp 3', 'timeline' => '03:30 - Summit'],
                    ['day' => 'Day 4', 'title' => 'Day 4: Turun ke Guci', 'desc' => 'Basecamp', 'timeline' => '09:00 - Turun'],
                ],
            ],
        ],
        'price_tiers' => [
            ['min_pax' => 1, 'price_per_pax' => 950000],
        ],
    ];

    $updateResponse = $this->actingAs($admin)->put(route('admin.mountains.update', $mountain->id), $updateData);
    $updateResponse->assertRedirect(route('admin.mountains.index'));

    $routeGuci->refresh();
    expect($routeGuci->itinerary['duration_label'])->toBe('4D3N');
    expect($routeGuci->itinerary['days_count'])->toBe(4);
    expect($routeGuci->itinerary['title'])->toBe('Itinerary 4D3N (Via Guci Long Trip)');
});
