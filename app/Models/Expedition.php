<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expedition extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'quota_max' => 'integer',
            'quota_booked' => 'integer',
            'current_locked_price' => 'integer',
        ];
    }

    /**
     * Relasi ke gunung induk.
     */
    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }

    /**
     * Relasi ke jalur pendakian.
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Relasi ke seluruh booking di batch trip ini.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Menghitung harga terkunci per pax berdasarkan kuota peserta terdaftar.
     */
    public function calculatePriceLockPrice(): int
    {
        return $this->mountain->getTierPriceForPax($this->quota_booked);
    }

    /**
     * Scope untuk ekspedisi yang masih open.
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }
}
