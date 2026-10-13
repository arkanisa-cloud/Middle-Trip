<?php

namespace Tests\Feature;

use App\Models\Mountain;
use App\Models\Route;
use Database\Seeders\ExpeditionSubsystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElevationProfileRenderingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExpeditionSubsystemSeeder::class);
    }

    public function test_standard_mountain_elevation_profile_renders_correctly(): void
    {
        $mountain = Mountain::where('slug', 'mt-merbabu')->firstOrFail();

        $response = $this->get(route('ekspedisi.show', $mountain->slug));

        $response->assertOk();
        $response->assertSee('elevation-svg');
        $response->assertSee('elevation-points-group');
    }

    public function test_mountain_with_many_checkpoints_expands_svg_width_and_staggers_labels(): void
    {
        $mountain = Mountain::where('slug', 'mt-merbabu')->firstOrFail();

        // Buat atau perbarui rute pertama dengan 10 checkpoints (Basecamp, Pos 1 s.d. Pos 8, Puncak)
        $checkpoints = [
            ['name' => 'Basecamp', 'elevation' => 1500],
            ['name' => 'Pos 1 (Mata Air)', 'elevation' => 1800],
            ['name' => 'Pos 2 (Hutan Cemara)', 'elevation' => 2100],
            ['name' => 'Pos 3 (Batu Putih)', 'elevation' => 2400],
            ['name' => 'Pos 4 (Shelter)', 'elevation' => 2650],
            ['name' => 'Pos 5 (Sabana Bawah)', 'elevation' => 2850],
            ['name' => 'Pos 6 (Sabana Atas)', 'elevation' => 3000],
            ['name' => 'Pos 7 (Punggungan)', 'elevation' => 3150],
            ['name' => 'Pos 8 (Kawah Mati)', 'elevation' => 3280],
            ['name' => 'Puncak Tertinggi', 'elevation' => 3428],
        ];

        $route = $mountain->routes()->first();
        if ($route) {
            $route->update([
                'elevation_checkpoints' => [
                    'points' => $checkpoints,
                    'water_note' => 'Pos 1 & Pos 4',
                    'wind_note' => 'Pos 6 ke atas',
                    'signal_note' => 'Basecamp saja',
                ],
            ]);
        } else {
            Route::create([
                'mountain_id' => $mountain->id,
                'name' => 'Jalur Ekstrem Banyak Pos',
                'slug' => 'jalur-ekstrem-banyak-pos',
                'grade' => 'Grade A',
                'is_active' => true,
                'elevation_checkpoints' => [
                    'points' => $checkpoints,
                    'water_note' => 'Pos 1 & Pos 4',
                    'wind_note' => 'Pos 6 ke atas',
                    'signal_note' => 'Basecamp saja',
                ],
            ]);
        }

        $response = $this->get(route('ekspedisi.show', $mountain->slug));

        $response->assertOk();
        // SVG canvas should be expanded to dynamic width (> 700px)
        // With 10 points: (10 * 115) + 80 = 1230px
        $response->assertSee('min-width: 1230px', false);
        $response->assertSee('viewBox="0 0 1230 250"', false);
        // Hint badge for scroll should be rendered
        $response->assertSee('Geser grafik rute');
    }
}
