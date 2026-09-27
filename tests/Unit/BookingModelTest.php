<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\BookingParticipant;
use App\Models\Expedition;
use App\Models\Mountain;
use App\Models\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_relationships_and_status(): void
    {
        $mountain = Mountain::create([
            'name' => 'Mt. Merbabu',
            'slug' => 'mt-merbabu',
            'elevation' => 3142,
            'province' => 'Jawa Tengah',
            'cover_image' => 'https://example.com/merbabu.jpg',
            'base_price' => 500000,
            'booking_fee_per_pax' => 150000,
        ]);

        $route = Route::create([
            'mountain_id' => $mountain->id,
            'name' => 'Via Selo',
            'slug' => 'selo',
            'grade' => 'Grade A',
            'is_primary' => true,
        ]);

        $expedition = Expedition::create([
            'mountain_id' => $mountain->id,
            'route_id' => $route->id,
            'type' => 'open',
            'hiking_type' => 'camping',
            'departure_date' => now()->addDays(10)->toDateString(),
            'return_date' => now()->addDays(12)->toDateString(),
            'quota_max' => 10,
            'quota_booked' => 0,
            'status' => 'open',
        ]);

        $booking = Booking::create([
            'booking_code' => 'MT-TEST-001',
            'expedition_id' => $expedition->id,
            'route_id' => $route->id,
            'trip_type' => 'open',
            'customer_name' => 'Arkan Isa Alvaro',
            'customer_email' => 'arkan@example.com',
            'customer_phone' => '08123456789',
            'customer_nik' => '3301234567890001',
            'pax_count' => 1,
            'booking_fee_per_pax' => 150000,
            'total_booking_fee' => 150000,
            'grand_total' => 500000,
            'status' => 'open',
        ]);

        $participant = BookingParticipant::create([
            'booking_id' => $booking->id,
            'full_name' => 'Arkan Isa Alvaro',
            'nik' => '3301234567890001',
            'is_leader' => true,
        ]);

        $this->assertEquals($expedition->id, $booking->expedition->id);
        $this->assertEquals($route->id, $booking->route->id);
        $this->assertCount(1, $booking->participants);
        $this->assertTrue($booking->participants->first()->is_leader);
    }
}
