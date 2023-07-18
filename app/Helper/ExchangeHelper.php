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
    public function getExchangeOnDate(string $date): bool
    {
        try {


            $formattedDate = Carbon::parse($date)->format('d/m/Y');
            $response = Http::get("https://www.cbr.ru/scripts/XML_daily.asp?date_req={$formattedDate}");

            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());

                $exchanges = [];

                foreach ($xml->Valute as $valute) {

                    $exchange = [
                        'date' => $date,
                        'charCode' => (string)$valute->CharCode,
                        'nominal' => (int)$valute->Nominal,
                        'rate' => (float)str_replace(',', '.', $valute->Value),
                    ];
                    $this->exchangeRepository->updateOrCreate($exchange);

                }
                return true;
            } else {
                return false;
            }
        }
        catch (\Throwable $e) {
            dd($e);
            return false;
        }
    }
}
