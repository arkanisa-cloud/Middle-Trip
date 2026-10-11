<?php

namespace Tests\Unit;

use App\Services\FonnteService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FonnteServiceTest extends TestCase
{
    public function test_normalizes_indonesian_phone_numbers_correctly(): void
    {
        $service = new FonnteService('dummy-token');

        $this->assertEquals('6281234567890', $service->normalizeNumber('081234567890'));
        $this->assertEquals('6281234567890', $service->normalizeNumber('+6281234567890'));
        $this->assertEquals('6281234567890', $service->normalizeNumber('6281234567890'));
        $this->assertEquals('6281234567890', $service->normalizeNumber('0812-3456-7890'));
        $this->assertEquals('6281234567890', $service->normalizeNumber('+62 (812) 3456-7890'));
    }

    public function test_detects_registered_whatsapp_number(): void
    {
        Http::fake([
            'https://api.fonnte.com/validate' => Http::response([
                'status' => true,
                'registered' => ['6281234567890'],
                'not_registered' => [],
            ], 200),
        ]);

        $service = new FonnteService('valid-token');
        $result = $service->isRegistered('081234567890');

        $this->assertTrue($result);
    }

    public function test_detects_unregistered_whatsapp_number(): void
    {
        Http::fake([
            'https://api.fonnte.com/validate' => Http::response([
                'status' => true,
                'registered' => [],
                'not_registered' => ['6281234567890'],
            ], 200),
        ]);

        $service = new FonnteService('valid-token');
        $result = $service->isRegistered('081234567890');

        $this->assertFalse($result);
    }

    public function test_returns_null_when_token_is_missing(): void
    {
        $service = new FonnteService('');
        $result = $service->isRegistered('081234567890');

        $this->assertNull($result);
    }

    public function test_handles_api_exception_gracefully(): void
    {
        Http::fake([
            'https://api.fonnte.com/validate' => Http::response('Server Error', 500),
        ]);

        $service = new FonnteService('valid-token');
        $result = $service->isRegistered('081234567890');

        $this->assertNull($result);
    }
}
