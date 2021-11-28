<?php

namespace LauanaOH\Dracma\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LauanaOH\Dracma\Contracts\CurrenciesRateRepositoryContract;
use LauanaOH\Dracma\Models\CurrenciesRate;

class CurrenciesRateRepository implements CurrenciesRateRepositoryContract
{
    protected ?string $source;

    public function __construct()
    {
        $this->source = config('dracma.source', 'USD');
    }

    public function getCurrenciesRatesFromSource(Collection $currencies, Carbon $date): Collection
    {
        $query = CurrenciesRate::query();

        foreach ($currencies as $currency) {
            $query->orWhere([
                'from' => $this->source,
                'to' => $currency,
                'date' => $date->format('Y-m-d'),
            ]);
        }

        return  $query->get()
            ->keyBy(fn (CurrenciesRate $item) => $item->to);
    }
}
