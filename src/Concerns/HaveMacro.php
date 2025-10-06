<?php

namespace RohanAdhikari\FilamentNepaliDatetime\Concerns;

use Carbon\Carbon;
use Closure;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use RohanAdhikari\FilamentNepaliDatetime\FilamentNepaliDatetimeServiceProvider;
use RohanAdhikari\FilamentNepaliDatetime\Services\NepaliCurrency;
use RohanAdhikari\FilamentNepaliDatetime\Services\NepaliDate;

trait HaveMacro
{
    public function registerTextColumnMacros(): void
    {
        TextColumn::macro('toNepaliDate', function (string|Closure|null $format = null, string $locale = 'en', string|Closure|null $timezone = null): static {
            /** @var TextColumn $this */
            $this->formatStateUsing(static function (TextColumn $column, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliDate::fromAd(Carbon::parse($state)
                    ->setTimezone($timezone ?? $column->getTimezone()))
                    ->locale($locale)
                    ->format($format ?? FilamentNepaliDatetimeServiceProvider::getDefaultFormat());
            });

            return $this;
        });

        TextColumn::macro('nepaliDate', function (string|Closure|null $stateFormat = null, string|Closure|null $format = null, string $locale = 'en'): static {
            /** @var TextColumn $this */
            $this->formatStateUsing(static function ($state) use ($format, $locale, $stateFormat): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliDate::parse($state, $stateFormat)
                    ->locale($locale)
                    ->format($format ?? FilamentNepaliDatetimeServiceProvider::getDefaultFormat());
            });

            return $this;
        });
    }

    public function registerTextEntryMacros(): void
    {
        TextEntry::macro('toNepaliDate', function (string|Closure|null $format = null, string|Closure $locale = 'en', string|Closure|null $timezone = null): static {
            /** @var TextEntry $this */
            $this->formatStateUsing(static function (TextEntry $component, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliDate::fromAd(Carbon::parse($state)
                    ->setTimezone($timezone ?? $component->getTimezone()))
                    ->locale($locale)
                    ->format($format ?? FilamentNepaliDatetimeServiceProvider::getDefaultFormat());
            });

            return $this;
        });

        TextEntry::macro('nepaliDate', function (string|Closure|null $stateFormat = null, string|Closure|null $format = null, string $locale = 'en'): static {
            /** @var TextEntry $this */
            $this->formatStateUsing(static function ($state) use ($format, $locale, $stateFormat): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliDate::parse($state, $stateFormat)
                    ->locale($locale)
                    ->format($format ?? FilamentNepaliDatetimeServiceProvider::getDefaultFormat());
            });

            return $this;
        });

        TextEntry::macro('nepaliNumber', function (string|bool $currencySymbol = false, $only = false, string $locale = 'en', bool $format = true): static {
            /** @var TextEntry $this */
            $this->formatStateUsing(static function ($state) use ($currencySymbol, $only, $locale, $format): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliCurrency::getNepaliCurrency($state, $format, $currencySymbol, $only, $locale);
            });

            return $this;
        });

        TextEntry::macro('nepaliWord', function (bool $currency = false, $only = false, string $locale = 'en'): static {
            /** @var TextEntry $this */
            $this->formatStateUsing(static function ($state) use ($currency, $only, $locale): ?string {
                if (blank($state)) {
                    return null;
                }

                return NepaliCurrency::getNepaliWord($state, $currency, $locale, $only);
            });

            return $this;
        });
    }
}
