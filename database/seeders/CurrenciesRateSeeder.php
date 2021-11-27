<?php

namespace LauanaOH\Dracma\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesRateSeeder extends Seeder
{
    public function run()
    {
        DB::table('currencies_rates')->insert([
            [
                'from' => 'USD',
                'to' => 'BRL',
                'rate' => 5.63,
                'date' => '2021-11-26',
            ],
            [
                'from' => 'USD',
                'to' => 'COP',
                'rate' => 4007,
                'date' => '2021-11-26',
            ],
        ]);
    }
}