<?php

namespace RohanAdhikari\FilamentNepaliDatetime;

class NepaliDatePicker extends NepaliDatetimePicker
{
    public function hasTime(): bool
    {
        return false;
    }
}
