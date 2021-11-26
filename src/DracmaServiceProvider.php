<?php

namespace LauanaOH\Dracma;

use Illuminate\Support\ServiceProvider;
use LauanaOH\Dracma\Console\FetchExternalCurrenciesRateCommand;

class DracmaServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerResources();
    }

    protected function registerPublishing(): void
    {
        if (!class_exists('CreateCurrenciesRatesTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_currencies_rates_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_currencies_rates_table.php'),
            ], 'migrations');
        }
    }

    protected function registerResources(): void
    {
        $this->registerFacades();
    }

    protected function registerFacades(): void
    {
        $this->app->singleton('Dracma', function ($app) {
            return new Dracma();
        });
    }
}