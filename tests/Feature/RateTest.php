<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LauanaOH\Dracma\Database\Seeders\CurrenciesRateSeeder;
use LauanaOH\Dracma\Exception\InvalidRateException;
use LauanaOH\Dracma\Facades\Dracma;
use LauanaOH\Dracma\Models\CurrenciesRate;
use Tests\TestCase;

class RateTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanGetAnCurrenciesRateFromDB()
    {
        $this->seed(CurrenciesRateSeeder::class);
        self::assertEquals(5.63, Dracma::getCurrenciesRate('USD', 'BRL', Carbon::parse('2021-11-26')));
    }

    public function testItCanGetAnCurrenciesRateWithTodayAsDefaultDate()
    {
        CurrenciesRate::factory()->create([
            'to' => 'BRL',
            'rate' => 5.63,
            'date' => now()->format('Y-m-d'),
        ]);

        self::assertEquals(5.63, Dracma::getCurrenciesRate('USD', 'BRL'));
    }

    public function testItWillThrowAnExceptionIfRateCanNotBeFounded()
    {
        $this->expectException(InvalidRateException::class);
        $this->expectExceptionMessage('The currencies rate from USD to YYY on 2021-11-26 could not be found.');

        Dracma::getCurrenciesRate('USD', 'YYY', Carbon::parse('2021-11-26'));
    }

    public function testItCanGetAnCurrenciesRateFromDriver()
    {
        self::assertNotEmpty(Dracma::getCurrenciesRate('USD', 'BRL', Carbon::parse('2021-11-26')));
    }

    public function testItCanGetAnCurrenciesRateFromDriverWithTodayAsDefaultDate()
    {
        self::assertNotEmpty(Dracma::getCurrenciesRate('USD', 'BRL'));
    }

    public function testItCanGetMultipleCurrenciesRateFromDB()
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
                'to' => 'COP',
                'date' => '2021-11-26',
            ],
        ];

        $currenciesRates = Dracma::getMultiplesCurrenciesRates($request);

        self::assertEquals($request[0]['from'], $currenciesRates['2021-11-26_USD_BRL']['from']);
        self::assertEquals($request[0]['to'], $currenciesRates['2021-11-26_USD_BRL']['to']);
        self::assertEquals(5.63, $currenciesRates['2021-11-26_USD_BRL']['rate']);
        self::assertEquals($request[0]['date'], $currenciesRates['2021-11-26_USD_BRL']['date']);

        self::assertEquals($request[1]['from'], $currenciesRates['2021-11-26_USD_COP']['from']);
        self::assertEquals($request[1]['to'], $currenciesRates['2021-11-26_USD_COP']['to']);
        self::assertEquals(4007, $currenciesRates['2021-11-26_USD_COP']['rate']);
        self::assertEquals($request[1]['date'], $currenciesRates['2021-11-26_USD_COP']['date']);
    }
}
