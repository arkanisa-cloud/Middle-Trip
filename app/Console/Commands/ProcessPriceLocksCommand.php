<?php

namespace App\Console\Commands;

use App\Models\Expedition;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Console\Command;

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

            $priceLockDays = (int) ($expedition->effective_price_lock_days);
            $lockThresholdDate = Carbon::parse($expedition->departure_date)->subDays($priceLockDays);

            // Jika hari ini sudah mencapai atau melewati tanggal threshold price lock
            if ($today->gte($lockThresholdDate)) {
                app(BookingService::class)->executePriceLock($expedition);
                $processedCount++;
            }
        }

        $this->info("Processed {$processedCount} expeditions for price locking.");

        return self::SUCCESS;
    }
}
