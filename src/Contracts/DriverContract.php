<?php

namespace LauanaOH\Dracma\Contracts;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface DriverContract
{
    public function getCurrenciesRatesFromSource(Collection $currencies, Carbon $date): Collection;

    public function getName(): string;
}
