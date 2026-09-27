<?php

namespace App\Models;

use App\Enums\TrailGrade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        ];
    }

    /**
     * Relasi ke gunung induk.
     */
    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }
}
