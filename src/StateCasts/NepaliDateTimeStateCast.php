<?php

namespace RohanAdhikari\FilamentNepaliDatetime\StateCasts;

use Carbon\CarbonInterface;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;
use RohanAdhikari\NepaliDate\Exceptions\NepaliDateFormatException;
use RohanAdhikari\NepaliDate\NepaliDate;

class NepaliDateTimeStateCast implements StateCast
{
    public function __construct(
        protected string $format,
        protected string $internalFormat,
        protected string $locale,
        protected string $timezone
    ) {}

    public function get(mixed $state): ?string
    {
        if (blank($state)) {
            return null;
        }
        if (! $state instanceof NepaliDate) {
            try {
                $state = NepaliDate::createFromFormat($this->internalFormat, (string) $state);
            } catch (NepaliDateFormatException) {
                try {
                    $state = NepaliDate::parse((string) $state);
                } catch (NepaliDateFormatException) {
                    return null;
                }
            }
        }
        $state->shiftTimezone($this->timezone);

        return $state->locale($this->locale)->format($this->format);
    }

    public function set(mixed $state): ?string
    {
        if (blank($state)) {
            return null;
        }
        if ($state instanceof CarbonInterface) {
            $state = NepaliDate::fromAd($state->toDateTime());
        }
        if (! $state instanceof NepaliDate) {
            try {
                $state = NepaliDate::createFromFormat($this->format, (string) $state);
            } catch (NepaliDateFormatException) {
                try {
                    $state = NepaliDate::parse((string) $state);
                } catch (NepaliDateFormatException) {
                    return null;
                }
            }
        }
        $state = $state->setTimezone($this->timezone);

        return $state->locale('en')->format($this->internalFormat);
    }
}
