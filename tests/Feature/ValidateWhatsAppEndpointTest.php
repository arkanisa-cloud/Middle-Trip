<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ValidateWhatsAppEndpointTest extends TestCase
{
    public function test_rejects_invalid_format_phone(): void
    {
        $response = $this->postJson('/api/validate/whatsapp', [
            'phone' => '12345',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
            ]);
    }

    public function test_rejects_dummy_phone(): void
    {
        $response = $this->postJson('/api/validate/whatsapp', [
            'phone' => '081234567890',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
            ]);
    }

    public function test_rejects_unregistered_whatsapp_from_fonnte(): void
    {
        config(['services.fonnte.token' => 'mock-token']);

        Http::fake([
            'https://api.fonnte.com/validate' => Http::response([
                'status' => true,
                'registered' => [],
                'not_registered' => ['6281299998888'],
            ], 200),
        ]);

        $response = $this->postJson('/api/validate/whatsapp', [
            'phone' => '081299998888',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
                'registered' => false,
            ]);
    }

    public function test_approves_registered_whatsapp_from_fonnte(): void
    {
        config(['services.fonnte.token' => 'mock-token']);

        Http::fake([
            'https://api.fonnte.com/validate' => Http::response([
                'status' => true,
                'registered' => ['6281299998888'],
                'not_registered' => [],
            ], 200),
        ]);

        $response = $this->postJson('/api/validate/whatsapp', [
            'phone' => '081299998888',
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => true,
                'registered' => true,
            ]);
    }
}
