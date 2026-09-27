<?php

namespace App\Console\Commands;

use App\Models\Expedition;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessPriceLocksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expeditions:process-price-locks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process price locks for expeditions nearing departure date';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();

        // Cari seluruh open trip yang masih open dan memiliki relasi mountain
        $expeditions = Expedition::with(['mountain.priceTiers', 'bookings' => function ($query) {
            $query->where('status', 'reserved');
        }])
            ->where('type', 'open')
            ->where('status', 'open')
            ->get();

        $processedCount = 0;

        foreach ($expeditions as $expedition) {
            $mountain = $expedition->mountain;
            if (! $mountain) {
                continue;
            }

            $priceLockDays = (int) ($mountain->price_lock_days_before_departure ?? 3);
            $lockThresholdDate = Carbon::parse($expedition->departure_date)->subDays($priceLockDays);

            // Jika hari ini sudah mencapai atau melewati tanggal threshold price lock
            if ($today->gte($lockThresholdDate)) {
                DB::transaction(function () use ($expedition, $mountain, &$processedCount) {
                    $paxCount = max(1, (int) $expedition->quota_booked);
                    $lockedPricePerPax = $mountain->getTierPriceForPax($paxCount);

                    $expedition->update([
                        'status' => 'price_locked',
                        'current_locked_price' => $lockedPricePerPax,
                    ]);

                    $paymentDeadline = Carbon::now()->addHours(48);

                    foreach ($expedition->bookings as $booking) {
                        $ticketTotal = $booking->pax_count * $lockedPricePerPax;
                        $shuttleTotal = (int) ($booking->shuttle_fee_total ?? 0);
                        $addonsTotal = (int) ($booking->addons_fee_total ?? 0);
                        $grandTotal = $ticketTotal + $shuttleTotal + $addonsTotal;
                        $remaining = max(0, $grandTotal - (int) $booking->total_booking_fee);

                        $booking->update([
                            'status' => 'price_locked',
                            'locked_price_per_pax' => $lockedPricePerPax,
                            'grand_total' => $grandTotal,
                            'remaining_payment_total' => $remaining,
                            'payment_deadline' => $paymentDeadline,
                        ]);
                    }

                    $processedCount++;
                });
            }
        }

        $this->info("Processed {$processedCount} expeditions for price locking.");

        return self::SUCCESS;
    }
}
