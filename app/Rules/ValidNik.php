<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidNik implements ValidationRule
{
    /**
     * Kode provinsi resmi Republik Indonesia menurut Kemendagri.
     */
    protected array $validProvinces = [
        '11', '12', '13', '14', '15', '16', '17', '18', '19', '21',
        '31', '32', '33', '34', '35', '36',
        '51', '52', '53',
        '61', '62', '63', '64', '65',
        '71', '72', '73', '74', '75', '76',
        '81', '82',
        '91', '92', '93', '94', '95', '96',
    ];

    public function __construct(
        protected int $minAge = 12,
        protected int $maxAge = 80,
    ) {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) && ! is_numeric($value)) {
            $fail('Nomor Induk Kependudukan (NIK) harus terdiri dari 16 digit angka sesuai KTP Anda.');

            return;
        }

        $nik = trim((string) $value);

        // 1. Cek panjang harus tepat 16 digit angka
        if (! preg_match('/^[0-9]{16}$/', $nik)) {
            $fail('Nomor Induk Kependudukan (NIK) harus terdiri dari 16 digit angka sesuai KTP Anda.');

            return;
        }

        // 2. Cek pola dummy angka seragam atau sekuensial
        if (preg_match('/^(.)\1{15}$/', $nik) || $nik === '1234567890123456') {
            $fail('Nomor KTP/NIK tidak valid. Pastikan Anda memasukkan nomor KTP asli yang terdaftar resmi.');

            return;
        }

        // 3. Cek nomor urut komputerisasi akhir (tidak boleh 0000)
        $sequence = substr($nik, 12, 4);
        if ($sequence === '0000') {
            $fail('Nomor registrasi pada NIK tidak valid. Mohon periksa kembali nomor KTP Anda.');

            return;
        }

        // 4. Validasi kode provinsi (digit 1-2)
        $provinceCode = substr($nik, 0, 2);
        if (! in_array($provinceCode, $this->validProvinces, true)) {
            $fail('Kode wilayah pada NIK tidak sesuai dengan data kependudukan resmi. Mohon periksa kembali nomor KTP Anda.');

            return;
        }

        // 5. Validasi tanggal dan bulan lahir
        $rawDay = (int) substr($nik, 6, 2);
        $month = (int) substr($nik, 8, 2);
        $year2Digit = (int) substr($nik, 10, 2);

        // Format Dukcapil: Laki-laki 01-31, Perempuan 41-71 (tanggal + 40)
        $realDay = $rawDay;
        if ($rawDay >= 41 && $rawDay <= 71) {
            $realDay = $rawDay - 40;
        } elseif ($rawDay < 1 || $rawDay > 31) {
            $fail('Tanggal lahir yang tertera pada NIK tidak valid. Mohon periksa kembali nomor KTP Anda.');

            return;
        }

        if ($month < 1 || $month > 12) {
            $fail('Bulan lahir yang tertera pada NIK tidak valid. Mohon periksa kembali nomor KTP Anda.');

            return;
        }

        // Menentukan tahun 4 digit (2026 sebagai basis)
        $currentYear = (int) date('Y');
        $current2Digit = (int) date('y');
        $fullYear = ($year2Digit <= $current2Digit)
            ? 2000 + $year2Digit
            : 1900 + $year2Digit;

        // Cek validitas kalender (misal 31 Februari tidak valid)
        if (! checkdate($month, $realDay, $fullYear)) {
            $fail('Tanggal lahir pada NIK tidak sesuai dengan kalender resmi. Mohon periksa kembali nomor KTP Anda.');

            return;
        }

        // 6. Cek batas usia pendaki
        try {
            $birthDate = Carbon::createFromDate($fullYear, $month, $realDay);
            $age = (int) round($birthDate->floatDiffInYears(Carbon::now()));

            if ($age < $this->minAge) {
                $fail("Berdasarkan NIK, usia Anda belum memenuhi ketentuan minimal pendakian (minimal {$this->minAge} tahun).");

                return;
            }

            if ($age > $this->maxAge) {
                $fail("Berdasarkan NIK, usia Anda melampaui batas ketentuan maksimal ekspedisi pendakian (maksimal {$this->maxAge} tahun).");

                return;
            }
        } catch (\Throwable $e) {
            $fail('Data tanggal lahir pada NIK tidak dapat diproses. Mohon periksa kembali nomor KTP Anda.');
        }
    }
}
