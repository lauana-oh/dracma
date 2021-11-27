<?php

namespace LauanaOH\Dracma\Drivers;

use Carbon\Carbon;
use GuzzleHttp\ClientInterface;
use LauanaOH\Dracma\Contracts\DriverContract;
use LauanaOH\Dracma\Models\CurrenciesRate;

class CurrencyLayerDriver implements DriverContract
{
    protected ?string $baseUrl;
    protected ?string $accessKey;
    protected ClientInterface $client;

    public function __construct()
    {
        $this->baseUrl = config('dracma.drivers.currency_layer.url');
        $this->accessKey = config('dracma.drivers.currency_layer.access_key');
        $this->client = app(ClientInterface::class);
    }

    public function getCurrenciesRate(string $from, string $to, Carbon $date): ?CurrenciesRate
    {
        $currenciesRate = null;
        $response = $this->fetchHistoricalRate($from, $to, $date);

        if ($response['success'] ?? null) {
            $currenciesRate = CurrenciesRate::create([
                'from' => $from,
                'to' => $to,
                'date' => $response['date'],
                'rate' => $response['quotes'][$from.$to],
            ]);
        }

        return $currenciesRate;
    }

    protected function fetchHistoricalRate(string $from, string $to, Carbon $date): ?array
    {
        $response = $this->client->request('GET', $this->baseUrl.'historical', [
            'query' => [
                'access_key' => $this->accessKey,
                'date' => $date->format('Y-m-d'),
                'source' => $from,
                'currencies' => $to,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
