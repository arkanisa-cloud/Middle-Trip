<?php

namespace Tests\Feature;

use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionDetailViewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_detail_page_loads_with_real_mountain_and_booking_modal(): void
    {
        $response = $this->get(route('ekspedisi.show', 'mt-merbabu'));

        $response->assertOk();
        $response->assertSee('Mt. Merbabu');
        $response->assertSee('Booking Sekarang');
        $response->assertSee('bookingModalComponent');
        $response->assertSee('paxCountDisplay', false);
    }
}
