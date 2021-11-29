<?php

namespace LauanaOH\Dracma\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static float getCurrenciesRateQuote(string $string, string $string1, $now = null)
 * @method static array getMultiplesCurrenciesRates(array[] $request)
 * @method static string convert(string $from, string $to, int $value, $date = null)
 * @method static array[] convertMany(array[] $request)
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
