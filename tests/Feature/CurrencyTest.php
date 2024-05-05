<?php

namespace Tests\Feature;

use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Запуск конкретного сидера
        $this->seed(CurrencySeeder::class);
    }

    /**
     * A basic feature test example.
     */
    public function test_get_currencies(): void
    {
        $usdCurrency = [
            'charCode' => 'USD',
            'numCode' => '840',
            'name' => 'Доллар США'
        ];

        $response = $this->postJson('/api/currency/get');

        $response->assertStatus(200);
        $response->assertJsonFragment($usdCurrency);
    }
}
