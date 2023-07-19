<?php

namespace App\Helper;

use App\Models\Exchange;
use App\Repositories\ExchangeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ExchangeHelper
{

    protected $exchangeRepository;

    public function __construct(ExchangeRepositoryInterface $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
    }
    /**
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
            dd($e);
            return [];
        }
    }

    /**
     * @return array
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
            dd($e);
            return false;
        }
    }

    /**
     * @return float
     */
    public function getDifference(array $exchanges, array $exchangesBeforeOneDay): float
    {
        return $exchangesBeforeOneDay['nominal'] * $exchangesBeforeOneDay['rate'] - $exchanges['nominal'] * $exchanges['rate'];
    }
}
