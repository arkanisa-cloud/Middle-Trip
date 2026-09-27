<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Mountain;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_expedition_subsystem_seeder_populates_full_dataset(): void
    {
        $this->seed(ExpeditionSubsystemSeeder::class);

        $merbabu = Mountain::where('slug', 'mt-merbabu')->first();
        $this->assertNotNull($merbabu);
        $this->assertEquals(150000, $merbabu->booking_fee_per_pax);
        $this->assertCount(3, $merbabu->priceTiers);
        $this->assertGreaterThanOrEqual(1, $merbabu->meetingPoints()->count());
        $this->assertGreaterThanOrEqual(1, $merbabu->expeditions()->count());

        $this->assertGreaterThanOrEqual(4, Addon::count());
    }
}
