<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\Expedition;
use App\Models\MeetingPoint;
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
            /** @var Expedition $expedition */
            $expedition = Expedition::where('id', $data['expedition_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $paxCount = (int) $data['pax_count'];
            $tripType = $data['trip_type'] ?? $expedition->type;

            // Validasi kuota publik hanya berlaku untuk Open Trip
            if ($tripType !== 'private') {
                $availableQuota = $expedition->quota_max - $expedition->quota_booked;

                if ($availableQuota < $paxCount) {
                    throw ValidationException::withMessages([
                        'pax_count' => "Sisa kuota tidak mencukupi (Tersisa {$availableQuota} kursi).",
                    ]);
                }
            }

            // Jika private trip diminta, pastikan gunung mendukung layanan private trip
            if ($tripType === 'private') {
                if (! $expedition->mountain->has_private_trip) {
                    throw ValidationException::withMessages([
                        'trip_type' => 'Layanan Private Trip belum dibuka untuk destinasi gunung ini.',
                    ]);
                }

                if ($expedition->type !== 'private') {
                    $privateExpedition = Expedition::where('mountain_id', $expedition->mountain_id)
                        ->where('type', 'private')
                        ->first();
                    if ($privateExpedition) {
                        $expedition = $privateExpedition;
                    }
                }
            }

            $mountain = $expedition->mountain;

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

            $hikingType = $data['hiking_type'] ?? $expedition->hiking_type ?? 'camping';

            if ($tripType === 'private') {
                // Private Trip: Harga tier langsung terkunci sesuai jumlah pax, pembayaran langsung 100% tanpa DP
                $lockedPricePerPax = $mountain->getTierPriceForPax($paxCount, $hikingType);
                $tripCost = $lockedPricePerPax * $paxCount;
                $grandTotal = $tripCost + $shuttleFeeTotal + $addonsFeeTotal;
                $bookingFeePerPax = 0;
                $totalBookingFee = 0;
                $remainingPaymentTotal = $grandTotal;
            } else {
                // Open Trip: Wajib bayar booking fee (DP), harga final terkunci menjelang keberangkatan
                $bookingFeePerPax = $mountain->booking_fee_per_pax;
                $totalBookingFee = $bookingFeePerPax * $paxCount;
                $baseOpenPrice = $hikingType === 'tektok' ? $mountain->effective_price_tektok : $mountain->base_price;
                $estimatedTripCost = $baseOpenPrice * $paxCount;
                $grandTotal = $estimatedTripCost + $shuttleFeeTotal + $addonsFeeTotal;
                $lockedPricePerPax = null;
                $remainingPaymentTotal = null;
            }

            // Tentukan tanggal keberangkatan dan kepulangan
            if ($tripType === 'private' && ! empty($data['departure_date'])) {
                $departureDate = Carbon::parse($data['departure_date'])->toDateString();
                $durationNights = $mountain?->duration_nights ?? 1;
                $returnDate = Carbon::parse($departureDate)->addDays($durationNights)->toDateString();
            } else {
                $departureDate = $expedition->departure_date ? Carbon::parse($expedition->departure_date)->toDateString() : now()->toDateString();
                $returnDate = $expedition->return_date ? Carbon::parse($expedition->return_date)->toDateString() : Carbon::parse($departureDate)->addDays(1)->toDateString();
            }

            // Generate Booking Code unik: MT-YYYYMMDD-XXXXX
            $bookingCode = 'MT-'.date('Ymd').'-'.strtoupper(Str::random(5));

            /** @var Booking $booking */
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'user_id' => $userId,
                'expedition_id' => $expedition->id,
                'route_id' => $data['route_id'],
                'meeting_point_id' => $data['meeting_point_id'] ?? null,
                'trip_type' => $tripType,
                'departure_date' => $departureDate,
                'return_date' => $returnDate,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'customer_nik' => $data['customer_nik'],
                'pax_count' => $paxCount,
                'booking_fee_per_pax' => $bookingFeePerPax,
                'total_booking_fee' => $totalBookingFee,
                'locked_price_per_pax' => $lockedPricePerPax,
                'shuttle_fee_total' => $shuttleFeeTotal,
                'addons_fee_total' => $addonsFeeTotal,
                'grand_total' => $grandTotal,
                'remaining_payment_total' => $remainingPaymentTotal,
                'status' => 'open',
                'notes' => $data['notes'] ?? null,
            ]);

            // Simpan data peserta (Ketua & Anggota)
            foreach ($data['participants'] as $index => $participant) {
                $isLeader = (bool) ($participant['is_leader'] ?? ($index === 0));
                $booking->participants()->create([
                    'full_name' => $participant['full_name'],
                    'nik' => $participant['nik'],
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
}
