<?php

namespace RohanAdhikari\FilamentNepaliDateTime\Model;

use RohanAdhikari\NepaliDate\NepaliDate;

class NepaliRange
{
    public function __construct(
        public string $label,
        public string | NepaliDate $startDate,
        public string | NepaliDate $endDate,
    ) {}

    public static function make(string $label, string | NepaliDate $startDate, string | NepaliDate $endDate): static
    {
        return new static($label, $startDate, $endDate);
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }
}
