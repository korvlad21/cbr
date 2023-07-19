<?php

namespace App\Helper;

use App\Http\Resources\ExchangeResource;
use App\Models\Currency;
use App\Models\Exchange;
use App\Repositories\ExchangeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeHelper
{

    protected ExchangeRepositoryInterface $exchangeRepository;

    public function __construct(ExchangeRepositoryInterface $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
    }

    /**
     * @param string $date
     * @param bool $beforeTradeDay
     * @return array
     */
    public function getExchangeOnDate(string $date, bool $beforeTradeDay = false): array
    {
        try {
            $formattedDate = Carbon::parse($date)->format('d/m/Y');
            $response = Http::get("https://www.cbr.ru/scripts/XML_daily.asp?date_req={$formattedDate}");
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                $exchanges = [];

                if ($beforeTradeDay) {
                    $getResponseDate = (array)$xml->attributes()['Date'];
                    $responseDate = Carbon::parse($getResponseDate[0])->format('Y-m-d');
                }

                if (isset($responseDate) && $date !== $responseDate) {
                    return $this->getExchangeOnDate(date('Y-m-d', strtotime($responseDate . ' -1 day')));
                }
                foreach ($xml->Valute as $valute) {

                    $exchanges[(string)$valute->CharCode] = [
                        'date' => $date,
                        'charCode' => (string)$valute->CharCode,
                        'nominal' => (int)$valute->Nominal,
                        'rate' => (float)str_replace(',', '.', $valute->Value),
                    ];

                }
                return $exchanges;
            } else {
                return [];
            }
        }
        catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * @param array $exchanges
     * @param array $exchangesBeforeTradeDay
     * @return bool
     */
    public function insert(array $exchanges, array $exchangesBeforeTradeDay): bool
    {
        try {

            foreach ($exchanges as $charCode => $exchange) {
                $exchange['difference'] = $this->getDifference($exchange, $exchangesBeforeTradeDay[$charCode]);
                $this->exchangeRepository->updateOrCreate($exchange);
            }
            return true;

        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * @param array $exchanges
     * @param array $exchangesBeforeOneDay
     * @return float
     */
    private function getDifference(array $exchanges, array $exchangesBeforeOneDay): float
    {
        return $exchanges['rate'] / $exchanges['nominal'] - $exchangesBeforeOneDay['rate'] / $exchangesBeforeOneDay['nominal'];
    }

    /**
     * @param string $date
     * @param string $currency
     * @return array
     */
    public function getExchangeRates(string $date, string $currency): array
    {
        try {
            $exchangeRates = $this->exchangeRepository->getOnDate($date)->toArray();

            if (Currency::CHARCODE_RUB !== $currency) {
                $exchangeRates = $this->convertCurrencyExchange($exchangeRates, $currency);
            }

            Cache::put(
                'exchange_rates_'.date('Y-m-d').'_currency_'.$currency,
                ExchangeResource::collection($exchangeRates),
                60*60*24
            );

            return $exchangeRates;
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * @param Collection $exchangeRates
     * @param string $currency
     * @return float
     */
    private function convertCurrencyExchange(Collection $exchangeRates, string $currency): array
    {
        $convertExchangeRates = [];
        $rateCurrencyDivisionRub = $exchangeRates->where('charCode', $currency);
        foreach ($exchangeRates as $exchangeRate) {

            $convertExchangeRates[] = ($currency === $exchangeRate->charCode)
                ? $this->getRevertCurrency($rateCurrencyDivisionRub)
                : $this->getConvertCurrency($rateCurrencyDivisionRub, $exchangeRate);
        }
        return $convertExchangeRates;
    }

    /**
     * @param Collection $rateCurrencyDivisionRub
     * @return float
     */
    private function getRevertCurrency(Collection $rateCurrencyDivisionRub): array
    {
        return [
            'charCode' => Currency::CHARCODE_RUB,
            'nominal' => 1,
            'rate' => 1 / ($rateCurrencyDivisionRub->rate / $rateCurrencyDivisionRub->nominal),
            'difference' => 1 / $rateCurrencyDivisionRub->difference,
            'currency' => [
                'numCode' => Currency::NUMCODE_RUB,
                'name' => Currency::NAME_RUB
            ]
        ];
    }

    /**
     * @param Collection $rateCurrencyDivisionRub
     * @param Exchange $exchangeRate
     * @return array
     */
    private function getConvertCurrency(Collection $rateCurrencyDivisionRub, Exchange $exchangeRate): array
    {
        return [
            'charCode' => $exchangeRate->charCode,
            'nominal' => 1,
            'rate' => ($rateCurrencyDivisionRub->rate / $rateCurrencyDivisionRub->nominal) / ($exchangeRate->rate / $exchangeRate->nominal),
            'difference' => $rateCurrencyDivisionRub->difference / $exchangeRate->difference,
            'currency' => [
                'numCode' => $exchangeRate->currency->numCode,
                'name' => $exchangeRate->currency->name
            ]
        ];
    }


}
