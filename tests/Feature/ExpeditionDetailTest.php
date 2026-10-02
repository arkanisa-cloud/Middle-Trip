<?php

namespace Tests\Feature;

use App\Models\Mountain;
use App\Models\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionDetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Uji halaman detail ekspedisi dapat diakses via slug yang valid dari database.
     */
    public function test_can_view_expedition_detail_page(): void
    {
        $mountain = Mountain::create([
            'name' => 'Mt. Merbabu',
            'slug' => 'mt-merbabu',
            'elevation' => 3142,
            'province' => 'Jawa Tengah',
            'cover_image' => 'https://example.com/custom-merbabu.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 150000,
            'price_lock_days_before_departure' => 3,
        ]);

        Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Suwanting Custom',
            'slug' => 'via-suwanting-custom',
            'grade' => 'Grade B',
            'is_primary' => true,
            'distance_km' => 15.0,
            'duration_hours' => '8-9 Jam',
        ]);

        $response = $this->get('/ekspedisi/mt-merbabu');

        $response->assertStatus(200);
        $response->assertSee('Mt. Merbabu Expedition');
        $response->assertSee('3.142 mdpl');
        $response->assertSee('Via Suwanting Custom');
        $response->assertSee('https://example.com/custom-merbabu.jpg');
        // Pastikan tidak ada data mock otomatis yang bocor
        $response->assertDontSee('/storage/mountains/merbabu.jpeg');
        $response->assertDontSee('Via Selo');
        $response->assertDontSee('Jalur Utama');
    }

    /**
     * Uji gunung tanpa rute tidak menghasilkan rute tiruan (Jalur Utama) secara otomatis.
     */
    public function test_mountain_without_routes_does_not_auto_generate_routes(): void
    {
        Mountain::create([
            'name' => 'Gunung Anyar',
            'slug' => 'gunung-anyar',
            'elevation' => 2000,
            'province' => 'Jawa Timur',
            'cover_image' => 'https://example.com/anyar.jpg',
            'base_price' => 300000,
            'booking_fee_per_pax' => 100000,
            'price_lock_days_before_departure' => 2,
        ]);

        $response = $this->get('/ekspedisi/gunung-anyar');

        $response->assertStatus(200);
        $response->assertSee('Gunung Anyar Expedition');
        $response->assertDontSee('Jalur Utama');
        $response->assertDontSee('Via Selo');
    }

    /**
     * Uji bahwa mengakses slug yang tidak terdaftar akan mengembalikan 404.
     */
    public function test_non_existent_expedition_returns_404(): void
    {
        $response = $this->get('/ekspedisi/gunung-tidak-ada');

        $response->assertStatus(404);
    }
}
