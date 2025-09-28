<?php

namespace RohanAdhikari\FilamentNepaliDatetime\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RohanAdhikari\FilamentNepaliDatetime\FilamentNepaliDatetime
 */
class FilamentNepaliDatetime extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \RohanAdhikari\FilamentNepaliDatetime\FilamentNepaliDatetime::class;
    }
}
