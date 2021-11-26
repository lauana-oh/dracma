<?php

namespace LauanaOH\Dracma\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LauanaOH\Dracma\Database\Factories\CurrenciesRateFactory;

class CurrenciesRate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];

    protected static function newFactory(): CurrenciesRateFactory
    {
        return CurrenciesRateFactory::new();
    }
}