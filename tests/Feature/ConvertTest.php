<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use LauanaOH\Dracma\Facades\Dracma;
use Tests\TestCase;

class ConvertTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanConvertAnCurrencyValue()
    {
        self::assertEquals(56.70, Dracma::convert('USD', 'BRL', 10));
        self::assertEquals(8320, Dracma::convert('USD', 'CLP', 10));
        self::assertEquals(1467, Dracma::convert('BRL', 'CLP', 10));

        self::assertEquals(1.76, Dracma::convert('BRL', 'USD', 10));
        self::assertEquals(0.01, Dracma::convert('CLP', 'USD', 10));
        self::assertEquals(0.07, Dracma::convert('CLP', 'BRL', 10));
    }

    public function testItCanConvertMany()
    {
        Carbon::setTestNow('2021-11-27');

        $request = [
            [
                'from' => 'USD',
                'to' => 'BRL',
                'value' => 10,
            ],
            [
                'from' => 'USD',
                'to' => 'CLP',
                'date' => '2021-11-26',
                'value' => 1,
            ],
        ];

        $response = $request;

        $response[0]['quote'] = 5.67;
        $response[0]['result'] = 56.70;
        $response[0]['date'] = '2021-11-27';

        $response[1]['quote'] = 832.05;
        $response[1]['result'] = 832;

        self::assertEquals($response, Dracma::convertMany($request));
    }
}
