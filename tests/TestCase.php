<?php

namespace Tests;

use LauanaOH\Dracma\DracmaServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);

        $app['config']->set('dracma.driver', 'mock');

        include_once __DIR__.'/../database/migrations/create_currencies_rates_table.php.stub';

        (new \CreateCurrenciesRatesTable)->up();
    }

    protected function getPackageProviders($app): array
    {
        return [
            DracmaServiceProvider::class,
        ];
    }
}
