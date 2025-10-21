<?php

namespace RohanAdhikari\FilamentNepaliDatetime\TextColumn;

use Carbon\Carbon;
use Closure;
use Filament\Tables\Columns\TextColumn;
use RohanAdhikari\NepaliDate\NepaliDate;
use RohanAdhikari\NepaliDate\NepaliDateInterface;
use RohanAdhikari\NepaliDate\NepaliNumbers;

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

            $format ??= fn (TextColumn $component): string => $component->getTable()->getDefaultDateTimeDisplayFormat();

            return $this->nepaliDate($format, $timezone, $locale);
        };
    }

    public function toNepaliDateTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->dateTime();

            $format ??= fn (TextColumn $component): string => $component->getTable()->getDefaultDateTimeDisplayFormat();

            return $this->toNepaliDate($format, $timezone, $locale);
        };
    }

    public function nepaliTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->time();

            $format ??= fn (TextColumn $component): string => $component->getTable()->getDefaultTimeDisplayFormat();

            return $this->nepaliDate($format, $timezone, $locale);
        };
    }

    public function toNepaliTime(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $this->time();

            $format ??= fn (TextColumn $component): string => $component->getTable()->getDefaultTimeDisplayFormat();

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
            $format ??= fn (TextColumn $component): string => $component->getTable()->getDefaultDateTimeDisplayFormat();

            return $this->nepaliDateTooltip($format, $timezone, $locale);
        };
    }

    public function timeTooltip(): Closure
    {
        return function (string | Closure | null $format = null, string | Closure | null $timezone = null, string | Closure | null $locale = null): static {
            $format ??= fn (TextColumn $component): string => $component->getTable()->getDefaultTimeDisplayFormat();

            return $this->nepaliDateTooltip($format, $timezone, $locale);
        };
    }

    public function nepaliSinceTooltip(): Closure
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

    public function nepaliNumber(): Closure
    {
        return function (string | Closure | bool $currencySymbol = false, Closure | bool $only = false, string | Closure $locale = 'en', bool | Closure $format = true) {
            $this->formatStateUsing(static function (TextColumn $component, $state) use ($currencySymbol, $only, $locale, $format): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! is_numeric($state)) {
                    return $state;
                }

                return NepaliNumbers::getNepaliCurrency(
                    $state,
                    $component->evaluate($currencySymbol),
                    $component->evaluate($only),
                    $component->evaluate($format),
                    $component->evaluate($locale) ?? 'en'
                );
            });

            return $this;
        };
    }

    public function nepaliMoney(): Closure
    {
        return function (string | bool | Closure | null $currencySymbol = true, int | Closure $divideBy = 0, string | Closure | null $locale = null, bool | Closure $only = true): static {
            $this->money();
            $this->formatStateUsing(static function (TextColumn $component, $state) use ($currencySymbol, $divideBy, $locale, $only): ?string {
                if (blank($state)) {
                    return null;
                }
                if (! is_numeric($state)) {
                    return $state;
                }
                $currencySymbol = $component->evaluate($currencySymbol);
                $only = $component->evaluate($only);
                $locale = $component->evaluate($locale) ?? 'en';
                if ($divideBy = $component->evaluate($divideBy) && (int) $state != 0) {
                    $state /= $divideBy;
                }

                return NepaliNumbers::getNepaliCurrency($state, $currencySymbol, $only, true, $locale);
            });

            return $this;
        };
    }

    public function nepaliNumeric(): Closure
    {
        return function (string | Closure | null $locale = null): static {
            $this->numeric();

            $this->formatStateUsing(static function (TextColumn $component, $state) use ($locale): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! is_numeric($state)) {
                    return $state;
                }

                $locale = $component->evaluate($locale) ?? 'en';
                $state = NepaliNumbers::nepaliNumberFormat($state);
                if ($locale == 'en') {
                    return $state;
                }

                return NepaliNumbers::convertToNepali($state);
            });

            return $this;
        };
    }

    public function nepaliNumberTooltip(): Closure
    {
        return function (string | Closure | bool $currencySymbol = false, Closure | bool $only = false, string | Closure $locale = 'en', bool | Closure $format = true) {
            $this->tooltip(static function (TextColumn $component, $state) use ($currencySymbol, $only, $locale, $format): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! is_numeric($state)) {
                    return $state;
                }

                return NepaliNumbers::getNepaliCurrency(
                    $state,
                    $component->evaluate($currencySymbol),
                    $component->evaluate($only),
                    $component->evaluate($format),
                    $component->evaluate($locale) ?? 'en'
                );
            });

            return $this;
        };
    }

    public function nepaliMoneyTooltip(): Closure
    {
        return function (string | bool | Closure | null $currencySymbol = true, int | Closure $divideBy = 0, string | Closure | null $locale = null, bool | Closure $only = true): static {
            $this->tooltip(static function (TextColumn $component, $state) use ($currencySymbol, $divideBy, $locale, $only): ?string {
                if (blank($state)) {
                    return null;
                }
                if (! is_numeric($state)) {
                    return $state;
                }
                $currencySymbol = $component->evaluate($currencySymbol);
                $only = $component->evaluate($only);
                $locale = $component->evaluate($locale) ?? 'en';
                if ($divideBy = $component->evaluate($divideBy) && (int) $state != 0) {
                    $state /= $divideBy;
                }

                return NepaliNumbers::getNepaliCurrency($state, $currencySymbol, $only, true, $locale);
            });

            return $this;
        };
    }

    public function nepaliNumericTooltip(): Closure
    {
        return function (string | Closure | null $locale = null): static {
            $this->tooltip(static function (TextColumn $component, $state) use ($locale): ?string {
                if (blank($state)) {
                    return null;
                }

                if (! is_numeric($state)) {
                    return $state;
                }

                $locale = $component->evaluate($locale) ?? 'en';
                $state = NepaliNumbers::nepaliNumberFormat($state);
                if ($locale == 'en') {
                    return $state;
                }

                return NepaliNumbers::convertToNepali($state);
            });

            return $this;
        };
    }
}
