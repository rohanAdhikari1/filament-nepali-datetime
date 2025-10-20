<?php

namespace RohanAdhikari\FilamentNepaliDatetime\TextColumn;

use Carbon\Carbon;
use Closure;
use Filament\Tables\Columns\TextColumn;
use RohanAdhikari\NepaliDate\NepaliDate;
use RohanAdhikari\NepaliDate\NepaliDateInterface;

trait CanFormatStateInNepali
{
    public function nepaliDate(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->date();
            $this->formatStateUsing(static function (TextColumn $component, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! $state instanceof NepaliDateInterface) {
                    $state = NepaliDate::parse($state);
                }

                return $state
                    ->setTimezone($component->evaluate($timezone) ?? $component->getTimezone())
                    ->setLocale($component->evaluate($locale) ?? 'en')
                    ->format($component->evaluate($format) ?? $component->getTable()->getDefaultDateDisplayFormat());
            });

            return $this;
        };
    }

    public function toNepaliDate(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->date();

            $this->formatStateUsing(static function (TextColumn $component, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! $state instanceof NepaliDateInterface) {
                    $state = NepaliDate::fromNotation($state);
                }

                return $state
                    ->setTimezone($component->evaluate($timezone) ?? $component->getTimezone())
                    ->setLocale($component->evaluate($locale) ?? 'en')
                    ->format($component->evaluate($format) ?? $component->getTable()->getDefaultDateDisplayFormat());
            });

            return $this;
        };
    }

    public function nepaliDateTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->dateTime();

            $format ??= fn(TextColumn $component): string => $component->getTable()->getDefaultDateTimeDisplayFormat();

            return $this->nepaliDate($format, $timezone, $locale);
        };
    }

    public function toNepaliDateTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->dateTime();

            $format ??= fn(TextColumn $component): string => $component->getTable()->getDefaultDateTimeDisplayFormat();

            return $this->toNepaliDate($format, $timezone, $locale);
        };
    }

    public function nepaliTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->time();

            $format ??= fn(TextColumn $component): string => $component->getTable()->getDefaultTimeDisplayFormat();

            return $this->nepaliDate($format, $timezone, $locale);
        };
    }

    public function toNepaliTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->time();

            $format ??= fn(TextColumn $component): string => $component->getTable()->getDefaultTimeDisplayFormat();

            return $this->toNepaliDate($format, $timezone, $locale);
        };
    }

    public function nepaliSince(): Closure
    {
        return function (string | Closure | null $timezone = null): static {
            $this->dateTime();

            $this->formatStateUsing(static function (TextColumn $component, $state) use ($timezone): ?string {
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
            $this->tooltip(static function (TextColumn $component, mixed $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! $state instanceof NepaliDateInterface) {
                    $state = NepaliDate::parse($state);
                }

                return $state
                    ->setTimezone($component->evaluate($timezone) ?? $component->getTimezone())
                    ->setLocale($component->evaluate($locale) ?? 'en')
                    ->format($component->evaluate($format) ?? $component->getTable()->getDefaultDateDisplayFormat());
            });

            return $this;
        };
    }

    public function nepaliDateTimeTooltip(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $format ??= fn(TextColumn $component): string => $component->getTable()->getDefaultDateTimeDisplayFormat();

            return $this->nepaliDateTooltip($format, $timezone, $locale);
        };
    }

    public function timeTooltip(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $format ??= fn(TextColumn $component): string => $component->getTable()->getDefaultTimeDisplayFormat();

            return $this->nepaliDateTooltip($format, $timezone, $locale);
        };
    }

    public function sinceTooltip(): Closure
    {
        return function (string | Closure | null $timezone = null): static {
            $this->tooltip(static function (TextColumn $component, mixed $state) use ($timezone): ?string {
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
}
