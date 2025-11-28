<?php

namespace RohanAdhikari\FilamentNepaliDateTime\Model;

class RangeSpan
{
    public function __construct(
        public int $days,
        public int $months = 0,
        public int $years = 0,
    ) {}

    public static function days(int $days): static
    {
        return new static(days: $days);
    }

    public static function day(): static
    {
        return static::days(1);
    }

    public static function months(int $months): static
    {
        return new static(days: 0, months: $months);
    }

    public static function month(): static
    {
        return static::months(1);
    }

    public static function years(int $years): static
    {
        return new static(days: 0, years: $years);
    }

    public static function year(): static
    {
        return static::years(1);
    }

    public function toArray(): array
    {
        return [
            'days' => $this->days,
            'months' => $this->months,
            'years' => $this->years,
        ];
    }
}
