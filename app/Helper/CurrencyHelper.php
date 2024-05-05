<?php

namespace App\Helper;

use App\Repositories\CurrencyRepositoryInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CurrencyHelper
{

    protected CurrencyRepositoryInterface $currencyRepository;

    public function __construct(CurrencyRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }
    /**
     * @return array
     */
    public function getCurrenciesResponse(): array
    {
        $response = Http::get('https://www.cbr.ru/scripts/XML_valFull.asp');
        if ($response->successful()) {
            $currencies = $this->getXmlCurrencies($response);
            $currenciesInfo = [];
            foreach ($currencies['Item'] as $currency) {
                $currenciesInfo[] = [
                    'charCode' => $currency['ISO_Char_Code'],
                    'name' => $currency['Name'],
                    'numCode' => $currency['ISO_Num_Code'],
                ];
            }
            return $currenciesInfo;

        } else {
            return [];

        }
    }

    public function getAll()
    {
        return $this->currencyRepository->getAll();
    }


    /**
     * @param Response $response
     * @return array
     */
    private function getXmlCurrencies(Response $response): array {
        $xmlData = $response->body();
        $xmlArray = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xmlArray);
        return json_decode($json, true);
    }

}
