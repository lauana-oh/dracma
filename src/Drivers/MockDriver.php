<?php

namespace LauanaOH\Dracma\Drivers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LauanaOH\Dracma\Contracts\DriverContract;
use LauanaOH\Dracma\Models\CurrenciesRate;

class MockDriver implements DriverContract
{
    const RATES = [
        'COP' => 3900,
        'BRL' => 5.67,
        'CLP' => 832.05,
        'CRC' => 639.73,
        'EUR' => 0.88,
        'CAD' => 1.28,
        'PEN' => 4.03,
    ];

    public function getName(): string
    {
        return 'mock';
    }

    public function getCurrenciesRatesFromSource(Collection $currencies, Carbon $date): Collection
    {
        $currenciesRates = collect();

        if ($currencies->isEmpty()) {
            $currencies = $currencies->merge(array_keys(self::RATES));
        }

        foreach ($currencies as $currency) {
            if ($quote = self::RATES[$currency] ?? null) {
                $currenciesRates->prepend(
                    CurrenciesRate::updateOrCreate([
                        'from' => config('dracma.source'),
                        'to' => $currency,
                        'date' => $date,
                    ], ['quote' => $quote]),
                    $currency
                );
            }
        }

        return $currenciesRates;
    }
}
