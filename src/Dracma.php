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

    public function getMultiplesCurrenciesRates(array $currenciesSettings): ?array
    {
        $query = CurrenciesRate::query();

        foreach ($currenciesSettings as $setting) {
            $query->orWhere([
                ['from', '=', $setting['from']],
                ['to', '=', $setting['to']],
                ['date', '=', $setting['date'] ?? now()->format('Y-m-d')],
            ]);
        }

        $currenciesRates = $query->get()
            ->mapWithKeys(function ($item) {
                $key = $item->date->format('Y-m-d').'_'.$item->from.'_'.$item->to;

                return [$key => [
                    'from' => $item->from,
                    'to' => $item->to,
                    'date' => $item->date->format('Y-m-d'),
                    'rate' => $item->rate,
                ]];
            });

        return $currenciesRates->all();
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
