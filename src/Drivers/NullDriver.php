<?php

namespace LauanaOH\Dracma\Drivers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LauanaOH\Dracma\Contracts\DriverContract;

class NullDriver implements DriverContract
{
    public function getCurrenciesRatesFromSource(Collection $currencies, Carbon $date): Collection
    {
        return collect();
    }

    public function getName(): string
    {
        return 'null';
    }
}
