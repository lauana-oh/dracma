<?php

namespace LauanaOH\Dracma\Facades;

use Illuminate\Support\Facades\Facade;
use LauanaOH\Dracma\Contracts\DriverContract;

/**
 * @method static DriverContract getDriver()
 */
class DriverManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'DracmaDriverManager';
    }
}