<?php

namespace App\Models;

use Carbon\Carbon;
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
            'departure_date' => 'date',
            'return_date' => 'date',
        ];
    }

    /**
     * Accessor tanggal keberangkatan dengan fallback ke batch ekspedisi.
     */
    public function getDepartureDateAttribute($value): ?Carbon
    {
        if ($value) {
            return Carbon::parse($value);
        }

        return $this->expedition?->departure_date ? Carbon::parse($this->expedition->departure_date) : null;
    }

    /**
     * Accessor tanggal kepulangan dengan fallback ke batch ekspedisi.
     */
    public function getReturnDateAttribute($value): ?Carbon
    {
        if ($value) {
            return Carbon::parse($value);
        }

        return $this->expedition?->return_date ? Carbon::parse($this->expedition->return_date) : null;
    }

    /**
     * Relasi ke batch ekspedisi.
     */
    public function expedition(): BelongsTo
    {
        return $this->belongsTo(Expedition::class);
    }

    /**
     * Relasi ke destinasi gunung melalui rute pendakian.
     */
    public function mountain()
    {
        return $this->hasOneThrough(Mountain::class, Route::class, 'id', 'id', 'route_id', 'mountain_id');
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
        return $this->hasMany(BookingParticipant::class)->orderByDesc('is_leader')->orderBy('id');
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

    /**
     * Alias relasi ke riwayat transaksi pembayaran.
     */
    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
