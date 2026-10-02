<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingParticipant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_leader' => 'boolean',
        ];
    }

    /**
     * Relasi ke booking induk.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Accessor untuk kompatibilitas jika dipanggil via $participant->name.
     */
    public function getNameAttribute(): string
    {
        return (string) ($this->attributes['full_name'] ?? '');
    }
}
