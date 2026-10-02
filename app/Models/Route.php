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
