<?php

namespace LauanaOH\Dracma;

use LauanaOH\Dracma\Contracts\ManagerContract;
use LauanaOH\Dracma\Helpers\ConvertHelper;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class Dracma
{
    protected ManagerContract $manager;

    public function __construct()
    {
        $this->manager = app(ManagerContract::class);
    }

    public function getCurrenciesRateQuote(string $from, string $to, $date = null): ?float
    {
        return $this->manager->getCurrenciesRateQuote($from, $to, $date);
    }

    public function getMultiplesCurrenciesRates(array $currenciesRequests): ?array
    {
        return $this->manager->getMultiplesCurrenciesRates($currenciesRequests);
    }

    public function convert(string $from, string $to, string $value, $date = null): ?float
    {
        $quote = $this->getCurrenciesRateQuote($from, $to, $date);
        return ConvertHelper::convert($from, $to, $value, $quote);
    }

    public function convertMany(array $currenciesRequests): ?array
    {
        $rates = $this->getMultiplesCurrenciesRates($currenciesRequests);

        return collect($currenciesRequests)->map(function ($request) use ($rates) {
            if (!isset($request['date'])) {
                $request['date'] = now()->format('Y-m-d');
            }

            $request['quote'] = $rates[implode('_', [$request['date'], $request['from'], $request['to']])]['quote'];

            if (is_numeric($request['quote'])) {
                $request['result'] = ConvertHelper::convert($request['from'], $request['to'], $request['value'], $request['quote']);
            }

            return $request;
        })->all();
    }
}
