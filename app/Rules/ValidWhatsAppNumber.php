<?php

namespace App\Rules;

use App\Services\FonnteService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidWhatsAppNumber implements ValidationRule
{
    protected FonnteService $fonnte;

    public function __construct(?FonnteService $fonnte = null)
    {
        $this->fonnte = $fonnte ?? app(FonnteService::class);
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) && ! is_numeric($value)) {
            $fail('Nomor WhatsApp harus berupa nomor yang valid.');

            return;
        }

        $raw = trim((string) $value);
        // Hapus spasi dan tanda hubung
        $clean = preg_replace('/[\s\-]/', '', $raw);

        // 1. Cek format seluler Indonesia (harus diawali 08, 628, atau +628)
        if (! preg_match('/^(\+62|62|0)8[1-9][0-9]{7,11}$/', $clean)) {
            $fail('Format nomor WhatsApp belum tepat. Mohon gunakan nomor ponsel Indonesia yang aktif (contoh: 081234567890).');

            return;
        }

        $normalized = $this->fonnte->normalizeNumber($clean);

        // 2. Cek pola nomor dummy berulang atau sekuensial
        $sub = substr($normalized, 2); // Ambil angka setelah 62 (contoh: 81234567890 atau 81111111111)

        // Angka berulang seperti 8111111111, 80000000000, 8999999999
        if (preg_match('/^8(.)\1{7,}$/', $sub)) {
            $fail('Nomor WhatsApp tidak valid. Mohon masukkan nomor ponsel aktif yang Anda gunakan sehari-hari.');

            return;
        }

        // Nomor sekuensial dummy yang umum digunakan testing
        $dummySequences = [
            '6281234567890',
            '628123456789',
            '6289876543210',
            '628000000000',
            '6280000000000',
        ];

        if (in_array($normalized, $dummySequences, true)) {
            $fail('Nomor WhatsApp tidak valid. Mohon masukkan nomor ponsel aktif yang Anda gunakan sehari-hari.');

            return;
        }

        // 3. Verifikasi nomor via Fonnte API jika token aktif
        $isRegistered = $this->fonnte->isRegistered($normalized);

        if ($isRegistered === false) {
            $fail('Nomor ini tidak terdaftar di WhatsApp aktif. Pastikan nomor ponsel Anda sudah terhubung ke WhatsApp agar dapat menerima konfirmasi reservasi.');

            return;
        }
    }
}
