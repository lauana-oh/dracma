<?php

namespace LauanaOH\Dracma\Facades;

use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static float getCurrenciesRateQuote(string $string, string $string1, ?Carbon $now = null)
 * @method static array getMultiplesCurrenciesRates(array[] $request)
 *
 * @see \LauanaOH\Dracma\Dracma
 */
class Dracma extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Dracma';
    }
}
