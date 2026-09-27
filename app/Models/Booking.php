<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pax_count' => 'integer',
            'booking_fee_per_pax' => 'integer',
            'total_booking_fee' => 'integer',
            'shuttle_fee_total' => 'integer',
            'addons_fee_total' => 'integer',
            'locked_price_per_pax' => 'integer',
            'remaining_payment_total' => 'integer',
            'grand_total' => 'integer',
            'payment_deadline' => 'datetime',
        ];
    }

    /**
     * Relasi ke batch ekspedisi.
     */
    public function expedition(): BelongsTo
    {
        return $this->belongsTo(Expedition::class);
    }

    /**
     * Relasi ke rute pendakian.
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Relasi ke meeting point shuttle.
     */
    public function meetingPoint(): BelongsTo
    {
        return $this->belongsTo(MeetingPoint::class);
    }

    /**
     * Relasi ke akun user jika login.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke seluruh peserta rombongan.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(BookingParticipant::class);
    }

    /**
     * Relasi ke perlengkapan sewa tambahan.
     */
    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'booking_addons')
            ->withPivot('price', 'quantity')
            ->withTimestamps();
    }

    /**
     * Relasi ke riwayat transaksi pembayaran.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
