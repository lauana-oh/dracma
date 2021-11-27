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
}
