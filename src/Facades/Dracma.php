<?php

namespace LauanaOH\Dracma\Facades;

use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static float getCurrenciesRate(string $string, string $string1, ?Carbon $now = null)
 * @method static array getMultiplesCurrenciesRates(array[] $request)
 */
class Dracma extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Dracma';
    }
}
