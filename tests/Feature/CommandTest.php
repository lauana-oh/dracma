<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanFetchCurrenciesRate()
    {
        Carbon::setTestNow('2021-11-27');

        $this->artisan('dracma:fetch')
            ->expectsOutput('It will be fetch currencies rates from USD, using mock driver with date equal to 2021-11-27.')
            ->expectsOutput('It has been fetch 7 currencies rates.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('currencies_rates', 7);
    }
}
