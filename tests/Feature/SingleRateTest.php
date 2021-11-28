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

class SingleRateTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanGetAnCurrenciesRateFromDB()
    {
        Config::set('dracma.driver');
        $this->seed(CurrenciesRateSeeder::class);

        self::assertEquals(5.63, Dracma::getCurrenciesRateQuote('USD', 'BRL', Carbon::parse('2021-11-26')));
    }

    public function testItCanGetAnCurrenciesRateFromDBWithSourceAsPivot()
    {
        Config::set('dracma.driver');
        $this->seed(CurrenciesRateSeeder::class);

        self::assertEquals(0.0014, Dracma::getCurrenciesRateQuote('COP', 'BRL', Carbon::parse('2021-11-26')));
    }

    public function testItCanGetAnCurrenciesRateWithTodayAsDefaultDate()
    {
        CurrenciesRate::factory()->create([
            'to' => 'BRL',
            'quote' => 5.63,
            'date' => now()->format('Y-m-d'),
        ]);

        self::assertEquals(5.63, Dracma::getCurrenciesRateQuote('USD', 'BRL'));
    }

    public function testItWillThrowAnExceptionIfRateCanNotBeFounded()
    {
        $this->expectException(InvalidRateException::class);
        $this->expectExceptionMessage('The currencies rate from USD to YYY on 2021-11-26 could not be found.');

        Dracma::getCurrenciesRateQuote('USD', 'YYY', Carbon::parse('2021-11-26'));
    }

    public function testItCanGetAnCurrenciesRateFromDriver()
    {
        self::assertNotEmpty(Dracma::getCurrenciesRateQuote('USD', 'BRL', Carbon::parse('2021-11-26')));
    }

    public function testItCanGetAnCurrenciesRateFromDriverWithTodayAsDefaultDate()
    {
        self::assertNotEmpty(Dracma::getCurrenciesRateQuote('USD', 'BRL'));
    }
}
