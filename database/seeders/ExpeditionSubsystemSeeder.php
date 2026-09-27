<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Expedition;
use App\Models\ExpeditionPriceTier;
use App\Models\MeetingPoint;
use App\Models\Mountain;
use Illuminate\Database\Seeder;

class ExpeditionSubsystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan MountainSeeder sudah berjalan
        if (Mountain::count() === 0) {
            $this->call(MountainSeeder::class);
        }

        // 2. Seed Addons (Perlengkapan Sewa Tambahan)
        $addons = [
            ['name' => 'Hydropack', 'category' => 'gear', 'price' => 15000, 'is_active' => true],
            ['name' => 'Trekking Pole', 'category' => 'gear', 'price' => 10000, 'is_active' => true],
            ['name' => 'Matras Gulung Tambahan', 'category' => 'gear', 'price' => 12000, 'is_active' => true],
            ['name' => 'Headlamp', 'category' => 'gear', 'price' => 10000, 'is_active' => true],
        ];

        foreach ($addons as $addonData) {
            Addon::firstOrCreate(['name' => $addonData['name']], $addonData);
        }

        // 3. Konfigurasi Setting & Tiers per Gunung
        $mountainsConfig = [
            'mt-merbabu' => [
                'booking_fee' => 150000,
                'price_lock_days' => 3,
                'tiers' => [
                    ['min_pax' => 1, 'max_pax' => 3, 'price_per_pax' => 650000],
                    ['min_pax' => 4, 'max_pax' => 6, 'price_per_pax' => 550000],
                    ['min_pax' => 7, 'max_pax' => 10, 'price_per_pax' => 475000],
                ],
                'meeting_points' => [
                    ['name' => 'Basecamp Selo (Boyolali)', 'location_type' => 'basecamp', 'additional_price_per_pax' => 0, 'is_default' => true],
                    ['name' => 'Shuttle Stasiun Solo Balapan', 'location_type' => 'station', 'additional_price_per_pax' => 75000, 'is_default' => false],
                    ['name' => 'Shuttle Stasiun Tugu Jogja', 'location_type' => 'station', 'additional_price_per_pax' => 100000, 'is_default' => false],
                ],
            ],
            'mt-sindoro' => [
                'booking_fee' => 175000,
                'price_lock_days' => 3,
                'tiers' => [
                    ['min_pax' => 1, 'max_pax' => 3, 'price_per_pax' => 750000],
                    ['min_pax' => 4, 'max_pax' => 6, 'price_per_pax' => 650000],
                    ['min_pax' => 7, 'max_pax' => 10, 'price_per_pax' => 580000],
                ],
                'meeting_points' => [
                    ['name' => 'Basecamp Kledung (Temanggung)', 'location_type' => 'basecamp', 'additional_price_per_pax' => 0, 'is_default' => true],
                    ['name' => 'Shuttle Terminal Wonosobo', 'location_type' => 'terminal', 'additional_price_per_pax' => 60000, 'is_default' => false],
                ],
            ],
            'mt-prau' => [
                'booking_fee' => 150000,
                'price_lock_days' => 2,
                'tiers' => [
                    ['min_pax' => 1, 'max_pax' => 3, 'price_per_pax' => 600000],
                    ['min_pax' => 4, 'max_pax' => 6, 'price_per_pax' => 500000],
                    ['min_pax' => 7, 'max_pax' => 10, 'price_per_pax' => 450000],
                ],
                'meeting_points' => [
                    ['name' => 'Basecamp Patakbanteng (Dieng)', 'location_type' => 'basecamp', 'additional_price_per_pax' => 0, 'is_default' => true],
                    ['name' => 'Shuttle Terminal Mendolo Wonosobo', 'location_type' => 'terminal', 'additional_price_per_pax' => 50000, 'is_default' => false],
                ],
            ],
        ];

        foreach ($mountainsConfig as $slug => $config) {
            $mountain = Mountain::where('slug', $slug)->first();
            if (! $mountain) {
                continue;
            }

            $mountain->update([
                'booking_fee_per_pax' => $config['booking_fee'],
                'price_lock_days_before_departure' => $config['price_lock_days'],
            ]);

            // Price Tiers
            foreach ($config['tiers'] as $tier) {
                ExpeditionPriceTier::firstOrCreate([
                    'mountain_id' => $mountain->id,
                    'min_pax' => $tier['min_pax'],
                    'max_pax' => $tier['max_pax'],
                ], [
                    'price_per_pax' => $tier['price_per_pax'],
                ]);
            }

            // Meeting Points
            foreach ($config['meeting_points'] as $mp) {
                MeetingPoint::firstOrCreate([
                    'mountain_id' => $mountain->id,
                    'name' => $mp['name'],
                ], $mp);
            }

            // Batch Expeditions
            $primaryRoute = $mountain->primaryRoute ?? $mountain->routes()->first();
            if ($primaryRoute) {
                // Open Trip
                Expedition::firstOrCreate([
                    'mountain_id' => $mountain->id,
                    'route_id' => $primaryRoute->id,
                    'type' => 'open',
                    'departure_date' => now()->addDays(14)->toDateString(),
                ], [
                    'hiking_type' => 'camping',
                    'return_date' => now()->addDays(15)->toDateString(),
                    'quota_max' => 10,
                    'quota_booked' => 5,
                    'status' => 'open',
                ]);

                // Private Trip
                Expedition::firstOrCreate([
                    'mountain_id' => $mountain->id,
                    'route_id' => $primaryRoute->id,
                    'type' => 'private',
                    'departure_date' => now()->addDays(20)->toDateString(),
                ], [
                    'hiking_type' => 'camping',
                    'return_date' => now()->addDays(21)->toDateString(),
                    'quota_max' => 10,
                    'quota_booked' => 0,
                    'status' => 'open',
                ]);
            }
        }
    }
}
