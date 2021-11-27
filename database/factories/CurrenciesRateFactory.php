<?php

namespace LauanaOH\Dracma\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LauanaOH\Dracma\Models\CurrenciesRate;

class CurrenciesRateFactory extends Factory
{
    protected $model = CurrenciesRate::class;

    public function definition(): array
    {
        return [
            'from' => 'USD',
            'to' => array_rand(array_flip(['COP', 'BRL', 'CRC', 'CLP', 'PEN'])),
            'rate' => random_int(1, 500) / 100,
            'date' => $this->faker->date,
        ];
    }
}
