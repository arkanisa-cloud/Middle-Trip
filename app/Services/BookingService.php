<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\Expedition;
use App\Models\MeetingPoint;
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
            $availableQuota = $expedition->quota_max - $expedition->quota_booked;

            if ($availableQuota < $paxCount) {
                throw ValidationException::withMessages([
                    'pax_count' => "Sisa kuota tidak mencukupi (Tersisa {$availableQuota} kursi).",
                ]);
            }

            $mountain = $expedition->mountain;
            $bookingFeePerPax = $mountain->booking_fee_per_pax;
            $totalBookingFee = $bookingFeePerPax * $paxCount;

            // Hitung biaya shuttle
            $shuttleFeeTotal = 0;
            if (! empty($data['meeting_point_id'])) {
                $meetingPoint = MeetingPoint::find($data['meeting_point_id']);
                if ($meetingPoint) {
                    $shuttleFeeTotal = $meetingPoint->additional_price_per_pax * $paxCount;
                }
            }

            // Hitung estimasi harga awal (base price x pax)
            $estimatedTripCost = $mountain->base_price * $paxCount;

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

            $grandTotal = $estimatedTripCost + $shuttleFeeTotal + $addonsFeeTotal;

            // Generate Booking Code unik: MT-YYYYMMDD-XXXXX
            $bookingCode = 'MT-'.date('Ymd').'-'.strtoupper(Str::random(5));

            /** @var Booking $booking */
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'user_id' => $userId,
                'expedition_id' => $expedition->id,
                'route_id' => $data['route_id'],
                'meeting_point_id' => $data['meeting_point_id'] ?? null,
                'trip_type' => $expedition->type,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'customer_nik' => $data['customer_nik'],
                'pax_count' => $paxCount,
                'booking_fee_per_pax' => $bookingFeePerPax,
                'total_booking_fee' => $totalBookingFee,
                'shuttle_fee_total' => $shuttleFeeTotal,
                'addons_fee_total' => $addonsFeeTotal,
                'grand_total' => $grandTotal,
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

            // Naikkan kuota terisi
            $expedition->increment('quota_booked', $paxCount);

            return $booking;
        });
    }
}
