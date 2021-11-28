<?php

namespace LauanaOH\Dracma\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use LauanaOH\Dracma\Helpers\DriverHelper;

class FetchExternalCurrenciesRateCommand extends Command
{
    protected $signature = 'dracma:fetch 
    {date? : Historical date to fetch rates. By default, it is today} 
    {--driver= : Specify a driver to fetch rates.}';

    protected $description = 'Fetch source currencies rate from driver';

    public function handle(): int
    {
        $date = $this->argument('date') ? Carbon::parse($this->argument('date')) : now();

        if ($date->greaterThan(now())) {
            $this->error('Date cannot be greater than today');

            return self::FAILURE;
        }

        $driver = DriverHelper::getDriver($this->option('driver'));

        $this->line(
            'It will be fetch currencies rates from '.config('dracma.source', 'USD')
            .', using '.$driver->getName().' driver with date equal to '.$date->format('Y-m-d').'.'
        );

        $rates = $driver->getCurrenciesRatesFromSource(collect(), $date);

        $this->info('It has been fetch '.$rates->count().' currencies rates.');

        return self::SUCCESS;
    }
}
