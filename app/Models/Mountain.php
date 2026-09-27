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
            'is_featured' => 'boolean',
            'featured_order' => 'integer',
            'is_active' => 'boolean',
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
     * Format elevasi dalam standar MDPL Indonesia (contoh: "3.142 MDPL").
     */
    public function getFormattedElevationAttribute(): string
    {
        return number_format($this->elevation, 0, ',', '.').' MDPL';
    }

    /**
     * Format harga dasar dalam Rupiah (contoh: "Rp 500.000").
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->base_price, 0, ',', '.');
    }

    /**
     * Format harga ringkas (contoh: "Rp 800k" atau "Rp 2.100k").
     */
    public function getFormattedShortPriceAttribute(): string
    {
        if ($this->base_price >= 1000) {
            $thousands = $this->base_price / 1000;

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
