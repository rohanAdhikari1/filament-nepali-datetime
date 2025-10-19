<?php

namespace RohanAdhikari\FilamentNepaliDatetime;

use Carbon\CarbonInterface;
use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;
use RohanAdhikari\FilamentNepaliDatetime\StateCasts\NepaliDateTimeStateCast;
use RohanAdhikari\NepaliDate\Exceptions\NepaliDateFormatException;
use RohanAdhikari\NepaliDate\NepaliDate;

class NepaliDatetimePicker extends DateTimePicker
{
    protected string $view = 'filament-nepali-datetime::nepali-date-time-picker';

    protected CarbonInterface | NepaliDate | string | Closure | null $bsMaxDate = null;

    protected CarbonInterface | NepaliDate | string | Closure | null $bsMinDate = null;

    protected CarbonInterface | NepaliDate | string | Closure | null $bsDefaultFocusedDate = null;

    protected bool | Closure $dehydrateStateInNepali = false;

    /**
     * @return array<StateCast>
     */
    public function getDefaultStateCasts(): array
    {
        return [
            app(NepaliDateTimeStateCast::class, [
                'format' => $this->getFormat(),
                'internalFormat' => $this->getInternalFormat(),
                'locale' => $this->getLocale(),
                'timezone' => $this->getTimezone(),
            ]),
        ];
    }

    public function dehydrateStateInNepali(Closure | bool $condition = true): static
    {
        $this->dehydrateStateInNepali = $condition;

        return $this;
    }

    public function getInternalFormat(): string
    {
        return 'Y-m-d H:i:s';
    }

    public function getMaxDate(): ?string
    {
        if (blank($this->bsMaxDate)) {
            return null;
        }

        return $this->getNepaliFormatDate($this->bsMaxDate);
    }

    public function getMinDate(): ?string
    {
        if (blank($this->bsMinDate)) {
            return null;
        }

        return $this->getNepaliFormatDate($this->bsMinDate);
    }

    public function maxDate(CarbonInterface | NepaliDate | string | Closure | null $date): static
    {
        $this->bsMaxDate = $date;

        $this->rule(static fn (NepaliDatetimePicker $component) => "before_or_equal:{$component->getMaxDate()}", static fn (NepaliDatetimePicker $component): bool => (bool) $component->getMaxDate());

        return $this;
    }

    public function minDate(CarbonInterface | NepaliDate | string | Closure | null $date): static
    {
        $this->bsMinDate = $date;

        $this->rule(static fn (NepaliDatetimePicker $component) => "after_or_equal:{$component->getMinDate()}", static fn (NepaliDatetimePicker $component): bool => (bool) $component->getMinDate());

        return $this;
    }

    public function defaultFocusedDate(CarbonInterface | NepaliDate | string | Closure | null $date): static
    {
        $this->bsDefaultFocusedDate = $date;

        return $this;
    }

    public function disabledDates(array | Closure $dates): static
    {
        $this->disabledDates = $dates;

        return $this;
    }

    public function getNepaliFormatDate(CarbonInterface | NepaliDate | string | Closure $date): ?string
    {
        $date = $this->evaluate($date);
        if ($date instanceof CarbonInterface) {
            $date = NepaliDate::fromAd($date->toDateTime());
        }
        if (! $date instanceof NepaliDate) {
            try {
                $date = NepaliDate::createFromFormat($this->getFormat(), (string) $date);
            } catch (NepaliDateFormatException) {
                try {
                    $date = NepaliDate::parse($date);
                } catch (NepaliDateFormatException) {
                    return null;
                }
            }
        }

        return $date->setTimezone($this->getTimezone())->format($this->getInternalFormat());
    }

    public function getDisabledDates(): array
    {
        $dates = $this->evaluate($this->disabledDates);
        $nepaliDates = array_map(fn ($date) => $this->getNepaliFormatDate($date), $dates);

        return $nepaliDates;
    }

    public function getDefaultFocusedDate(): ?string
    {
        $defaultFocusedDate = $this->evaluate($this->bsDefaultFocusedDate);

        if (filled($defaultFocusedDate)) {
            $defaultFocusedDate = $this->getNepaliFormatDate($defaultFocusedDate);
        }

        return $defaultFocusedDate;
    }

    public function getDehydrateStateToNepali(): bool
    {

        return $this->evaluate($this->dehydrateStateInNepali);
    }

    public function mutateDehydratedState(mixed $state): mixed
    {
        try {
            $newState = NepaliDate::createFromFormat($this->getFormat(), $state)->locale('en')->format($this->getFormat());
        } catch (\Throwable) {
            $newState = null;
        }

        return parent::mutateDehydratedState($newState);
    }

    public function mutatesDehydratedState(): bool
    {
        return parent::mutatesDehydratedState() || (! $this->getDehydrateStateToNepali() && $this->getLocale() == 'np');
    }

    public function locale(string | Closure | null $locale): static
    {
        if ($locale != 'en' && $locale != 'np') {
            $locale = null;
        }
        $this->locale = $locale;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->evaluate($this->locale) ?? 'en';
    }

    public function weekStartsOnSunday(): static
    {
        $this->firstDayOfWeek(1);

        return $this;
    }

    public function weekStartsOnMonday(): static
    {
        $this->firstDayOfWeek(2);

        return $this;
    }

    public function weekStartsOnSaturday(): static
    {
        $this->firstDayOfWeek(7);

        return $this;
    }
}
