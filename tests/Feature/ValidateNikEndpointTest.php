<?php

namespace Tests\Feature;

use Tests\TestCase;

class ValidateNikEndpointTest extends TestCase
{
    public function test_rejects_invalid_length_nik(): void
    {
        $response = $this->postJson('/api/validate/nik', [
            'nik' => '12345',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
            ]);
    }

    public function test_rejects_dummy_nik(): void
    {
        $response = $this->postJson('/api/validate/nik', [
            'nik' => '1234567890123456',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
            ]);
    }

    public function test_rejects_underage_nik(): void
    {
        // Lahir tahun 2024
        $response = $this->postJson('/api/validate/nik', [
            'nik' => '3301011508240001',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
            ]);
    }

    public function test_approves_valid_nik(): void
    {
        // Jawa Tengah, Tgl 15-08-1995 (31 tahun), Laki-laki
        $response = $this->postJson('/api/validate/nik', [
            'nik' => '3301011508950001',
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => true,
                'age' => 31,
                'message' => 'NIK Valid (Laki-laki, 31 tahun)',
            ]);
    }
}
