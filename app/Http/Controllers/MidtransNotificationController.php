<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Expedition;
use App\Models\PaymentTransaction;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{
    public function __construct(
        public readonly MidtransService $midtrans,
    ) {}

    /**
     * Menangani webhook HTTP notification resmi dari Midtrans.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');

        if (! $this->midtrans->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            return response()->json(['message' => 'Invalid signature key.'], 403);
        }

        $transaction = PaymentTransaction::where('transaction_code', $orderId)->first();

        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        $parsed = $this->midtrans->parseTransactionStatus($payload);

        DB::transaction(function () use ($transaction, $parsed, $payload): void {
            $transaction->update([
                'status' => $parsed['status'],
                'payment_method' => $payload['payment_type'] ?? $transaction->payment_method,
                'payment_payload' => $payload,
                'paid_at' => $parsed['is_success'] ? now() : $transaction->paid_at,
            ]);

            $booking = Booking::with('expedition')->where('id', $transaction->booking_id)->first();
            if ($booking) {
                if ($parsed['is_success']) {
                    if ($booking->status === 'cancelled') {
                        $booking->update([
                            'notes' => trim(($booking->notes ?? '').' | Pembayaran diterima saat status cancelled/kuota penuh (Overbooked). Menunggu proses refund dana 100%.', ' |'),
                        ]);
                        Log::warning("OVERBOOKING REFUND REQUIRED: Booking {$booking->booking_code} (ID: {$booking->id}) paid Rp {$transaction->amount} but booking was already cancelled.");
                    } elseif ($transaction->payment_stage === 'booking_fee' && $booking->status === 'open') {
                        $isPrivate = $booking->trip_type === 'private';
                        $expedition = $booking->expedition_id
                            ? Expedition::where('id', $booking->expedition_id)->lockForUpdate()->first()
                            : null;

                        $availableQuota = $expedition ? max(0, $expedition->quota_max - $expedition->quota_booked) : 0;

                        if (! $isPrivate && $expedition && $availableQuota < $booking->pax_count) {
                            // Skenario Kalah Cepat: Pembayaran berhasil saat kuota sudah habis terisi
                            $booking->update([
                                'status' => 'cancelled',
                                'notes' => 'Pembayaran DP diterima tetapi kuota batch ekspedisi telah terisi penuh oleh pendaki lain (Overbooked). Menunggu proses refund dana 100%.',
                            ]);
                            Log::warning("OVERBOOKING REFUND REQUIRED: Booking {$booking->booking_code} (ID: {$booking->id}) paid Rp {$transaction->amount} but quota for expedition {$expedition->id} was already full.");
                        } else {
                            $booking->update(['status' => 'reserved']);
                            $expedition?->syncQuotaBooked();

                            // Jika kuota batch telah penuh (quota_booked >= quota_max),
                            // otomatis batalkan transaksi pending calon peserta lain agar uang mereka tidak terpotong!
                            if (! $isPrivate && $expedition && $expedition->quota_booked >= $expedition->quota_max) {
                                $this->cancelPendingTransactionsForExpedition($expedition->id, $booking->id);
                            }
                        }
                    } elseif (in_array($transaction->payment_stage, ['settlement', 'full_payment'], true)) {
                        $booking->update(['status' => 'paid']);
                        $booking->expedition?->syncQuotaBooked();
                    }
                } elseif ($parsed['status'] === 'expired' && $booking->status === 'price_locked') {
                    $booking->markAsExpired('Kedaluwarsa pelunasan melalui notifikasi Midtrans');
                }
            }
        });

        return response()->json(['status' => 'ok']);
    }

    /**
     * Membatalkan seluruh tagihan Midtrans yang masih pending untuk pesanan lain
     * pada batch ekspedisi yang sama saat kuota telah terisi penuh.
     */
    protected function cancelPendingTransactionsForExpedition(int $expeditionId, int $winningBookingId): void
    {
        $otherBookings = Booking::where('expedition_id', $expeditionId)
            ->where('id', '!=', $winningBookingId)
            ->where('status', 'open')
            ->get();

        foreach ($otherBookings as $otherBooking) {
            $pendingTransactions = PaymentTransaction::where('booking_id', $otherBooking->id)
                ->where('status', 'pending')
                ->get();

            foreach ($pendingTransactions as $pendingTx) {
                $this->midtrans->cancelTransaction($pendingTx->transaction_code);
                $pendingTx->update(['status' => 'expired']);
            }

            $otherBooking->update([
                'status' => 'cancelled',
                'notes' => 'Dibatalkan otomatis oleh sistem karena sisa kuota pada batch ini telah terpenuhi 100% oleh pendaki lain.',
            ]);
        }
    }
}
