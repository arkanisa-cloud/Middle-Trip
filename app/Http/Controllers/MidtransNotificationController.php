<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentTransaction;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            if ($parsed['is_success']) {
                $booking = Booking::where('id', $transaction->booking_id)->first();
                if ($booking) {
                    if ($transaction->payment_stage === 'booking_fee' && $booking->status === 'open') {
                        $booking->update(['status' => 'reserved']);
                    } elseif (in_array($transaction->payment_stage, ['settlement', 'full_payment'], true)) {
                        $booking->update(['status' => 'paid']);
                    }
                }
            }
        });

        return response()->json(['status' => 'ok']);
    }
}
