<?php

namespace LauanaOH\Dracma\Helpers;

use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exchange\FixedExchange;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;

class ConvertHelper
{
    protected static ISOCurrencies $currencies;
    protected static DecimalMoneyParser $parser;
    protected static DecimalMoneyFormatter $formatter;

    public static function convert(string $from, string $to, string $value, float $quote): float
    {
        $converter = new Converter(self::getCurrencies(), new FixedExchange([
            $from => [ $to => $quote ]
        ]));

        $convertedValue = $converter->convert(self::getParser()->parse($value, new Currency($from)), new Currency($to));

        return self::getFormatter()->format($convertedValue);
    }

    protected static function getCurrencies(): ISOCurrencies
    {
        if (!isset(self::$currencies)) {
            self::$currencies = new ISOCurrencies();
        }

        return self::$currencies;
    }

    protected static function getParser(): DecimalMoneyParser
    {
        if (!isset(self::$parser)) {
            self::$parser = new DecimalMoneyParser(self::getCurrencies());
        }

        return self::$parser;
    }

    protected static function getFormatter(): DecimalMoneyFormatter
    {
        if (!isset(self::$formatter)) {
            self::$formatter = new DecimalMoneyFormatter(self::getCurrencies());
        }

        return self::$formatter;
    }
}