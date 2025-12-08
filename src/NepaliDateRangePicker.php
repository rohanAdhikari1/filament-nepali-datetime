<?php

namespace RohanAdhikari\FilamentNepaliDatetime;

class NepaliDateRangePicker extends NepaliDateTimeRangePicker
{
    public function hasTime(): bool
    {
        return false;
    }
}
