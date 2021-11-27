<?php

namespace LauanaOH\Dracma;

use LauanaOH\Dracma\Contracts\DriverContract;
use LauanaOH\Dracma\Drivers\NullDriver;

class DracmaDriverManager
{
    public function getDriver(): DriverContract
    {
        $driverType = config('dracma.driver');

        $className = array_reduce(explode('_', $driverType), function ($name, $word) {
            return $name.ucfirst($word);
        }, 'LauanaOH\\Dracma\\Drivers\\').'Driver';

        return class_exists($className) ? new $className() : new NullDriver();
    }
}
