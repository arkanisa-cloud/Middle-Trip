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

    /**
     * Memeriksa apakah pesanan sudah kedaluwarsa karena melewati batas waktu 24 jam atau deadline pelunasan.
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        // Pesanan Open (Belum bayar DP / Full payment)
        if ($this->status === 'open') {
            if ($this->payment_deadline && $this->payment_deadline->isPast()) {
                return true;
            }

            if ($this->created_at && $this->created_at->diffInHours(now(), false) >= 24) {
                return true;
            }
        }

        // Pesanan Price Locked (Belum melunasi sisa tagihan)
        if ($this->status === 'price_locked') {
            if ($this->payment_deadline && $this->payment_deadline->isPast()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Menandai pesanan sebagai kedaluwarsa dan mengembalikan kuota kursi ekspedisi.
     */
    public function markAsExpired(?string $reason = null): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        $note = $reason ?? 'Kedaluwarsa otomatis karena melewati batas waktu pembayaran 24 jam pada '.now()->format('d/m/Y H:i');
        $updatedNotes = $this->notes ? $this->notes.' | '.$note : $note;

        $updated = (bool) $this->update([
            'status' => 'expired',
            'notes' => $updatedNotes,
        ]);

        if ($this->expedition) {
            $this->expedition->syncQuotaBooked();
        }

        return $updated;
    }

    /**
     * Scope query untuk mengambil pesanan yang belum dibayar dan sudah kedaluwarsa (> 24 jam).
     */
    public function scopeUnpaidExpired($query)
    {
        $now = now();

        return $query->where(function ($q) use ($now) {
            $q->where('status', 'open')
                ->where(function ($sub) use ($now) {
                    $sub->where(function ($d) use ($now) {
                        $d->whereNotNull('payment_deadline')
                            ->where('payment_deadline', '<=', $now);
                    })->orWhere(function ($c) use ($now) {
                        $c->whereNull('payment_deadline')
                            ->where('created_at', '<=', $now->copy()->subHours(24));
                    });
                });
        })->orWhere(function ($q) use ($now) {
            $q->where('status', 'price_locked')
                ->whereNotNull('payment_deadline')
                ->where('payment_deadline', '<=', $now);
        });
    }
}
