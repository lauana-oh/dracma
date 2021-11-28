<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use LauanaOH\Dracma\Database\Seeders\CurrenciesRateSeeder;
use LauanaOH\Dracma\Exception\InvalidRateException;
use LauanaOH\Dracma\Facades\Dracma;
use LauanaOH\Dracma\Models\CurrenciesRate;
use Tests\TestCase;

class MultiRateTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanGetMultipleCurrenciesRateFromDB()
    {
        Config::set('dracma.driver');
        $this->seed(CurrenciesRateSeeder::class);

        $request = [
            [
                'from' => 'USD',
                'to' => 'BRL',
                'date' => '2021-11-26',
            ],
            [
                'from' => 'USD',
                'to' => 'COP',
                'date' => '2021-11-26',
            ],
        ];

        $currenciesRates = Dracma::getMultiplesCurrenciesRates($request);

        self::assertEquals($request[0]['from'], $currenciesRates['2021-11-26_USD_BRL']['from']);
        self::assertEquals($request[0]['to'], $currenciesRates['2021-11-26_USD_BRL']['to']);
        self::assertEquals(5.63, $currenciesRates['2021-11-26_USD_BRL']['quote']);
        self::assertEquals($request[0]['date'], $currenciesRates['2021-11-26_USD_BRL']['date']);

        self::assertEquals($request[1]['from'], $currenciesRates['2021-11-26_USD_COP']['from']);
        self::assertEquals($request[1]['to'], $currenciesRates['2021-11-26_USD_COP']['to']);
        self::assertEquals(4007, $currenciesRates['2021-11-26_USD_COP']['quote']);
        self::assertEquals($request[1]['date'], $currenciesRates['2021-11-26_USD_COP']['date']);
    }

    public function testItCanGetMultipleCurrenciesRateFromDBAndDriver()
    {
        $this->seed(CurrenciesRateSeeder::class);
        $request = [
            [
                'from' => 'USD',
                'to' => 'BRL',
                'date' => '2021-11-26',
            ],
            [
                'from' => 'USD',
                'to' => 'CAD',
                'date' => '2021-11-26',
            ],
            [
                'from' => 'CAD',
                'to' => 'CLP',
                'date' => '2021-11-26',
            ],
        ];

        $currenciesRates = Dracma::getMultiplesCurrenciesRates($request);

        self::assertEquals($request[0]['from'], $currenciesRates['2021-11-26_USD_BRL']['from']);
        self::assertEquals($request[0]['to'], $currenciesRates['2021-11-26_USD_BRL']['to']);
        self::assertEquals(5.63, $currenciesRates['2021-11-26_USD_BRL']['quote']);
        self::assertEquals($request[0]['date'], $currenciesRates['2021-11-26_USD_BRL']['date']);

        self::assertEquals($request[1]['from'], $currenciesRates['2021-11-26_USD_CAD']['from']);
        self::assertEquals($request[1]['to'], $currenciesRates['2021-11-26_USD_CAD']['to']);
        self::assertNotEmpty($currenciesRates['2021-11-26_USD_CAD']['quote']);
        self::assertEquals($request[1]['date'], $currenciesRates['2021-11-26_USD_CAD']['date']);
    }

    public function testItShowsMessageWhenRateIsNotFound()
    {
        $this->seed(CurrenciesRateSeeder::class);

        $request = [
            [
                'from' => 'USD',
                'to' => 'BRL',
                'date' => '2021-11-26',
            ],
            [
                'from' => 'YYY',
                'to' => 'COP',
                'date' => '2021-11-26',
            ],
        ];

        $currenciesRates = Dracma::getMultiplesCurrenciesRates($request);

        self::assertEquals($request[0]['from'], $currenciesRates['2021-11-26_USD_BRL']['from']);
        self::assertEquals($request[0]['to'], $currenciesRates['2021-11-26_USD_BRL']['to']);
        self::assertEquals(5.63, $currenciesRates['2021-11-26_USD_BRL']['quote']);
        self::assertEquals($request[0]['date'], $currenciesRates['2021-11-26_USD_BRL']['date']);

        self::assertEquals($request[1]['from'], $currenciesRates['2021-11-26_YYY_COP']['from']);
        self::assertEquals($request[1]['to'], $currenciesRates['2021-11-26_YYY_COP']['to']);
        self::assertEquals('Unable to get currencies rate quote.', $currenciesRates['2021-11-26_YYY_COP']['quote']);
        self::assertEquals($request[1]['date'], $currenciesRates['2021-11-26_YYY_COP']['date']);
    }
}
