<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransService
{
    /**
     * Membuat transaksi Snap dan mengembalikan token beserta redirect_url.
     *
     * @return array{token: string, redirect_url: string, order_id: string}
     */
    public function createSnapToken(Booking $booking, string $paymentStage, int $amount): array
    {
        $serverKey = config('midtrans.server_key');

        if (empty($serverKey)) {
            throw new RuntimeException('Midtrans Server Key belum dikonfigurasi pada file .env.');
        }

        $stagePrefix = match ($paymentStage) {
            'settlement' => 'SETTLE',
            'full_payment' => 'PVT',
            default => 'DP',
        };

        $orderId = sprintf('MT-%s-%d-%d', $stagePrefix, $booking->id, time());

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'phone' => $booking->customer_phone,
            ],
            'item_details' => [
                [
                    'id' => sprintf('ITEM-%s-%d', $stagePrefix, $booking->id),
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => match ($paymentStage) {
                        'settlement' => 'Pelunasan Ekspedisi MiddleTrip',
                        'full_payment' => 'Pembayaran Penuh Private Trip',
                        default => 'Booking Fee (DP) Ekspedisi MiddleTrip',
                    },
                ],
            ],
            'credit_card' => [
                'secure' => (bool) config('midtrans.is_3ds', true),
            ],
        ];

        $apiUrl = config('midtrans.snap_api_url');

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->post($apiUrl, $payload);

        if (! $response->successful()) {
            $errorMessage = $response->json('error_messages.0') ?? 'Gagal menghubungi Midtrans Snap API.';
            throw new RuntimeException($errorMessage);
        }

        $data = $response->json();

        return [
            'token' => $data['token'],
            'redirect_url' => $data['redirect_url'] ?? '',
            'order_id' => $orderId,
        ];
    }

    /**
     * Memverifikasi keabsahan signature hash SHA-512 dari Midtrans webhook.
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = (string) config('midtrans.server_key');
        $computedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        return hash_equals($computedSignature, $signatureKey);
    }

    /**
     * Menerjemahkan status Midtrans ke dalam status transaksi aplikasi.
     *
     * @param  array<string, mixed>  $payload
     * @return array{status: string, is_success: bool}
     */
    public function parseTransactionStatus(array $payload): array
    {
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? null;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                return ['status' => 'pending', 'is_success' => false];
            }

            if ($fraudStatus === 'accept') {
                return ['status' => 'success', 'is_success' => true];
            }
        }

        if ($transactionStatus === 'settlement') {
            return ['status' => 'success', 'is_success' => true];
        }

        if ($transactionStatus === 'pending') {
            return ['status' => 'pending', 'is_success' => false];
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'failure'], true)) {
            return ['status' => 'failed', 'is_success' => false];
        }

        if ($transactionStatus === 'expire') {
            return ['status' => 'expired', 'is_success' => false];
        }

        return ['status' => 'pending', 'is_success' => false];
    }
}
