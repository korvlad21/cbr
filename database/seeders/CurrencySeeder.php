<?php

namespace Database\Seeders;

use App\Helper\CurrencyHelper;
use App\Models\Currency;
use App\Repositories\CurrencyRepositoryInterface;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mockery\Exception;

class CurrencySeeder extends Seeder
{
    protected CurrencyRepositoryInterface $currencyRepository;

    public function __construct(CurrencyRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencyHelper = new CurrencyHelper();
        $cbrCurrencies = $currencyHelper->getCurrenciesResponse();
        $cbrCurrencies[] = [
            'charCode' => Currency::CHARCODE_RUB,
            'name' => Currency::NAME_RUB,
            'numCode' => Currency::NUMCODE_RUB,
        ];
        $currenciesCode = [];
        foreach ($cbrCurrencies as $cbrCurrency) {
            if (!$cbrCurrency['charCode'] || in_array($cbrCurrency['charCode'], $currenciesCode)){
                continue;
            }
            $currenciesCode[] = $cbrCurrency['charCode'];
            $this->currencyRepository->create($cbrCurrency);
        }

    }
}
