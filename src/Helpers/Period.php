<?php

namespace Squirtle\Analytics\Helpers;

use DateTimeInterface;
use Carbon\Carbon;
use Squirtle\Analytics\Exceptions\InvalidPeriod;

class Period
{

    public DateTimeInterface $startDate;

    public DateTimeInterface $endDate;


    /**
     * @throws InvalidPeriod
     */
    public function __construct(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        if ($startDate > $endDate) {
            throw InvalidPeriod::validateDateRange($startDate, $endDate);
        }

        $this->startDate = $startDate;

        $this->endDate = $endDate;
    }

    /**
     * @throws InvalidPeriod
     */
    public static function create(DateTimeInterface $startDate, DateTimeInterface $endDate): self
    {
        return new static($startDate, $endDate);
    }

    /**
     * @throws InvalidPeriod
     */
    public static function days(int $numberOfDays): Period
    {
        $endDate = Carbon::today();

        $startDate = Carbon::today()->subDays($numberOfDays)->startOfDay();

        return new static($startDate, $endDate);
    }

    /**
     * @throws InvalidPeriod
     */
    public static function months(int $numberOfMonths): Period
    {
        $endDate = Carbon::today();

        $startDate = Carbon::today()->subMonths($numberOfMonths)->startOfDay();

        return new static($startDate, $endDate);
    }

    /**
     * @throws InvalidPeriod
     */
    public static function years(int $numberOfYears): Period
    {
        $endDate = Carbon::today();

        $startDate = Carbon::today()->subYears($numberOfYears)->startOfDay();

        return new static($startDate, $endDate);
    }
}