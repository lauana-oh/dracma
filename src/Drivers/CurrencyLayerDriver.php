<?php

namespace LauanaOH\Dracma\Drivers;

use Carbon\Carbon;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Collection;
use LauanaOH\Dracma\Contracts\DriverContract;
use LauanaOH\Dracma\Models\CurrenciesRate;

class CurrencyLayerDriver implements DriverContract
{
    protected ?string $baseUrl;
    protected ?string $accessKey;
    protected ?string $source;
    protected ClientInterface $client;

    public function __construct()
    {
        $this->baseUrl = config('dracma.drivers.currency_layer.url');
        $this->accessKey = config('dracma.drivers.currency_layer.access_key');
        $this->source = config('dracma.source', 'USD');
        $this->client = app(ClientInterface::class);
    }

    public function getName(): string
    {
        return 'currency_layer';
    }

    public function getCurrenciesRatesFromSource(Collection $currencies, Carbon $date): Collection
    {
        $currenciesRates = collect();
        $response = $this->fetchHistoricalRate(
            $this->source,
            $currencies->implode(','),
            $date->format('Y-m-d')
        );

        if ($response['success'] ?? null) {
            foreach ($response['quotes'] as $key => $quote) {
                $from = substr($key, 0, 3);
                $to = substr($key, -3);

                $currenciesRates->prepend(
                    CurrenciesRate::updateOrCreate(
                        compact('from', 'to', 'date'),
                        compact('quote')
                    ),
                    $to
                );
            }
        }

        return $currenciesRates;
    }

    protected function fetchHistoricalRate(string $from, string $to, string $date): ?array
    {
        $response = $this->client->request('GET', $this->baseUrl.'/historical', [
            'query' => [
                'access_key' => $this->accessKey,
                'date' => $date,
                'source' => $from,
                'currencies' => $to,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
