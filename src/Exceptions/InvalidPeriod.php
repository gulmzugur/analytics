<?php

namespace Squirtle\Analytics\Exceptions;

use DateTimeInterface;
use Exception;

class InvalidPeriod extends Exception
{
    public static function validateDateRange(DateTimeInterface $startDate, DateTimeInterface $endDate): InvalidPeriod
    {
        return new static("Start date `{$startDate->format('Y-m-d')}` cannot be after end date `{$endDate->format('Y-m-d')}`.");
    }
}