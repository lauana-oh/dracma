# Dracma Laravel Package

Dracma is a Laravel package to manage currencies rates and conversions.
It uses database storage and api to fetch the rates.

## Installation

You must add the repository in your `composer.json`
```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/lauana-oh/dracma.git"
        }
    ],
}
```

Then, you can install the package via composer
``` bash
composer require lauana-oh/dracma
```

You must publish and run the migrations to create the `language_lines` table:

```bash
php artisan vendor:publish --provider="LauanaOH\Dracma\DracmaServiceProvider" --tag="migrations"
php artisan migrate
```

Optionally you could publish the config file using this command.

```bash
php artisan vendor:publish --provider="LauanaOH\Dracma\DracmaServiceProvider" --tag="config"
```

## Usage

### Methods
You can use our facade to do different things as get a currencies
rate quote, get multiple currencies rates and convert values between currencies.

#### getCurrenciesRateQuote(string $from, string $to, $date = null): ?float
```php
    Dracma::getCurrenciesRateQuote('COP', 'BRL') // 5.67
    Dracma::getCurrenciesRateQuote('COP', 'BRL', '2021-11-26') // 5.63
    Dracma::getCurrenciesRateQuote('COP', 'BRL', Carbon::parse('2021-11-26')) // 5.63
```

#### getMultiplesCurrenciesRates(array $currenciesRequests): ?array
```php
$request = [
    [
        'from' => 'USD',
        'to' => 'BRL',
        'date' => '2021-11-26',
    ],
    _[
        'from' => 'USD',
        'to' => 'CAD',
    ]_,
    [
        'from' => 'CAD',
        'to' => 'CLP',
        'date' => '2021-11-26',
    ],
];

$currenciesRates = Dracma::getMultiplesCurrenciesRates($request);

/**
 * [
    "2021-11-26_USD_BRL" => array:4 [
        "from" => "USD"
        "to" => "BRL"
        "date" => "2021-11-26"
        "quote" => 5.63
    ]
    "2021-11-26_USD_CAD" => array:4 [
        "from" => "USD"
        "to" => "CAD"
        "date" => "2021-11-27"
        "quote" => 1.28
    ]
    "2021-11-26_CAD_CLP" => array:4 [
        "from" => "CAD"
        "to" => "CLP"
        "date" => "2021-11-26"
        "quote" => 650.039
    ]
]
*/

```

#### convert(string $from, string $to, string $value, $date = null): ?float
```php
Dracma::convert('USD', 'BRL', 10) // 56.70
Dracma::convert('USD', 'BRL', 10, '2021-11-26') // 56.30
Dracma::convert('USD', 'BRL', 10, Carbon::parse('2021-11-26')) // 56.30
```

#### convertMany(array $currenciesRequests): ?array
```php
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

    $converted = Dracma::convertMany($request);

/**
[
    0 => array:6 [
        "from" => "USD"
        "to" => "BRL"
        "value" => 10
        "date" => "2021-11-27"
        "quote" => 5.67
        "result" => 56.7
    ]
    1 => array:6 [
        "from" => "USD"
        "to" => "CLP"
        "date" => "2021-11-26"
        "value" => 1
        "quote" => 832.05
        "result" => 832.0
    ]
]
 */
```

### Command
Dracma has a command, so you can daily fill in the database
with currencies rate fetch data.
```bash
    php artisan dracma:fetch
```

Optionally, you can specify which drive and/or date do you want
to fetch the rates' information:
```bash
    php artisan dracma:fetch 2021-11-25 --driver=currency_layer
```
