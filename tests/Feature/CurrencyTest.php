<?php

namespace Tests\Feature;

use App\Models\Currency;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class CurrencyTest extends AbstractTest
{
    public function test_get_currencies_db(): void
    {
        $this->assertDatabaseHas(Currency::class, $this->usdCurrency);
    }

    /**
     * A basic feature test example.
     */
    public function test_get_currencies(): void
    {
        $response = $this->postJson('/api/currency/get');

        $response->assertStatus(200);
        $response->assertJsonFragment($this->usdCurrency);
    }


}
