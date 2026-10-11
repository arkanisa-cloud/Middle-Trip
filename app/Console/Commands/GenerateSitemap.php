<?php

namespace App\Console\Commands;

use App\Models\Mountain;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemap untuk Google Search dan mesin pencari lainnya';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai pembuatan sitemap XML...');

        $sitemap = Sitemap::create();

        // 1. Halaman Statis Utama
        $sitemap->add(
            Url::create(route('home'))
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        $sitemap->add(
            Url::create(route('ekspedisi.index'))
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        if (Route::has('contact')) {
            $sitemap->add(
                Url::create(route('contact'))
                    ->setPriority(0.5)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        }

        if (Route::has('privacy.policy')) {
            $sitemap->add(
                Url::create(route('privacy.policy'))
                    ->setPriority(0.3)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            );
        }

        if (Route::has('terms.service')) {
            $sitemap->add(
                Url::create(route('terms.service'))
                    ->setPriority(0.3)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            );
        }

        // 2. Data Produk Ekspedisi Gunung Dinamis (Hanya Gunung yang Aktif)
        $mountainCount = 0;
        Mountain::query()
            ->active()
            ->get()
            ->each(function (Mountain $mountain) use ($sitemap, &$mountainCount) {
                if (empty($mountain->slug)) {
                    return;
                }

                $sitemap->add(
                    Url::create(route('ekspedisi.show', $mountain->slug))
                        ->setLastModificationDate($mountain->updated_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );

                $mountainCount++;
            });

        // 3. Simpan hasilnya ke direktori public/sitemap.xml
        $outputPath = public_path('sitemap.xml');
        $sitemap->writeToFile($outputPath);

        $this->info("✓ Sitemap berhasil di-generate! ({$mountainCount} destinasi gunung aktif ditambahkan)");
        $this->info("✓ Lokasi file: {$outputPath}");

        return Command::SUCCESS;
    }
}
