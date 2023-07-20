<?php

namespace App\Helper;

use App\Models\Exchange;
use Illuminate\Support\Facades\Http;

class DifferenceHelper
{


    protected RoundHelper $roundHelper;

    public function __construct()
    {
        $this->roundHelper = new RoundHelper();
    }
    /**
     * @param array $exchanges
     * @param array $exchangesBeforeOneDay
     * @return float
     */
    public function getDifference(array $exchanges, array $exchangesBeforeOneDay): float
    {
        return $this->roundHelper->getRound($exchanges['rate'] / $exchanges['nominal']
            - $exchangesBeforeOneDay['rate'] / $exchangesBeforeOneDay['nominal']);
    }

    /**
     * @param array $exchanges
     * @param array $exchangesBeforeOneDay
     * @return float
     */
    public function getDifferenceRub(Exchange $rateCurrencyDivisionRub): float
    {
        return $this->roundHelper->getRound(1 / ($rateCurrencyDivisionRub->rate / $rateCurrencyDivisionRub->nominal) -
            1 / ($rateCurrencyDivisionRub->rate / $rateCurrencyDivisionRub->nominal
                - $rateCurrencyDivisionRub->difference));
    }

    /**
     * @param Exchange $exchangeRate
     * @param Exchange $rateCurrencyDivisionRub
     * @return float
     */
    public function getDifferenceAnotherCurrency(Exchange $exchangeRate, Exchange $rateCurrencyDivisionRub): float
    {
        $rate = ($exchangeRate->rate / $exchangeRate->nominal) / ($rateCurrencyDivisionRub->rate / $rateCurrencyDivisionRub->nominal);
        $rateBeforeTradeDay = ($exchangeRate->rate / $exchangeRate->nominal - $exchangeRate->difference)
            / ($rateCurrencyDivisionRub->rate / $rateCurrencyDivisionRub->nominal - $rateCurrencyDivisionRub->difference);
        return $this->roundHelper->getRound($rate - $rateBeforeTradeDay);
    }

}
