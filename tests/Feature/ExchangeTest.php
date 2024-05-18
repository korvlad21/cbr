<?php

namespace Tests\Feature;

use App\Helper\ExchangeHelper;
use App\Jobs\GenerateExchangesJob;
use App\Models\Exchange;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;

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
        $this->assertDatabaseHas(Exchange::class, [
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

    /**
     * A basic feature test example.
     */
    public function test_download_all_days_exchange(): void
    {
        $response = $this->postJson('/api/exchange/download_all_days');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'success' => true
        ]);
        $this->assertEquals(ExchangeHelper::COUNT_DAYS_JOBS, Exchange::where('charCode', 'USD')->count());

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
        $this->assertDatabaseHas(Exchange::class, [
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
