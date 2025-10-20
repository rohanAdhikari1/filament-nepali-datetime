<?php

namespace RohanAdhikari\FilamentNepaliDatetime\Concerns;

use Closure;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use RohanAdhikari\FilamentNepaliDatetime\FilamentNepaliDatetimeServiceProvider;
use RohanAdhikari\NepaliDate\NepaliDate;
use RohanAdhikari\NepaliDate\NepaliNumbers;

trait HaveMacro
{
    public function registerTextColumnMacros(): void
    {
        TextColumn::macro('toNepaliDate', function (string | Closure | null $format = null, string | Closure $locale = 'en', string | Closure | null $timezone = null) {
            /** @var TextColumn $this */
            $this->formatStateUsing(static function (TextColumn $column, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliDate::fromNotation($state, $timezone ?? $column->getTimezone())
                    ->locale(
                        $column->evaluate($locale)
                    )
                    ->format(
                        $column->evaluate($format) ?? FilamentNepaliDatetimeServiceProvider::getDefaultFormat()
                    );
            });

            return $this;
        });

        TextColumn::macro('nepaliDate', function (string | Closure | null $stateFormat = null, string | Closure | null $format = null, string | Closure $locale = 'en') {
            /** @var TextColumn $this */
            $this->formatStateUsing(static function (TextColumn $column, $state) use ($format, $locale, $stateFormat): ?string {
                if (blank($state)) {
                    return null;
                }
                $stateFormat = (string)$column->evaluate($stateFormat);
                $date = $stateFormat ? NepaliDate::createFromFormat($stateFormat, $state) : NepaliDate::parse($state);

                return $date
                    ->locale(
                        $column->evaluate($locale)
                    )
                    ->format(
                        $column->evaluate($format) ?? FilamentNepaliDatetimeServiceProvider::getDefaultFormat()
                    );
            });

            return $this;
        });
    }

    public function registerTextEntryMacros(): void
    {
        TextEntry::macro('toNepaliDate', function (string | Closure | null $format = null, string | Closure $locale = 'en', string | Closure | null $timezone = null) {
            /** @var TextEntry $this */
            $this->formatStateUsing(static function (TextEntry $entry, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliDate::fromNotation(
                    $state,
                    $entry->evaluate($timezone) ?? $entry->getTimezone()
                )
                    ->locale($entry->evaluate($locale))
                    ->format($entry->evaluate($format) ?? FilamentNepaliDatetimeServiceProvider::getDefaultFormat());
            });

            return $this;
        });

        TextEntry::macro('nepaliDate', function (string | Closure | null $stateFormat = null, string | Closure | null $format = null, string | Closure $locale = 'en') {
            /** @var TextEntry $this */
            $this->formatStateUsing(static function (TextEntry $entry, $state) use ($format, $locale, $stateFormat): ?string {
                if (blank($state)) {
                    return null;
                }
                $stateFormat = (string)$entry->evaluate($stateFormat);
                $date = $stateFormat ? NepaliDate::createFromFormat($stateFormat, $state) : NepaliDate::parse($state);

                return $date
                    ->locale($entry->evaluate($locale))
                    ->format($entry->evaluate($format) ?? FilamentNepaliDatetimeServiceProvider::getDefaultFormat());
            });

            return $this;
        });

        TextEntry::macro('nepaliNumber', function (string | bool $currencySymbol = false, $only = false, string $locale = 'en', bool $format = true) {
            /** @var TextEntry $this */
            $this->formatStateUsing(static function ($state) use ($currencySymbol, $only, $locale, $format): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliNumbers::getNepaliCurrency($state, $currencySymbol, $only, $format, $locale);
            });

            return $this;
        });

        TextEntry::macro('nepaliWord', function (bool $currency = false, $only = false, string $locale = 'en') {
            /** @var TextEntry $this */
            $this->formatStateUsing(static function ($state) use ($currency, $only, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliNumbers::getNepaliWord($state, $currency, $locale, $only);
            });

            return $this;
        });
    }
}
