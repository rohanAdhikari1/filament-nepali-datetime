<?php

namespace RohanAdhikari\FilamentNepaliDatetime\Concerns;

trait CanFormat
{
    public static function getDefaultFormat()
    {
        return config('default-format', 'Y-m-d');
    }
}
