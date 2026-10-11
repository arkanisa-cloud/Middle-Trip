<?php

namespace App\Http\Controllers;

use App\Rules\ValidNik;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{
    public function __construct(
        protected FonnteService $fonnte,
    ) {}

    /**
     * Memvalidasi keaktifan dan format nomor WhatsApp secara realtime saat user mengetik.
     */
    public function validateWhatsApp(Request $request): JsonResponse
    {
        $raw = trim((string) $request->input('phone', ''));
        $clean = preg_replace('/[\s\-]/', '', $raw);

        // 1. Cek format seluler Indonesia (08, 628, +628)
        if (! preg_match('/^(\+62|62|0)8[1-9][0-9]{7,11}$/', $clean)) {
            return response()->json([
                'valid' => false,
                'message' => 'Format nomor WhatsApp belum tepat. Mohon gunakan nomor ponsel Indonesia yang aktif).',
                'is_dummy' => false,
            ], 422);
        }

        $normalized = $this->fonnte->normalizeNumber($clean);
        $sub = substr($normalized, 2);

        // 2. Cek pola dummy berulang
        if (preg_match('/^8(.)\1{7,}$/', $sub)) {
            return response()->json([
                'valid' => false,
                'message' => 'Nomor WhatsApp tidak valid. Mohon masukkan nomor ponsel aktif Anda.',
                'is_dummy' => true,
            ], 422);
        }

        // Cek nomor dummy sekuensial
        $dummySequences = [
            '6281234567890',
            '628123456789',
            '6289876543210',
            '628000000000',
            '6280000000000',
        ];

        if (in_array($normalized, $dummySequences, true)) {
            return response()->json([
                'valid' => false,
                'message' => 'Nomor WhatsApp tidak valid. Mohon masukkan nomor ponsel aktif Anda.',
                'is_dummy' => true,
            ], 422);
        }

        // 3. Verifikasi keaktifan nomor melalui Fonnte API jika token tersedia
        $isRegistered = $this->fonnte->isRegistered($normalized);

        if ($isRegistered === false) {
            return response()->json([
                'valid' => false,
                'registered' => false,
                'message' => 'Nomor ini belum terdaftar di aplikasi WhatsApp. Pastikan nomor sudah aktif di WhatsApp.',
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'registered' => $isRegistered === true,
            'normalized' => $normalized,
            'message' => $isRegistered === true
                ? 'Nomor WhatsApp aktif dan terverifikasi.'
                : 'Format nomor telepon valid.',
        ]);
    }

    /**
     * Memvalidasi struktur NIK secara realtime saat user mengetik.
     */
    public function validateNik(Request $request): JsonResponse
    {
        $nik = trim((string) $request->input('nik', ''));

        $validator = Validator::make(
            ['nik' => $nik],
            ['nik' => [new ValidNik]]
        );

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => $validator->errors()->first('nik'),
            ], 422);
        }

        $rawDay = (int) substr($nik, 6, 2);
        $month = (int) substr($nik, 8, 2);
        $year2Digit = (int) substr($nik, 10, 2);
        $gender = ($rawDay >= 41 && $rawDay <= 71) ? 'Perempuan' : 'Laki-laki';
        $realDay = ($rawDay >= 41 && $rawDay <= 71) ? $rawDay - 40 : $rawDay;

        $currentYear = (int) date('Y');
        $current2Digit = (int) date('y');
        $fullYear = ($year2Digit <= $current2Digit) ? 2000 + $year2Digit : 1900 + $year2Digit;
        $birthDate = Carbon::createFromDate($fullYear, $month, $realDay);
        $age = (int) round($birthDate->floatDiffInYears(Carbon::now()));

        return response()->json([
            'valid' => true,
            'message' => "NIK Valid ({$gender}, {$age} tahun)",
            'gender' => $gender,
            'age' => $age,
        ]);
    }
}
