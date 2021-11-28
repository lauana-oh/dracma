<?php

namespace LauanaOH\Dracma;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use LauanaOH\Dracma\Console\FetchExternalCurrenciesRateCommand;
use LauanaOH\Dracma\Contracts\CurrenciesRateRepositoryContract;
use LauanaOH\Dracma\Contracts\DriverManagerContract;
use LauanaOH\Dracma\Repositories\CurrenciesRateRepository;

class DracmaServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ClientInterface::class => Client::class,
    ];

    public array $singletons = [
        DriverManagerContract::class => DriverManager::class,
        CurrenciesRateRepositoryContract::class => CurrenciesRateRepository::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/dracma.php', 'dracma');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();

            $this->commands([
                FetchExternalCurrenciesRateCommand::class,
            ]);
        }

        $this->registerResources();
    }

    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/dracma.php' => config_path('dracma.php'),
        ], 'config');

        if (! class_exists('CreateCurrenciesRatesTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_currencies_rates_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_currencies_rates_table.php'),
            ], 'migrations');
        }
    }

    protected function registerResources(): void
    {
        $this->registerFacades();
    }

    protected function registerFacades(): void
    {
        $this->app->singleton('Dracma', function () {
            return new Dracma();
        });
    }
}
