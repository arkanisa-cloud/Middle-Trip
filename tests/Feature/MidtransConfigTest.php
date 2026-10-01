<?php

namespace Tests\Feature;

use Tests\TestCase;

class MidtransConfigTest extends TestCase
{
    public function test_midtrans_configuration_has_expected_keys(): void
    {
        $this->assertNotNull(config('midtrans.snap_url'));
        $this->assertNotNull(config('midtrans.snap_api_url'));
        $this->assertNotNull(config('midtrans.core_api_url'));
        $this->assertFalse(config('midtrans.is_production'));
        $this->assertStringContainsString('sandbox', config('midtrans.snap_url'));
        $this->assertStringContainsString('sandbox', config('midtrans.snap_api_url'));
    }
}
