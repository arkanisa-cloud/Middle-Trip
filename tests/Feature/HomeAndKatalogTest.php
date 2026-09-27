<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeAndKatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHasAll(['mountains', 'featuredHero', 'featuredCards']);

        // Pastikan featured hero Merbabu dan featured card Slamet tampil
        $response->assertSee('Mt. Merbabu');
        $response->assertSee('Mt. Slamet');
    }

    public function test_katalog_page_displays_all_mountains(): void
    {
        $response = $this->get(route('ekspedisi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('customer.shop');
        $response->assertViewHas('mountains');

        $mountains = $response->viewData('mountains');
        $this->assertCount(8, $mountains);

        $response->assertSee('Mt. Merbabu');
        $response->assertSee('Mt. Slamet');
        $response->assertSee('Mt. Prau');
    }

    public function test_katalog_filters_by_open_trip_only(): void
    {
        $response = $this->get(route('ekspedisi.index', ['type' => 'open']));

        $response->assertStatus(200);
        $response->assertSee('Mt. Merbabu');
        // Mt. Prau diset has_open_trip = false
        $response->assertDontSee('Mt. Prau');
    }

    public function test_katalog_filters_by_private_trip_only(): void
    {
        $response = $this->get(route('ekspedisi.index', ['type' => 'private']));

        $response->assertStatus(200);
        $response->assertSee('Mt. Prau');
        // Mt. Papandayan diset has_private_trip = false
        $response->assertDontSee('Mt. Papandayan');
    }

    public function test_katalog_filters_by_grade(): void
    {
        // Grade C primary route: Mt. Slamet & Mt. Rinjani
        $response = $this->get(route('ekspedisi.index', ['grade' => 'Grade C']));

        $response->assertStatus(200);
        $response->assertSee('Mt. Slamet');
        $response->assertSee('Mt. Rinjani');
        $response->assertDontSee('Mt. Merbabu');
        $response->assertDontSee('Mt. Prau');
    }

    public function test_katalog_filters_by_search_query(): void
    {
        $response = $this->get(route('ekspedisi.index', ['q' => 'Rinjani']));

        $response->assertStatus(200);
        $response->assertSee('Mt. Rinjani');
        $response->assertDontSee('Mt. Merbabu');
        $response->assertDontSee('Mt. Slamet');
    }
}
