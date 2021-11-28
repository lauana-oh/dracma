<?php

namespace LauanaOH\Dracma\Contracts;

interface ManagerContract
{
    public function getCurrenciesRateQuote(string $from, string $to, $date = null): ?float;

    public function getMultiplesCurrenciesRates(array $currenciesRequests): ?array;
}
