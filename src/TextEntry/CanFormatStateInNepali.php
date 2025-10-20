<?php

namespace RohanAdhikari\FilamentNepaliDatetime\TextEntry;

use Carbon\Carbon;
use Closure;
use Filament\Infolists\Components\TextEntry;
use RohanAdhikari\NepaliDate\NepaliDate;
use RohanAdhikari\NepaliDate\NepaliDateInterface;
use RohanAdhikari\NepaliDate\NepaliNumbers;

trait CanFormatStateInNepali
{
    public function nepaliDate(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->date();
            $this->formatStateUsing(static function (TextEntry $component, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! $state instanceof NepaliDateInterface) {
                    $state = NepaliDate::parse($state);
                }

                return $state
                    ->setTimezone($component->evaluate($timezone) ?? $component->getTimezone())
                    ->setLocale($component->evaluate($locale) ?? 'en')
                    ->format($component->evaluate($format) ?? $component->getContainer()->getDefaultDateDisplayFormat());
            });

            return $this;
        };
    }

    public function toNepaliDate(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->date();

            $this->formatStateUsing(static function (TextEntry $component, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! $state instanceof NepaliDateInterface) {
                    $state = NepaliDate::fromNotation($state);
                }

                return $state
                    ->setTimezone($component->evaluate($timezone) ?? $component->getTimezone())
                    ->setLocale($component->evaluate($locale) ?? 'en')
                    ->format($component->evaluate($format) ?? $component->getContainer()->getDefaultDateDisplayFormat());
            });

            return $this;
        };
    }

    public function nepaliDateTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->dateTime();

            $format ??= fn (TextEntry $component): string => $component->getContainer()->getDefaultDateTimeDisplayFormat();

            return $this->nepaliDate($format, $timezone, $locale);
        };
    }

    public function toNepaliDateTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->dateTime();

            $format ??= fn (TextEntry $component): string => $component->getContainer()->getDefaultDateTimeDisplayFormat();

            return $this->toNepaliDate($format, $timezone, $locale);
        };
    }

    public function nepaliTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->time();

            $format ??= fn (TextEntry $component): string => $component->getContainer()->getDefaultTimeDisplayFormat();

            return $this->nepaliDate($format, $timezone, $locale);
        };
    }

    public function toNepaliTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->time();

            $format ??= fn (TextEntry $component): string => $component->getContainer()->getDefaultTimeDisplayFormat();

            return $this->toNepaliDate($format, $timezone, $locale);
        };
    }

    public function nepaliSince(): Closure
    {
        return function (string | Closure | null $timezone = null): static {
            $this->dateTime();

            $this->formatStateUsing(static function (TextEntry $component, $state) use ($timezone): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! $state instanceof NepaliDateInterface) {
                    $state = NepaliDate::parse($state);
                }

                return Carbon::instance($state->toAd())
                    ->setTimezone($component->evaluate($timezone) ?? $component->getTimezone())
                    ->diffForHumans();
            });

            return $this;
        };
    }

    // public function toNepaliSince() {}

    public function nepaliDateTooltip(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->tooltip(static function (TextEntry $component, mixed $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! $state instanceof NepaliDateInterface) {
                    $state = NepaliDate::parse($state);
                }

                return $state
                    ->setTimezone($component->evaluate($timezone) ?? $component->getTimezone())
                    ->setLocale($component->evaluate($locale) ?? 'en')
                    ->format($component->evaluate($format) ?? $component->getContainer()->getDefaultDateDisplayFormat());
            });

            return $this;
        };
    }

    public function nepaliDateTimeTooltip(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $format ??= fn (TextEntry $component): string => $component->getContainer()->getDefaultDateTimeDisplayFormat();

            return $this->nepaliDateTooltip($format, $timezone, $locale);
        };
    }

    public function timeTooltip(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $format ??= fn (TextEntry $component): string => $component->getContainer()->getDefaultTimeDisplayFormat();

            return $this->nepaliDateTooltip($format, $timezone, $locale);
        };
    }

    public function sinceTooltip(): Closure
    {
        return function (string | Closure | null $timezone = null): static {
            $this->tooltip(static function (TextEntry $component, mixed $state) use ($timezone): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! $state instanceof NepaliDateInterface) {
                    $state = NepaliDate::parse($state);
                }

                return Carbon::instance($state->toAd())
                    ->setTimezone($component->evaluate($timezone) ?? $component->getTimezone())
                    ->diffForHumans();
            });

            return $this;
        };
    }

    public function nepaliNumber(): Closure
    {
        return function (string | bool $currencySymbol = false, $only = false, string $locale = 'en', bool $format = true) {
            $this->formatStateUsing(static function ($state) use ($currencySymbol, $only, $locale, $format): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliNumbers::getNepaliCurrency($state, $currencySymbol, $only, $format, $locale);
            });

            return $this;
        };
    }

    public function nepaliWord(): Closure
    {
        return function (bool $currency = false, $only = false, string $locale = 'en') {
            $this->formatStateUsing(static function ($state) use ($currency, $only, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliNumbers::getNepaliWord($state, $currency, $locale, $only);
            });

            return $this;
        };
    }
}
