<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;

class CurrencyHelper
{
    /**
     * @return array
     */
    public function getCurrenciesResponse(): array
    {
        $response = Http::get('https://www.cbr.ru/scripts/XML_valFull.asp');

        if ($response->successful()) {
            $xmlData = $response->body();

            // Преобразование XML в массив
            $xmlArray = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
            $json = json_encode($xmlArray);
            $currencies = json_decode($json, true);
            // Получение информации о валютах
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

}
