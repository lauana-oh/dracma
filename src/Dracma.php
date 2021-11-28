<?php

namespace LauanaOH\Dracma;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LauanaOH\Dracma\Contracts\CurrenciesRateRepositoryContract;
use LauanaOH\Dracma\Contracts\DriverManagerContract;
use LauanaOH\Dracma\Exception\InvalidRateException;
use LauanaOH\Dracma\Helpers\CurrenciesRateHelper;
use LauanaOH\Dracma\Models\CurrenciesRate;

class Dracma
{
    protected Contracts\DriverContract $driver;
    protected CurrenciesRateRepositoryContract $repository;
    protected ?string $source;

    public function __construct()
    {
        $this->driver = app(DriverManagerContract::class)->getDriver();
        $this->repository = app(CurrenciesRateRepositoryContract::class);
        $this->source = config('dracma.source', 'USD');
    }

    public function getCurrenciesRateQuote(string $from, string $to, $date = null): ?float
    {
        $currencies = collect([$from, $to]);
        $date = $this->getNormalizeDate($date);

        $sourceRates = $this->getSourceRates($currencies, $date);

        if ($currencies->diff($sourceRates->keys())->isNotEmpty()) {
            throw InvalidRateException::notFound($from, $to, $date->format('Y-m-d'));
        }

        return CurrenciesRateHelper::getRateQuoteFromSameSource($sourceRates->get($from), $sourceRates->get($to));
    }

    public function getMultiplesCurrenciesRates(array $currenciesRequests): ?array
    {
        $currenciesRequests = $this->normalizeRequest($currenciesRequests);

        foreach($this->requestsGroupedByDate($currenciesRequests) as $date => $requests) {
            $currencies = $requests->reduce(function (Collection $currencies, CurrenciesRate $request) {
                return $currencies->push($request->from, $request->to);
            }, collect())->unique();

            $sourceRates = $this->getSourceRates($currencies, $requests->first()->date);

            foreach ($requests as $request) {
                $from = $sourceRates->get($request->from);
                $to = $sourceRates->get($request->to);

                if (!$from || !$to) {
                    $request->quote = 'Unable to fetch currencies rate quote.';
                    continue;
                }

                $request->quote = CurrenciesRateHelper::getRateQuoteFromSameSource($from, $to);
            }
        }

        return $currenciesRequests->map(function ($item) {
                return [
                    'from' => $item->from,
                    'to' => $item->to,
                    'date' => $item->date->format('Y-m-d'),
                    'quote' => $item->quote,
                ];
            })->all();
    }

    protected function normalizeRequest(array $currenciesRequests): Collection
    {
        return collect($currenciesRequests)->mapWithKeys(function ($setting) {
            if (!isset($setting['from']) || !isset($setting['to'])) {
                throw new \Exception();
            }

            $currenciesRate = CurrenciesRate::make([
                'from' => $setting['from'],
                'to' => $setting['to'],
                'date' => $this->getNormalizeDate($setting['date'] ?? null),
            ]);

            return [
                CurrenciesRateHelper::getKey($currenciesRate) => $currenciesRate
            ];
        });
    }

    protected function getNormalizeDate($date): Carbon
    {
        $date ??= now();
        return $date instanceof Carbon ? $date : Carbon::parse($date);
    }

    protected function createSourceRateCollection($date): Collection
    {
        return collect([$this->source => CurrenciesRate::make([
            'from' => $this->source,
            'to' => $this->source,
            'quote' => 1,
            'date' => $date
        ])]);
    }

    protected function getSourceRates(Collection $currencies, Carbon $date): Collection
    {
        $sourceRates = $this->createSourceRateCollection($date)
            ->merge($this->repository->getCurrenciesRatesFromSource($currencies, $date));

        if ($currencies->diff($sourceRates->keys())->isNotEmpty()) {
            $sourceRates = $sourceRates->merge(
                $this->driver->getCurrenciesRatesFromSource($currencies->diff($sourceRates->keys()), $date)
            );
        }

        return $sourceRates;
    }

    protected function requestsGroupedByDate(Collection $currenciesRequests): Collection
    {
        return $currenciesRequests->groupBy(function ($request) {
            return $request->date->format('Y-m-d');
        });
    }
}
