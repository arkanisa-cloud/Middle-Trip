<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ExpireUnpaidBookingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire unpaid bookings that exceeded the 24-hour window or settlement deadline';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /** @var Collection<int, Booking> $expiredBookings */
        $expiredBookings = Booking::with('expedition')
            ->unpaidExpired()
            ->get();

        $expiredCount = 0;

        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking, &$expiredCount) {
                $booking->markAsExpired();
                $expiredCount++;
            });
        }

        $this->info("Expired {$expiredCount} unpaid bookings and synchronized quota.");

        return self::SUCCESS;
    }
}
