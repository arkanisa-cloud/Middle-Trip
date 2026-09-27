<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingPoint extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'additional_price_per_pax' => 'integer',
            'is_default' => 'boolean',
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
