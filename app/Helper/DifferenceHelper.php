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
        return $this->roundHelper->getRound($exchanges['rate'] - $exchangesBeforeOneDay['rate']);
    }

    /**
     * @param Exchange $rateCurrencyDivisionRub
     * @return float
     */
    public function getDifferenceRub(Exchange $rateCurrencyDivisionRub): float
    {
        return $this->roundHelper->getRound(1 / $rateCurrencyDivisionRub->rate  -
            1 / ($rateCurrencyDivisionRub->rate - $rateCurrencyDivisionRub->difference));
    }

    /**
     * @param Exchange $exchangeRate
     * @param Exchange $rateCurrencyDivisionRub
     * @return float
     */
    public function getDifferenceAnotherCurrency(Exchange $exchangeRate, Exchange $rateCurrencyDivisionRub): float
    {
        $rate = $exchangeRate->rate / $rateCurrencyDivisionRub->rate;
        $rateBeforeTradeDay = ($exchangeRate->rate - $exchangeRate->difference)
            / ($rateCurrencyDivisionRub->rate - $rateCurrencyDivisionRub->difference);
        return $this->roundHelper->getRound($rate - $rateBeforeTradeDay);
    }

}
