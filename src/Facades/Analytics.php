<?php

namespace Squirtle\Analytics\Facades;

use Illuminate\Support\Facades\Facade;

class Analytics extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'analytics';
    }
}