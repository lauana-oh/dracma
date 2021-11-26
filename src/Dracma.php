<?php

namespace LauanaOH\Dracma;

use Carbon\Carbon;
use LauanaOH\Dracma\Exception\InvalidRateException;
use LauanaOH\Dracma\Models\CurrenciesRate;

class Dracma
{
    public function getCurrenciesRate(string $from, string $to, ?Carbon $date = null): ?float
    {
        $date ??= now();

        $currenciesRate =  CurrenciesRate::where('from', $from)
            ->where('to', $to)
            ->where('date', $date->format('Y-m-d'))
            ->first();

        if (!$currenciesRate) {
            throw InvalidRateException::notFound($from, $to, $date->format('Y-m-d'));
        }

        return $currenciesRate->rate ?? null;
    }
}