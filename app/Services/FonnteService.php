<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class FonnteService
{
    public function __construct(
        protected ?string $token = null,
        protected string $apiUrl = 'https://api.fonnte.com',
    ) {
        $this->token = $token ?? config('services.fonnte.token');
        $this->apiUrl = config('services.fonnte.url', 'https://api.fonnte.com');
    }

    /**
     * Normalisasi nomor telepon format Indonesia ke standar internasional E.164 (628...).
     */
    public function normalizeNumber(string $phone): string
    {
        // Hapus karakter non-digit kecuali tanda plus di awal
        $clean = preg_replace('/[^\d+]/', '', trim($phone));

        if (str_starts_with($clean, '+')) {
            $clean = substr($clean, 1);
        }

        if (str_starts_with($clean, '08')) {
            $clean = '62'.substr($clean, 1);
        }

        return $clean;
    }

    /**
     * Memeriksa apakah nomor telepon terdaftar di WhatsApp menggunakan Fonnte API.
     *
     * @return bool|null True jika terdaftar, False jika tidak terdaftar, Null jika gagal memverifikasi (graceful fallback).
     */
    public function isRegistered(string $phone): ?bool
    {
        if (empty($this->token)) {
            return null;
        }

        $normalized = $this->normalizeNumber($phone);

        try {
            $response = Http::timeout(4)
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->asForm()
                ->post(rtrim($this->apiUrl, '/').'/validate', [
                    'target' => $normalized,
                    'countryCode' => '62',
                ]);

            if (! $response->successful()) {
                Log::warning('Fonnte API validation failed with status '.$response->status(), [
                    'response' => $response->body(),
                ]);

                return null;
            }

            $data = $response->json();

            if (! isset($data['status'])) {
                return null;
            }

            $registered = $data['registered'] ?? [];
            $notRegistered = $data['not_registered'] ?? [];

            if (in_array($normalized, $registered, true)) {
                return true;
            }

            if (in_array($normalized, $notRegistered, true)) {
                return false;
            }

            return null;
        } catch (Throwable $e) {
            Log::warning('Fonnte API connection error: '.$e->getMessage());

            return null;
        }
    }
}
