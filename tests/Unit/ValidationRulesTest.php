<?php

namespace Tests\Unit;

use App\Rules\ValidEmailAddress;
use App\Rules\ValidNik;
use App\Rules\ValidWhatsAppNumber;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidationRulesTest extends TestCase
{
    // ==========================================
    // 1. ValidEmailAddress Tests
    // ==========================================

    public function test_valid_email_passes(): void
    {
        $rule = new ValidEmailAddress;

        $validator = Validator::make(
            ['email' => 'pendaki.sejati@gmail.com'],
            ['email' => [$rule]]
        );

        $this->assertTrue($validator->passes());
    }

    public function test_disposable_email_fails(): void
    {
        $rule = new ValidEmailAddress;

        $disposables = [
            'user@tempmail.com',
            'user@10minutemail.com',
            'user@mailinator.com',
            'user@yopmail.com',
            'user@guerrillamail.com',
            'user@trashmail.com',
        ];

        foreach ($disposables as $email) {
            $validator = Validator::make(
                ['email' => $email],
                ['email' => [$rule]]
            );

            $this->assertFalse($validator->passes(), "Expected {$email} to fail disposable check.");
            $this->assertStringContainsString('disposable', $validator->errors()->first('email'));
        }
    }

    public function test_dummy_repetitive_email_fails(): void
    {
        $rule = new ValidEmailAddress;

        $dummies = [
            'aaaaaaa@gmail.com',
            '11111111@gmail.com',
            'asdfasdf@gmail.com',
            'testtesttest@gmail.com',
        ];

        foreach ($dummies as $email) {
            $validator = Validator::make(
                ['email' => $email],
                ['email' => [$rule]]
            );

            $this->assertFalse($validator->passes(), "Expected {$email} to fail dummy pattern check.");
            $this->assertStringContainsString('dummy', $validator->errors()->first('email'));
        }
    }

    public function test_coba_and_fake_domain_emails_fail(): void
    {
        $rule = new ValidEmailAddress;

        $invalids = [
            'coba@mail.com',
            'coba@coba.com',
            'coba123@gmail.com',
            'test@test.com',
            'contoh@contoh.com',
            'dummy@dummy.com',
            'percobaan@gmail.com',
            'admin@coba.com',
        ];

        foreach ($invalids as $email) {
            $validator = Validator::make(
                ['email' => $email],
                ['email' => [$rule]]
            );

            $this->assertFalse($validator->passes(), "Expected {$email} to fail dummy email check.");
        }
    }

    // ==========================================
    // 2. ValidNik Tests
    // ==========================================

    public function test_valid_male_nik_passes(): void
    {
        $rule = new ValidNik;

        // Jawa Tengah (33), Kab Cilacap (01), Kec Cilacap Selatan (01), Tgl 15, Bln 08, Thn 95 (1995 -> 31 thn), urut 0001
        $validator = Validator::make(
            ['nik' => '3301011508950001'],
            ['nik' => [$rule]]
        );

        $this->assertTrue($validator->passes());
    }

    public function test_valid_female_nik_passes(): void
    {
        $rule = new ValidNik;

        // Wanita tgl 15 + 40 = 55
        $validator = Validator::make(
            ['nik' => '3301015508950001'],
            ['nik' => [$rule]]
        );

        $this->assertTrue($validator->passes());
    }

    public function test_invalid_length_and_non_digit_nik_fails(): void
    {
        $rule = new ValidNik;

        $invalids = ['12345', '330101150895000A', '330101150895000123'];

        foreach ($invalids as $nik) {
            $validator = Validator::make(
                ['nik' => $nik],
                ['nik' => [$rule]]
            );

            $this->assertFalse($validator->passes());
        }
    }

    public function test_dummy_pattern_nik_fails(): void
    {
        $rule = new ValidNik;

        $dummies = [
            '1111111111111111',
            '1234567890123456',
            '0000000000000000',
            '3301011508950000', // nomor urut 0000
        ];

        foreach ($dummies as $nik) {
            $validator = Validator::make(
                ['nik' => $nik],
                ['nik' => [$rule]]
            );

            $this->assertFalse($validator->passes(), "Expected {$nik} to fail dummy check.");
        }
    }

    public function test_invalid_province_in_nik_fails(): void
    {
        $rule = new ValidNik;

        // 00 dan 99 bukan kode provinsi valid di Indonesia
        $validator = Validator::make(
            ['nik' => '0001011508950001'],
            ['nik' => [$rule]]
        );
        $this->assertFalse($validator->passes());

        $validator = Validator::make(
            ['nik' => '9901011508950001'],
            ['nik' => [$rule]]
        );
        $this->assertFalse($validator->passes());
    }

    public function test_invalid_birth_date_in_nik_fails(): void
    {
        $rule = new ValidNik;

        // Tanggal 32 (pria) atau 72 (wanita)
        $validator = Validator::make(
            ['nik' => '3301013208950001'],
            ['nik' => [$rule]]
        );
        $this->assertFalse($validator->passes());

        // Bulan 13
        $validator = Validator::make(
            ['nik' => '3301011513950001'],
            ['nik' => [$rule]]
        );
        $this->assertFalse($validator->passes());
    }

    public function test_underage_nik_fails(): void
    {
        $rule = new ValidNik(minAge: 12);

        // Lahir tahun 2024 (usia ~2 tahun)
        $validator = Validator::make(
            ['nik' => '3301011508240001'],
            ['nik' => [$rule]]
        );

        $this->assertFalse($validator->passes());
        $this->assertStringContainsString('12 tahun', $validator->errors()->first('nik'));
    }

    // ==========================================
    // 3. ValidWhatsAppNumber Tests
    // ==========================================

    public function test_valid_whatsapp_format_passes(): void
    {
        $fonnte = new FonnteService(''); // Tanpa token Fonnte, cek format lokal
        $rule = new ValidWhatsAppNumber($fonnte);

        $valids = ['081298765432', '+6281298765432', '6281298765432'];

        foreach ($valids as $phone) {
            $validator = Validator::make(
                ['phone' => $phone],
                ['phone' => [$rule]]
            );

            $this->assertTrue($validator->passes(), "Expected {$phone} to pass.");
        }
    }

    public function test_invalid_and_dummy_whatsapp_fails(): void
    {
        $fonnte = new FonnteService('');
        $rule = new ValidWhatsAppNumber($fonnte);

        $invalids = [
            '12345',
            '0215556667', // Nomor PSTN/telepon kabel
            '080000000000', // Dummy
            '081111111111', // Dummy
            '081234567890', // Dummy sequential
        ];

        foreach ($invalids as $phone) {
            $validator = Validator::make(
                ['phone' => $phone],
                ['phone' => [$rule]]
            );

            $this->assertFalse($validator->passes(), "Expected {$phone} to fail.");
        }
    }

    public function test_unregistered_fonnte_number_fails(): void
    {
        Http::fake([
            'https://api.fonnte.com/validate' => Http::response([
                'status' => true,
                'registered' => [],
                'not_registered' => ['6281298765432'],
            ], 200),
        ]);

        $fonnte = new FonnteService('test-token');
        $rule = new ValidWhatsAppNumber($fonnte);

        $validator = Validator::make(
            ['phone' => '081298765432'],
            ['phone' => [$rule]]
        );

        $this->assertFalse($validator->passes());
        $this->assertStringContainsString('tidak terdaftar di WhatsApp', $validator->errors()->first('phone'));
    }

    public function test_registered_fonnte_number_passes(): void
    {
        Http::fake([
            'https://api.fonnte.com/validate' => Http::response([
                'status' => true,
                'registered' => ['6281298765432'],
                'not_registered' => [],
            ], 200),
        ]);

        $fonnte = new FonnteService('test-token');
        $rule = new ValidWhatsAppNumber($fonnte);

        $validator = Validator::make(
            ['phone' => '081298765432'],
            ['phone' => [$rule]]
        );

        $this->assertTrue($validator->passes());
    }
}
