<?php

namespace App\Helper;

class RoundHelper
{

    public const COUNT_ROUND = 4;

    /**
     * @param float $numberFloat
     * @return float
     */
    public function getRound(float $numberFloat): float
    {
        return round($numberFloat, self::COUNT_ROUND);
    }

}
