<?php

namespace App\Models;

use App\Enums\TrailGrade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grade' => TrailGrade::class,
            'is_primary' => 'boolean',
            'distance_km' => 'float',
            'elevation_checkpoints' => 'array',
            'itinerary' => 'array',
            'price_camping_open' => 'integer',
            'price_tektok_open' => 'integer',
            'price_camping_private' => 'integer',
            'price_tektok_private' => 'integer',
            'booking_fee_per_pax' => 'integer',
        ];
    }

    /**
     * Besaran DP / Booking Fee per pax untuk jalur ini.
     */
    public function getEffectiveBookingFeePerPaxAttribute(): int
    {
        return $this->booking_fee_per_pax ?? $this->mountain?->booking_fee_per_pax ?? (int) round(($this->effective_price_camping_open * 0.3));
    }

    /**
     * Harga efektif camping open trip pada jalur ini.
     */
    public function getEffectivePriceCampingOpenAttribute(): int
    {
        return $this->price_camping_open ?? $this->mountain?->base_price ?? 0;
    }

    /**
     * Harga efektif tektok open trip pada jalur ini.
     */
    public function getEffectivePriceTektokOpenAttribute(): int
    {
        return $this->price_tektok_open ?? (int) round($this->effective_price_camping_open * 0.8);
    }

    /**
     * Harga efektif camping private trip pada jalur ini.
     */
    public function getEffectivePriceCampingPrivateAttribute(): int
    {
        return $this->price_camping_private ?? $this->mountain?->price_private ?? (int) round($this->effective_price_camping_open * 1.5);
    }

    /**
     * Harga efektif tektok private trip pada jalur ini.
     */
    public function getEffectivePriceTektokPrivateAttribute(): int
    {
        return $this->price_tektok_private ?? (int) round($this->effective_price_camping_private * 0.85);
    }

    /**
     * Relasi ke gunung induk.
     */
    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }

    /**
     * Relasi ke seluruh ekspedisi yang melewati jalur ini.
     */
    public function expeditions(): HasMany
    {
        return $this->hasMany(Expedition::class);
    }

    /**
     * Relasi ke seluruh booking di jalur ini.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
