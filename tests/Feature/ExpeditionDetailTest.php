<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExpeditionDetailTest extends TestCase
{
    /**
     * Uji halaman detail ekspedisi dapat diakses via slug yang valid.
     */
    public function test_can_view_expedition_detail_page(): void
    {
        $response = $this->get('/ekspedisi/mt-merbabu');

        $response->assertStatus(200);
        $response->assertSee('Mt. Merbabu Expedition');
        $response->assertSee('3.142 mdpl');
        $response->assertSee('Via Selo');
    }

    /**
     * Uji bahwa seluruh 6 ekspedisi dapat diakses halamannya dengan sukses.
     */
    public function test_all_expedition_slugs_load_successfully(): void
    {
        $slugs = [
            'mt-merbabu',
            'mt-sindoro',
            'mt-prau',
            'mt-slamet',
            'mt-sumbing',
            'mt-lawu',
        ];

        foreach ($slugs as $slug) {
            $response = $this->get('/ekspedisi/'.$slug);
            $response->assertStatus(200);
            $response->assertSee('Atur Perjalananmu!');
            $response->assertSee('Booking Sekarang');
        }
    }

    /**
     * Uji bahwa mengakses slug yang tidak terdaftar akan mengembalikan 404.
     */
    public function test_non_existent_expedition_returns_404(): void
    {
        $response = $this->get('/ekspedisi/gunung-tidak-ada');

        $response->assertStatus(404);
    }
}
