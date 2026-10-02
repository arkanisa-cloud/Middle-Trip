<?php

namespace Tests\Feature;

use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    /**
     * Memastikan halaman katalog ekspedisi dapat diakses dengan sukses.
     */
    public function test_catalog_page_returns_successful_response(): void
    {
        $response = $this->get('/ekspedisi');

        $response->assertStatus(200);
        $response->assertSee('Jelajahi Puncak Indonesia');
        $response->assertSee('Katalog Ekspedisi 2026');
        $response->assertSee('Mt. Merbabu');
    }

    /**
     * Memastikan rute alias /katalog me-redirect ke /ekspedisi.
     */
    public function test_katalog_alias_redirects_to_ekspedisi(): void
    {
        $response = $this->get('/katalog');

        $response->assertRedirect('/ekspedisi');
    }

    /**
     * Memastikan halaman home memuat tautan ke halaman ekspedisi.
     */
    public function test_home_page_contains_catalog_link(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('/ekspedisi');
    }
}
