<?php

namespace LauanaOH\Dracma\Facades;

use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static getCurrenciesRate(string $string, string $string1, ?Carbon $now = null)
 */
class Dracma extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Dracma';
    }
}