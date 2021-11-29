<?php

namespace LauanaOH\Dracma\Contracts;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface CurrenciesRateRepositoryContract
{
    public function getCurrenciesRatesFromSource(Collection $currencies, Carbon $date): Collection;
}
