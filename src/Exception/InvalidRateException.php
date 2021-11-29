<?php

namespace LauanaOH\Dracma\Exception;

class InvalidRateException extends DracmaException
{
    public static function notFound(string $from, string $to, string $date): self
    {
        return new self(trans("The currencies rate from $from to $to on $date could not be found."));
    }
}
