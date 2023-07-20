<?php

namespace App\Helper;

use App\Http\Resources\ExchangeResource;
use App\Models\Currency;
use App\Models\Exchange;
use App\Repositories\ExchangeRepositoryInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeHelper
{

    protected ExchangeRepositoryInterface $exchangeRepository;
    protected DifferenceHelper $differenceHelper;
    protected RoundHelper $roundHelper;

    public function __construct(ExchangeRepositoryInterface $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
        $this->differenceHelper = new DifferenceHelper();
        $this->roundHelper = new RoundHelper();
    }

    /**
     * @param string $date
     * @param bool $beforeTradeDay
     * @return array
     */
    public function getExchangeOnDate(string &$date, bool $beforeTradeDay = false): array
    {
        try {
            $formattedDate = Carbon::parse($date)->format('d/m/Y');
            $response = Http::get("https://www.cbr.ru/scripts/XML_daily.asp?date_req={$formattedDate}");
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                $exchanges = [];

                if (!$beforeTradeDay) {
                    $getResponseDate = (array)$xml->attributes()['Date'];
                    $responseDate = Carbon::parse($getResponseDate[0])->format('Y-m-d');
                }
                foreach ($xml->Valute as $valute) {

                    $exchanges[(string)$valute->CharCode] = [
                        'date' => $date,
                        'charCode' => (string)$valute->CharCode,
                        'nominal' => (int)$valute->Nominal,
                        'rate' => (float)str_replace(',', '.', $valute->Value),
                    ];

                }
                if (isset($responseDate)) {
                    $date = $this->getBeforeDate($date, $responseDate);
                }

                return $exchanges;
            } else {
                return [];
            }
        }
        catch (\Throwable $e) {
            dump($e);
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
                $exchange['difference'] = $this->differenceHelper->getDifference($exchange, $exchangesBeforeTradeDay[$charCode]);
                $this->exchangeRepository->updateOrCreate($exchange);
            }

            return true;

        } catch (\Throwable $e) {
            dump($e);
            return false;
        }
    }

    /**
     * @param string $date
     * @param string $currency
     * @return array
     */
    public function getExchangeRates(string $date, string $currency): array
    {
        try {
            $exchangeRates = Cache::get('exchange_rates_'.$date);

            if (!$exchangeRates) {
                $exchangeRates = $this->exchangeRepository->getOnDate($date);
                Cache::put(
                    'exchange_rates_'.$date,
                    $exchangeRates,
                    60*60*24
                );
            }

            if (Currency::CHARCODE_RUB !== $currency) {
                $exchangeRates = $this->convertCurrencyExchange($exchangeRates, $currency);
            }

            else {
                $exchangeRates = $exchangeRates->toArray();
            }

            return $exchangeRates;
        } catch (\Throwable $e) {
            dump($e);
            dd($e);
        }
    }

    /**
     * @param Collection $exchangeRates
     * @param string $currency
     * @return float
     */
    private function convertCurrencyExchange(Collection $exchangesRates, string $currency): array
    {
        $convertExchangeRates = [];
        $rateCurrencyDivisionRub = $exchangesRates->where('charCode', $currency)->first();

        if (!$rateCurrencyDivisionRub) {
            return $convertExchangeRates;
        }

        foreach ($exchangesRates as $exchangeRate) {
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
    private function getRevertCurrency(Exchange $rateCurrencyDivisionRub): array
    {
        return [
            'charCode' => Currency::CHARCODE_RUB,
            'nominal' => 1,
            'rate' => $this->roundHelper->getRound(1 / ($rateCurrencyDivisionRub->rate / $rateCurrencyDivisionRub->nominal)),
            'difference' => $this->differenceHelper->getDifferenceRub($rateCurrencyDivisionRub),
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
    private function getConvertCurrency(Exchange $rateCurrencyDivisionRub, Exchange $exchangeRate): array
    {

        return [
            'charCode' => $exchangeRate->charCode,
            'nominal' => 1,
            'rate' =>  $this->roundHelper->getRound(($exchangeRate->rate / $exchangeRate->nominal) / ($rateCurrencyDivisionRub->rate / $rateCurrencyDivisionRub->nominal)),
            'difference' => $this->differenceHelper->getDifferenceAnotherCurrency($exchangeRate, $rateCurrencyDivisionRub),
            'currency' => [
                'numCode' => $exchangeRate->currency->numCode,
                'name' => $exchangeRate->currency->name
            ]
        ];
    }

    /**
     * @throws \Exception
     */
    private function getBeforeDate(string $date, string $responseDate): string
    {
        if ($date === $responseDate) {
            return date('Y-m-d', strtotime($date . ' -1 days'));
        }

        $days = 1;

        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime($responseDate);

        $interval = $datetime1->diff($datetime2);

        $days += $interval->days;

        return date('Y-m-d', strtotime($date . ' -'.$days.' days'));
    }


}
