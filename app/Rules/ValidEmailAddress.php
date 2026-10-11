<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmailAddress implements ValidationRule
{
    /**
     * Daftar domain disposable / temporary email yang diblokir.
     */
    protected array $disposableDomains = [
        'tempmail.com',
        '10minutemail.com',
        'mailinator.com',
        'yopmail.com',
        'guerrillamail.com',
        'trashmail.com',
        'temp-mail.org',
        'temp-mail.io',
        'fakemailgenerator.com',
        'throwawaymail.com',
        'sharklasers.com',
        'dispostable.com',
        'getairmail.com',
        'maildrop.cc',
        'mohmal.com',
        'burnermail.io',
        'crazymailing.com',
        'nada.ltd',
        'getnada.com',
        'dropmail.me',
    ];

    /**
     * Daftar domain dummy / testing / spam yang dilarang.
     */
    protected array $dummyDomains = [
        'coba.com',
        'contoh.com',
        'test.com',
        'testing.com',
        'dummy.com',
        'sample.com',
        'fake.com',
        'example.com',
        'example.org',
        'example.net',
        'mail.com',
        'email.com',
        'inbox.com',
        'testmail.com',
        'temp.com',
        'random.com',
    ];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('Format alamat email belum tepat. Pastikan penulisan email sudah benar (contoh: nama@domain.com).');

            return;
        }

        $parts = explode('@', strtolower(trim($value)), 2);
        if (count($parts) !== 2) {
            $fail('Format alamat email belum tepat. Pastikan penulisan email sudah benar (contoh: nama@domain.com).');

            return;
        }

        [$localPart, $domain] = $parts;

        // 1. Cek domain disposable / temporary email
        if (in_array($domain, $this->disposableDomains, true)) {
            $fail('Demi keamanan akun Anda, kami tidak dapat menerima alamat email sementara (disposable). Mohon gunakan email pribadi atau resmi Anda yang aktif.');

            return;
        }

        // 2. Cek domain dummy / testing
        if (in_array($domain, $this->dummyDomains, true)) {
            $fail('Domain email ini tidak dapat digunakan. Mohon gunakan alamat email resmi Anda yang masih aktif.');

            return;
        }

        // 3. Cek pola dummy berulang pada username (local part)
        // Karakter tunggal berulang (contoh: aaaaa, 111111)
        if (preg_match('/^(.)\1{4,}$/', $localPart)) {
            $fail('Alamat email tidak dapat digunakan karena terindikasi sebagai data dummy. Mohon gunakan alamat email asli Anda.');

            return;
        }

        // Kata kunci testing/dummy di awal atau seluruh username (contoh: coba, test, testing, contoh, dummy, percobaan)
        if (preg_match('/^(coba|test|testing|contoh|dummy|percobaan|sample|demo|asdf|qwerty|tester|fake|random|admin|user)[0-9_\-\.]*$/i', $localPart)) {
            $fail('Alamat email uji coba tidak dapat digunakan. Mohon gunakan alamat email aktif Anda yang sesungguhnya.');

            return;
        }

        // Pola string dummy keyboard smash & generic test
        if (preg_match('/(asdfasdf|qwertyqwerty|testtesttest|dummyaccount|randomuser)/i', $localPart)) {
            $fail('Alamat email tidak dapat digunakan karena terindikasi sebagai data dummy. Mohon gunakan alamat email asli Anda.');

            return;
        }

        // 4. Cek DNS MX Record jika bukan saat running unit test offline
        if (! app()->runningUnitTests()) {
            if (! checkdnsrr($domain, 'MX')) {
                $fail('Domain penyedia email tidak ditemukan atau tidak aktif menerima pesan. Mohon periksa kembali alamat email Anda.');

                return;
            }
        }
    }
}
