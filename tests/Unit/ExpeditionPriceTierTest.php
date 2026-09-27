<?php

namespace Tests\Unit;

use App\Models\ExpeditionPriceTier;
use App\Models\Mountain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionPriceTierTest extends TestCase
{
    use RefreshDatabase;

    public function test_mountain_returns_correct_tier_price_for_pax_count(): void
    {
        $mountain = Mountain::create([
            'name' => 'Mt. Merbabu',
            'slug' => 'mt-merbabu',
            'elevation' => 3142,
            'province' => 'Jawa Tengah',
            'cover_image' => 'https://example.com/merbabu.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 150000,
            'price_lock_days_before_departure' => 3,
        ]);

        ExpeditionPriceTier::create([
            'mountain_id' => $mountain->id,
            'min_pax' => 1,
            'max_pax' => 3,
            'price_per_pax' => 650000,
        ]);

        ExpeditionPriceTier::create([
            'mountain_id' => $mountain->id,
            'min_pax' => 4,
            'max_pax' => 6,
            'price_per_pax' => 550000,
        ]);

        ExpeditionPriceTier::create([
            'mountain_id' => $mountain->id,
            'min_pax' => 7,
            'max_pax' => 10,
            'price_per_pax' => 475000,
        ]);

        $this->assertEquals(650000, $mountain->getTierPriceForPax(2));
        $this->assertEquals(550000, $mountain->getTierPriceForPax(5));
        $this->assertEquals(475000, $mountain->getTierPriceForPax(8));
        // Fallback boundary jika melebihi max_pax
        $this->assertEquals(475000, $mountain->getTierPriceForPax(12));
    }
}
