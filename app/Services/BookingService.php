<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\Expedition;
use App\Models\MeetingPoint;
use App\Models\PaymentTransaction;
use App\Models\Route;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Membuat reservasi booking baru dengan proteksi kuota (pessimistic lock).
     *
     * @param  array<string, mixed>  $data
     */
    public function createBooking(array $data, ?int $userId = null): Booking
    {
        return DB::transaction(function () use ($data, $userId): Booking {
            $paxCount = (int) $data['pax_count'];
            $route = Route::with('mountain')->findOrFail($data['route_id']);
            $mountain = $route->mountain;

            $expedition = null;
            if (! empty($data['expedition_id'])) {
                $expedition = Expedition::where('id', $data['expedition_id'])
                    ->lockForUpdate()
                    ->first();
            }

            $tripType = $data['trip_type'] ?? ($expedition ? $expedition->type : 'open');

            // Validasi kuota publik hanya berlaku untuk Open Trip
            if ($tripType !== 'private') {
                if (! $expedition) {
                    throw ValidationException::withMessages([
                        'expedition_id' => 'Jadwal batch ekspedisi wajib dipilih untuk Open Trip.',
                    ]);
                }

                if ($expedition->status === 'price_locked') {
                    throw ValidationException::withMessages([
                        'expedition_id' => 'Pendaftaran untuk batch ekspedisi ini sudah ditutup karena harga telah dikunci (Price Locked).',
                    ]);
                }

                if ($expedition->status !== 'open') {
                    throw ValidationException::withMessages([
                        'expedition_id' => 'Pendaftaran untuk batch ekspedisi ini sudah tidak aktif atau ditutup.',
                    ]);
                }

                $requestedHikingType = $data['hiking_type'] ?? null;
                if ($requestedHikingType && $expedition->hiking_type && $expedition->hiking_type !== $requestedHikingType) {
                    $batchTypeLabel = $expedition->hiking_type === 'camping' ? 'Camping' : 'Tek-tok';
                    $requestedTypeLabel = $requestedHikingType === 'camping' ? 'Camping' : 'Tek-tok';
                    throw ValidationException::withMessages([
                        'hiking_type' => "Batch jadwal ekspedisi yang dipilih bertipe {$batchTypeLabel}, tidak dapat mendaftar untuk tipe {$requestedTypeLabel}.",
                    ]);
                }

                $availableQuota = $expedition->quota_max - $expedition->quota_booked;

                if ($availableQuota < $paxCount) {
                    throw ValidationException::withMessages([
                        'pax_count' => "Sisa kuota tidak mencukupi (Tersisa {$availableQuota} kursi).",
                    ]);
                }
            }

            // Jika private trip diminta, pastikan gunung mendukung layanan private trip
            if ($tripType === 'private') {
                if (! $mountain->has_private_trip) {
                    throw ValidationException::withMessages([
                        'trip_type' => 'Layanan Private Trip belum dibuka untuk destinasi gunung ini.',
                    ]);
                }

                if ($expedition && $expedition->type !== 'private') {
                    $privateExpedition = Expedition::where('mountain_id', $mountain->id)
                        ->where('type', 'private')
                        ->first();
                    if ($privateExpedition) {
                        $expedition = $privateExpedition;
                    } else {
                        $expedition = null;
                    }
                }
            }

            // Hitung biaya shuttle
            $shuttleFeeTotal = 0;
            if (! empty($data['meeting_point_id'])) {
                $meetingPoint = MeetingPoint::find($data['meeting_point_id']);
                if ($meetingPoint) {
                    $shuttleFeeTotal = $meetingPoint->additional_price_per_pax * $paxCount;
                }
            }

            // Hitung addons
            $addonsFeeTotal = 0;
            $addonsToSync = [];
            if (! empty($data['addons'])) {
                foreach ($data['addons'] as $addonInput) {
                    $addon = Addon::find($addonInput['id']);
                    if ($addon) {
                        $qty = (int) ($addonInput['quantity'] ?? 1);
                        $subtotal = $addon->price * $qty;
                        $addonsFeeTotal += $subtotal;
                        $addonsToSync[$addon->id] = [
                            'price' => $addon->price,
                            'quantity' => $qty,
                        ];
                    }
                }
            }

            $hikingType = $tripType === 'open'
                ? ($expedition?->hiking_type ?? $data['hiking_type'] ?? 'camping')
                : ($data['hiking_type'] ?? 'camping');

            if ($tripType === 'private') {
                // Private Trip: Gunakan harga via jika diatur, atau fallback ke tier harga gunung
                if ($hikingType === 'tektok') {
                    $lockedPricePerPax = $route->price_tektok_private
                        ?? ($route->price_camping_private ? (int) round($route->price_camping_private * 0.85) : null)
                        ?? ($mountain->price_private_tektok ?? $mountain->effective_price_private_tektok);
                } else {
                    $lockedPricePerPax = $route->price_camping_private
                        ?? ($route->price_camping_open ? (int) round($route->price_camping_open * 1.5) : null)
                        ?? ($mountain->price_private ?? $mountain->getTierPriceForPax($paxCount, $hikingType));
                }

                $tripCost = $lockedPricePerPax * $paxCount;
                $grandTotal = $tripCost + $shuttleFeeTotal + $addonsFeeTotal;
                $bookingFeePerPax = 0;
                $totalBookingFee = 0;
                $remainingPaymentTotal = $grandTotal;
            } else {
                // Open Trip: Wajib bayar booking fee (DP), harga dasar dihitung dari via yang dipilih
                $bookingFeePerPax = $route->effective_booking_fee_per_pax;
                $totalBookingFee = $bookingFeePerPax * $paxCount;

                if ($hikingType === 'tektok') {
                    $baseOpenPrice = $route->price_tektok_open
                        ?? ($route->price_camping_open ? (int) round($route->price_camping_open * 0.8) : null)
                        ?? $mountain->effective_price_tektok;
                } else {
                    $baseOpenPrice = $route->price_camping_open ?? $mountain->base_price;
                }

                $estimatedTripCost = $baseOpenPrice * $paxCount;
                $grandTotal = $estimatedTripCost + $shuttleFeeTotal + $addonsFeeTotal;
                $lockedPricePerPax = null;
                $remainingPaymentTotal = null;
            }

            // Tentukan tanggal keberangkatan dan kepulangan
            if ($tripType === 'private') {
                $departureDate = ! empty($data['departure_date'])
                    ? Carbon::parse($data['departure_date'])->toDateString()
                    : ($expedition?->departure_date ? Carbon::parse($expedition->departure_date)->toDateString() : now()->addDays(7)->toDateString());
                $durationNights = $mountain?->duration_nights ?? 1;
                $returnDate = Carbon::parse($departureDate)->addDays($durationNights)->toDateString();
            } else {
                $departureDate = $expedition?->departure_date ? Carbon::parse($expedition->departure_date)->toDateString() : now()->toDateString();
                $returnDate = $expedition?->return_date ? Carbon::parse($expedition->return_date)->toDateString() : Carbon::parse($departureDate)->addDays(1)->toDateString();
            }

            // Generate Booking Code unik: MT-YYYYMMDD-XXXXX
            $bookingCode = 'MT-'.date('Ymd').'-'.strtoupper(Str::random(5));

            // Fallback ke profil user jika data pemesan masih kosong
            $user = $userId ? User::find($userId) : null;
            $customerName = ! empty($data['customer_name']) ? $data['customer_name'] : ($user?->name ?? 'Pendaki MiddleTrip');
            $customerEmail = ! empty($data['customer_email']) ? $data['customer_email'] : ($user?->email ?? '');
            $customerPhone = ! empty($data['customer_phone']) ? $data['customer_phone'] : ($user?->phone ?? null);
            $customerNik = ! empty($data['customer_nik']) ? $data['customer_nik'] : ($user?->nik ?? null);

            /** @var Booking $booking */
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'user_id' => $userId,
                'expedition_id' => $expedition?->id,
                'route_id' => $data['route_id'],
                'meeting_point_id' => $data['meeting_point_id'] ?? null,
                'trip_type' => $tripType,
                'departure_date' => $departureDate,
                'return_date' => $returnDate,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'customer_nik' => $customerNik,
                'pax_count' => $paxCount,
                'booking_fee_per_pax' => $bookingFeePerPax,
                'total_booking_fee' => $totalBookingFee,
                'locked_price_per_pax' => $lockedPricePerPax,
                'shuttle_fee_total' => $shuttleFeeTotal,
                'addons_fee_total' => $addonsFeeTotal,
                'grand_total' => $grandTotal,
                'remaining_payment_total' => $remainingPaymentTotal,
                'status' => 'open',
                'payment_deadline' => now()->addHours(24),
                'notes' => $data['notes'] ?? null,
            ]);

            // Simpan data peserta (Ketua & Anggota)
            foreach ($data['participants'] as $index => $participant) {
                $isLeader = (bool) ($participant['is_leader'] ?? ($index === 0));
                $pName = ! empty($participant['full_name']) ? $participant['full_name'] : ($isLeader ? $customerName : null);
                $pNik = ! empty($participant['nik']) ? $participant['nik'] : ($isLeader ? $customerNik : null);

                $booking->participants()->create([
                    'full_name' => $pName,
                    'nik' => $pNik,
                    'is_leader' => $isLeader,
                ]);
            }

            // Sync Addons
            if (! empty($addonsToSync)) {
                $booking->addons()->sync($addonsToSync);
            }

            return $booking;
        });
    }

    /**
     * Mengeksekusi penguncian harga (Price Lock) pada ekspedisi:
     * 1. Kunci tarif final pada ekspedisi.
     * 2. Batalkan semua pesanan yang belum membayar DP (status 'open') dan batalkan transaksi pendingnya.
     * 3. Kunci harga & terbitkan tagihan pelunasan untuk pesanan yang sudah bayar DP (status 'reserved').
     * 4. Sinkronisasi kuota terisi.
     */
    public function executePriceLock(Expedition $expedition): void
    {
        DB::transaction(function () use ($expedition): void {
            $lockedExpedition = Expedition::with(['mountain.priceTiers', 'bookings'])->lockForUpdate()->find($expedition->id);
            if (! $lockedExpedition || ! $lockedExpedition->mountain) {
                return;
            }

            $paxCount = max(1, (int) $lockedExpedition->quota_booked);
            $lockedPrice = $lockedExpedition->calculatePriceLockPrice();

            $lockedExpedition->update([
                'current_locked_price' => $lockedPrice,
                'status' => 'price_locked',
            ]);

            // 1. Batalkan semua pesanan yang belum bayar DP (status 'open')
            $unpaidBookings = $lockedExpedition->bookings()->where('status', 'open')->get();
            $midtrans = app(MidtransService::class);

            foreach ($unpaidBookings as $unpaidBooking) {
                $pendingTransactions = PaymentTransaction::where('booking_id', $unpaidBooking->id)
                    ->where('status', 'pending')
                    ->get();

                foreach ($pendingTransactions as $pendingTx) {
                    try {
                        $midtrans->cancelTransaction($pendingTx->transaction_code);
                    } catch (\Throwable $e) {
                        // ignore error in sandbox/mock
                    }
                    $pendingTx->update(['status' => 'expired']);
                }

                $unpaidBooking->update([
                    'status' => 'cancelled',
                    'notes' => trim(($unpaidBooking->notes ?? '').' | Dibatalkan otomatis karena pendaftaran batch ekspedisi telah ditutup & harga dikunci (Price Locked).', ' |'),
                ]);
            }

            // 2. Kunci harga untuk pesanan yang sudah membayar DP (status 'reserved')
            $paymentDeadline = Carbon::now()->addHours(48);
            $reservedBookings = $lockedExpedition->bookings()->where('status', 'reserved')->get();

            foreach ($reservedBookings as $booking) {
                $ticketTotal = $booking->pax_count * $lockedPrice;
                $shuttleTotal = (int) ($booking->shuttle_fee_total ?? 0);
                $addonsTotal = (int) ($booking->addons_fee_total ?? 0);
                $grandTotal = $ticketTotal + $shuttleTotal + $addonsTotal;
                $remaining = max(0, $grandTotal - (int) $booking->total_booking_fee);

                $booking->update([
                    'status' => 'price_locked',
                    'locked_price_per_pax' => $lockedPrice,
                    'grand_total' => $grandTotal,
                    'remaining_payment_total' => $remaining,
                    'payment_deadline' => $paymentDeadline,
                ]);
            }

            $lockedExpedition->syncQuotaBooked();
        });
    }
}
