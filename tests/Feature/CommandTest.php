<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2021-11-27');
    }

    public function testItCanFetchCurrenciesRate()
    {
        $this->artisan('dracma:fetch')
            ->expectsOutput('It will be fetch currencies rates from USD, using mock driver with date equal to 2021-11-27.')
            ->expectsOutput('It has been fetch 7 currencies rates.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('currencies_rates', 7);
    }

    public function testItCanFetchCurrenciesRateWithCustomDate()
    {
        $this->artisan('dracma:fetch', [
            'date' => '2021-11-26',
        ])
            ->expectsOutput('It will be fetch currencies rates from USD, using mock driver with date equal to 2021-11-26.')
            ->expectsOutput('It has been fetch 7 currencies rates.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('currencies_rates', 7);
    }

    public function testItCanFetchCurrenciesRateWithCustomDriver()
    {
        $this->artisan('dracma:fetch', [
            '--driver' => 'null',
        ])
            ->expectsOutput('It will be fetch currencies rates from USD, using null driver with date equal to 2021-11-27.')
            ->expectsOutput('It has been fetch 0 currencies rates.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('currencies_rates', 0);
    }

    public function testItOutputAnErrorIfDateIsGreaterThanToday()
    {
        $this->artisan('dracma:fetch', [
            'date' => '2021-11-28',
        ])
            ->expectsOutput('Date cannot be greater than today')
            ->assertExitCode(1);

        $this->assertDatabaseCount('currencies_rates', 0);
    }
}
