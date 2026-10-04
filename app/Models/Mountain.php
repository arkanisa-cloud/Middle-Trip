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
     * Mendapatkan harga per pax berdasarkan jumlah peserta akumulasi dari tier matriks.
     */
    public function getTierPriceForPax(int $pax, string $hikingType = 'camping'): int
    {
        $tiers = $this->priceTiers()->get();

        $campingPrice = $this->base_price;

        if ($tiers->isNotEmpty()) {
            // Cari tier dengan min_pax tertinggi yang memenuhi kuota pax
            $matchingTier = $tiers->where('min_pax', '<=', $pax)->sortByDesc('min_pax')->first();

            if ($matchingTier) {
                $campingPrice = $matchingTier->price_per_pax;
            } else {
                $lowestTier = $tiers->sortBy('min_pax')->first();
                $campingPrice = $lowestTier?->price_per_pax ?? $this->base_price;
            }
        }

        if ($hikingType === 'tektok') {
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
