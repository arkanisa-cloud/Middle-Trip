<?php

namespace Tests\Feature;

use App\Models\Mountain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenerateSitemapCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        if (file_exists(public_path('sitemap.xml'))) {
            unlink(public_path('sitemap.xml'));
        }

        parent::tearDown();
    }

    public function test_sitemap_generate_command_creates_valid_sitemap_xml(): void
    {
        $this->seed(\Database\Seeders\MountainSeeder::class);

        $activeMountain = Mountain::where('is_active', true)->first();
        $this->assertNotNull($activeMountain);

        // Mark another mountain as inactive
        $inactiveMountain = Mountain::where('id', '!=', $activeMountain->id)->first();
        $inactiveMountain->update(['is_active' => false]);

        $this->artisan('sitemap:generate')->assertSuccessful();

        $this->assertFileExists(public_path('sitemap.xml'));

        $content = file_get_contents(public_path('sitemap.xml'));

        $this->assertStringContainsString(route('home'), $content);
        $this->assertStringContainsString(route('ekspedisi.index'), $content);
        $this->assertStringContainsString(route('ekspedisi.show', $activeMountain->slug), $content);
        $this->assertStringNotContainsString(route('ekspedisi.show', $inactiveMountain->slug), $content);
    }
}
