<?php

namespace LauanaOH\Dracma\Drivers;

use Carbon\Carbon;
use LauanaOH\Dracma\Contracts\DriverContract;
use LauanaOH\Dracma\Models\CurrenciesRate;

class NullDriver implements DriverContract
{
    public function getCurrenciesRate(string $from, string $to, Carbon $date): ?CurrenciesRate
    {
        return null;
    }
}
