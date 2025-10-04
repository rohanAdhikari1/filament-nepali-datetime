<?php

namespace RohanAdhikari\FilamentNepaliDatetime\StateCasts;

use Carbon\CarbonInterface;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;
use InvalidArgumentException;
use RohanAdhikari\FilamentNepaliDatetime\Services\NepaliDate;

class NepaliDateTimeStateCast implements StateCast
{
    public function __construct(
        protected string $format,
        protected string $internalFormat,
        protected string $locale,
    ) {}

    public function get(mixed $state): ?string
    {
        if (blank($state)) {
            return null;
        }
        if (! $state instanceof NepaliDate) {
            try {
                $state = NepaliDate::parse((string) $state);
            } catch (InvalidArgumentException) {
                try {
                    $state = NepaliDate::parse((string) $state, $this->format);
                } catch (InvalidArgumentException) {
                    return null;
                }
            }
        }
        return $state->locale($this->locale)->format($this->format);
    }

    public function set(mixed $state): ?string
    {
        if (blank($state)) {
            return null;
        }
        if ($state instanceof CarbonInterface) {
            $state = NepaliDate::fromAd($state);
        }
        if ($state instanceof NepaliDate) {
            $state = $state->locale('en')->format($this->internalFormat);
        }
        return $state;
    }
}
