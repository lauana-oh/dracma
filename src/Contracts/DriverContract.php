<?php

namespace LauanaOH\Dracma\Contracts;

use Carbon\Carbon;
use LauanaOH\Dracma\Models\CurrenciesRate;

interface DriverContract
{
    public function getCurrenciesRate(string $from, string $to, Carbon $date): ?CurrenciesRate;
}