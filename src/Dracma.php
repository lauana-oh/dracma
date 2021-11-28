<?php

namespace LauanaOH\Dracma;

use LauanaOH\Dracma\Contracts\ManagerContract;

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
}
