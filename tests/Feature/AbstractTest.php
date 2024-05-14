<?php

namespace Tests\Feature;

use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class AbstractTest extends TestCase
{
    use RefreshDatabase;
    protected array $usdCurrency;
    protected function setUp(): void
    {
        parent::setUp();
        // Запуск конкретного сидера
        $this->seed(CurrencySeeder::class);

        $this->usdCurrency = [
            'charCode' => 'USD',
            'numCode' => '840',
            'name' => 'Доллар США'
        ];
    }
}
