<?php

namespace LauanaOH\Dracma\Helpers;

use LauanaOH\Dracma\Models\CurrenciesRate;

class CurrenciesRateHelper
{
    public static function getRateQuoteFromSameSource(CurrenciesRate $from, CurrenciesRate $to): float
    {
        if ($from->date->format('Y-m-d') !== $to->date->format('Y-m-d') || $from->from !== $to->from) {
            throw new \Exception();
        }

        return bcdiv($to->quote, $from->quote, 4);
    }

    public static function getKey(CurrenciesRate $currenciesRate): string
    {
        return $currenciesRate->date->format('Y-m-d').'_'.$currenciesRate->from.'_'.$currenciesRate->to;
    }
}
