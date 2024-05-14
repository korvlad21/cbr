<?php

namespace Tests\Feature;

use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ExchangeTest extends AbstractTest
{

    /**
     * A basic feature test example.
     */
    public function test_download_exchange(): void
    {
        $date = date('Y-m-d');
        $response = $this->postJson('/api/exchange/download', [
            'date' => $date
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'success' => true
        ]);
        $this->assertDatabaseHas('exchanges', [
            'date' => $date,
            'charCode' => 'USD',
        ]);

    }

    public function test_download_exchange_wrong_type(): void
    {
        $response = $this->postJson('/api/exchange/download', [
            'date' => date('Y-m-d').'1'
        ]);;
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'Тип поля должен быть датой'
        ]);
    }

    public function test_download_exchange_without_date(): void
    {
        $response = $this->postJson('/api/exchange/download');
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'Необходимо обязательно указать дату'
        ]);
    }

    public function test_get_rates(): void
    {
        $date = date('Y-m-d');
        $responseDownload = $this->postJson('/api/exchange/download', [
            'date' => $date
        ]);
        $responseDownload->assertStatus(200);
        $responseDownload->assertJsonFragment([
            'success' => true
        ]);
        $this->assertDatabaseHas('exchanges', [
            'date' => $date,
            'charCode' => 'USD',
        ]);
        $response = $this->postJson('/api/exchange/get_rates', [
            'date' => $date,
            'currency' => 'RUB'
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment($this->usdCurrency);


    }
}
