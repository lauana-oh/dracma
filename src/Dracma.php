<?php

namespace LauanaOH\Dracma;

use Carbon\Carbon;
use LauanaOH\Dracma\Contracts\DriverManagerContract;
use LauanaOH\Dracma\Exception\InvalidRateException;
use LauanaOH\Dracma\Models\CurrenciesRate;

class Dracma
{
    protected Contracts\DriverContract $driver;

    public function __construct()
    {
        $this->driver = app(DriverManagerContract::class)->getDriver();
    }

    public function getCurrenciesRate(string $from, string $to, ?Carbon $date = null): ?float
    {
        $date ??= now();
        $currenciesRate = $this->fetchCurrenciesRate($from, $to, $date);

        if (! $currenciesRate) {
            throw InvalidRateException::notFound($from, $to, $date->format('Y-m-d'));
        }

        return $currenciesRate->rate;
    }

    protected function fetchCurrenciesRate(string $from, string $to, $date): ?CurrenciesRate
    {
        $currenciesRate = CurrenciesRate::where([
            ['from', '=', $from],
            ['to', '=', $to],
            ['date', '=', $date->format('Y-m-d')],
        ])->first();

        return $currenciesRate ?? $this->driver->getCurrenciesRate($from, $to, $date);
    }
}
