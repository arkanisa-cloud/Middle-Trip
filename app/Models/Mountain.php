<?php

namespace App\Models;

use App\Enums\TrailGrade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mountain extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'elevation' => 'integer',
            'has_open_trip' => 'boolean',
            'has_private_trip' => 'boolean',
            'base_price' => 'integer',
            'price_private' => 'integer',
            'price_tektok' => 'integer',
            'price_private_tektok' => 'integer',
            'booking_fee_per_pax' => 'integer',
            'price_lock_days_before_departure' => 'integer',
            'is_featured' => 'boolean',
            'featured_order' => 'integer',
            'is_active' => 'boolean',
            'elevation_checkpoints' => 'array',
            'facilities_included' => 'array',
            'facilities_excluded' => 'array',
            'gallery' => 'array',
        ];
    }

    /**
     * Relasi ke seluruh jalur pendakian gunung ini.
     */
    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    /**
     * Jalur utama/rekomendasi gunung ini (digunakan sebagai default badge grade).
     */
    public function primaryRoute(): HasOne
    {
        return $this->hasOne(Route::class)->where('is_primary', true);
    }

    /**
     * Relasi ke matriks tier harga dinamis.
     */
    public function priceTiers(): HasMany
    {
        return $this->hasMany(ExpeditionPriceTier::class)->orderBy('min_pax');
    }

    /**
     * Relasi ke batch ekspedisi pendakian.
     */
    public function expeditions(): HasMany
    {
        return $this->hasMany(Expedition::class);
    }

    /**
     * Relasi ke opsi meeting point shuttle.
     */
    public function meetingPoints(): HasMany
    {
        return $this->hasMany(MeetingPoint::class);
    }

    /**
     * Mendapatkan harga dasar tektok (dengan fallback proporsional 80% dari harga camping).
     */
    public function getEffectivePriceTektokAttribute(): int
    {
        return $this->price_tektok ?? (int) round($this->base_price * 0.8);
    }

    /**
     * Mendapatkan harga private tektok (dengan fallback proporsional).
     */
    public function getEffectivePricePrivateTektokAttribute(): int
    {
        $basePrivate = $this->price_private ?? (int) round($this->base_price * 1.5);

        return $this->price_private_tektok ?? (int) round($basePrivate * 0.85);
    }

    /**
     * Mendapatkan harga per pax berdasarkan jumlah peserta akumulasi dari tier matriks (dengan dukungan penyesuaian rute).
     */
    public function getTierPriceForPax(int $pax, string $hikingType = 'camping', ?Route $route = null): int
    {
        // 1. Jika rute memiliki custom price_tiers tersimpan
        if ($route && ! empty($route->price_tiers) && is_array($route->price_tiers)) {
            $routeTiers = collect($route->price_tiers);
            $matchingTier = $routeTiers->where('min_pax', '<=', $pax)->sortByDesc('min_pax')->first();
            if (! $matchingTier) {
                $matchingTier = $routeTiers->sortBy('min_pax')->first();
            }

            if ($matchingTier) {
                if ($hikingType === 'tektok') {
                    $tektok = $matchingTier['price_per_pax_tektok'] ?? null;
                    if ($tektok !== null && (int) $tektok > 0) {
                        return (int) $tektok;
                    }
                    $camp = (int) ($matchingTier['price_per_pax'] ?? $route->effective_price_camping_open);

                    return (int) round($camp * 0.8);
                }

                if (! empty($matchingTier['price_per_pax'])) {
                    return (int) $matchingTier['price_per_pax'];
                }
            }
        }

        // 2. Ambil tier induk gunung
        $tiers = $this->priceTiers()->get();
        $campingPrice = $this->base_price;
        $tektokPrice = null;

        if ($tiers->isNotEmpty()) {
            $matchingTier = $tiers->where('min_pax', '<=', $pax)->sortByDesc('min_pax')->first();

            if ($matchingTier) {
                $campingPrice = $matchingTier->price_per_pax;
                $tektokPrice = $matchingTier->price_per_pax_tektok;
            } else {
                $lowestTier = $tiers->sortBy('min_pax')->first();
                $campingPrice = $lowestTier?->price_per_pax ?? $this->base_price;
                $tektokPrice = $lowestTier?->price_per_pax_tektok;
            }
        }

        // 3. Jika ada rute spesifik, hitung selisih harga rute terhadap base price gunung
        if ($route) {
            if ($hikingType === 'tektok') {
                $baseTektok = $this->effective_price_tektok;
                $routeTektok = $route->price_tektok_open !== null ? (int) $route->price_tektok_open : $baseTektok;
                $deltaTektok = $routeTektok - $baseTektok;

                $effectiveTektokTier = ($tektokPrice !== null && $tektokPrice > 0)
                    ? (int) $tektokPrice
                    : (int) round($campingPrice * ($this->base_price > 0 && $this->price_tektok ? ($this->price_tektok / $this->base_price) : 0.8));

                return max(0, $effectiveTektokTier + $deltaTektok);
            }

            $routeCamping = $route->price_camping_open !== null ? (int) $route->price_camping_open : $this->base_price;
            $deltaCamping = $routeCamping - $this->base_price;

            return max(0, $campingPrice + $deltaCamping);
        }

        // Standar tanpa rute spesifik (mengacu ke base gunung)
        if ($hikingType === 'tektok') {
            if ($tektokPrice !== null && $tektokPrice > 0) {
                return (int) $tektokPrice;
            }

            if ($this->price_tektok !== null && $this->base_price > 0) {
                $ratio = $this->price_tektok / $this->base_price;

                return (int) round($campingPrice * $ratio);
            }

            return (int) round($campingPrice * 0.8);
        }

        return $campingPrice;
    }

    /**
     * Format elevasi dalam standar MDPL Indonesia (contoh: "3.142 MDPL").
     */
    public function getFormattedElevationAttribute(): string
    {
        return number_format($this->elevation, 0, ',', '.').' MDPL';
    }

    /**
     * Mendapatkan harga awal termurah dari seluruh via/jalur aktif (Opsi A: Mulai dari Rp...).
     */
    public function getEffectiveStartingPriceAttribute(): int
    {
        $minRoutePrice = $this->routes
            ->filter(fn ($r) => ($r->price_camping_open ?? 0) > 0)
            ->min('price_camping_open');

        return $minRoutePrice ?: ($this->base_price ?: 0);
    }

    /**
     * Format harga dasar dalam Rupiah (contoh: "Rp 500.000").
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->effective_starting_price, 0, ',', '.');
    }

    /**
     * Format harga ringkas (contoh: "Rp 800k" atau "Rp 2.100k").
     */
    public function getFormattedShortPriceAttribute(): string
    {
        $price = $this->effective_starting_price;
        if ($price >= 1000) {
            $thousands = $price / 1000;

            return 'Rp '.number_format($thousands, 0, ',', '.').'k';
        }

        return $this->getFormattedPriceAttribute();
    }

    /**
     * Grade default dari jalur utama.
     */
    public function getDefaultGradeAttribute(): ?TrailGrade
    {
        if ($this->relationLoaded('primaryRoute') && $this->primaryRoute !== null) {
            return $this->primaryRoute->grade;
        }

        return $this->primaryRoute()->first()?->grade ?? $this->routes()->first()?->grade;
    }

    /**
     * Scope untuk gunung yang aktif ditampilkan.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk gunung yang masuk Bento Grid pilihan utama.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
