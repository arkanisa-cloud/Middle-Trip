<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
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
    protected $description = 'Expire bookings whose settlement payment deadline has passed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();

        $expiredBookings = Booking::with('expedition')
            ->where('status', 'price_locked')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', $now)
            ->get();

        $expiredCount = 0;

        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking, &$expiredCount) {
                $booking->update([
                    'status' => 'expired',
                ]);

                if ($booking->expedition) {
                    $newQuota = max(0, $booking->expedition->quota_booked - $booking->pax_count);
                    $booking->expedition->update([
                        'quota_booked' => $newQuota,
                    ]);
                }

                $expiredCount++;
            });
        }

        $this->info("Expired {$expiredCount} unpaid bookings and released quota.");

        return self::SUCCESS;
    }
}
