<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_paginated_json_response(): void
    {
        $response = $this->getJson('/metadata/api');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
    }

    /** @test */
    public function it_respects_per_page_parameter(): void
    {
        $response = $this->getJson('/metadata/api?per_page=5');

        $response->assertStatus(200);
        $response->assertJsonPath('meta.per_page', 5);
    }

    /** @test */
    public function it_rejects_per_page_over_100(): void
    {
        $response = $this->getJson('/metadata/api?per_page=500');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('per_page');
    }

    /** @test */
    public function it_filters_by_tipe_dok(): void
    {
        $response = $this->getJson('/metadata/api?tipeDok=Peraturan');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_filters_by_tahun(): void
    {
        $response = $this->getJson('/metadata/api?tahun=2024');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_rejects_invalid_tipe_dok(): void
    {
        $response = $this->getJson('/metadata/api?tipeDok=Invalid');

        $response->assertStatus(422);
    }

    /** @test */
    public function it_rejects_invalid_tahun(): void
    {
        $response = $this->getJson('/metadata/api?tahun=abc');

        $response->assertStatus(422);
    }

    /** @test */
    public function it_supports_sorting(): void
    {
        $response = $this->getJson('/metadata/api?sort_by=tahun&sort_order=asc');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_rejects_invalid_sort_by(): void
    {
        $response = $this->getJson('/metadata/api?sort_by=password');

        $response->assertStatus(422);
    }
}
