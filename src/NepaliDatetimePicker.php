<?php

namespace RohanAdhikari\FilamentNepaliDatetime;

use Carbon\CarbonInterface;
use Closure;
use Filament\Forms\Components\DateTimePicker;
use InvalidArgumentException;
use RohanAdhikari\FilamentNepaliDatetime\Services\NepaliDate;
use RohanAdhikari\FilamentNepaliDatetime\StateCasts\NepaliDateTimeStateCast;

class NepaliDatetimePicker extends DateTimePicker
{
    protected string $view = 'filament-nepali-datetime::nepali-date-time-picker';

    protected CarbonInterface | NepaliDate | string | Closure | null $bsMaxDate = null;

    protected CarbonInterface | NepaliDate | string | Closure | null $bsMinDate = null;

    protected CarbonInterface | NepaliDate | string | Closure | null $bsDefaultFocusedDate = null;

    protected bool | Closure  $dehydrateStateInNepali = false;


    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(static function (NepaliDatetimePicker $component, $state): void {
            $newState = $state;
            if ($state && !$state instanceof NepaliDate) {
                try {
                    $newState = NepaliDate::parse((string) $state, $component->getFormat())->locale('en')->format($component->getInternalFormat());
                } catch (InvalidArgumentException) {
                    $newState = null;
                }
            }
            $component->state($newState);
        });
    }

    /**
     * @return array<StateCast>
     */
    public function getDefaultStateCasts(): array
    {
        return [
            app(NepaliDateTimeStateCast::class, [
                'format' => $this->getFormat(),
                'internalFormat' => $this->getInternalFormat(),
                'locale' => $this->getLocale()
            ]),
        ];
    }

    public function dehydrateStateToNepali(Closure | bool $condition = true): static
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
        return $this->getNepaliFormatDate($this->bsMaxDate);
    }

    public function getMinDate(): ?string
    {
        return $this->getNepaliFormatDate($this->bsMinDate);
    }


    public function maxDate(CarbonInterface | NepaliDate | string | Closure | null $date): static
    {
        $this->bsMaxDate = $date;

        $this->rule(static function (NepaliDatetimePicker $component) {
            return "before_or_equal:{$component->getMaxDate()}";
        }, static fn(NepaliDatetimePicker $component): bool => (bool) $component->getMaxDate());

        return $this;
    }

    public function minDate(CarbonInterface | NepaliDate | string | Closure | null $date): static
    {
        $this->bsMinDate = $date;

        $this->rule(static function (NepaliDatetimePicker $component) {
            return "after_or_equal:{$component->getMinDate()}";
        }, static fn(NepaliDatetimePicker $component): bool => (bool) $component->getMinDate());

        return $this;
    }

    public function defaultFocusedDate(CarbonInterface | NepaliDate| string | Closure | null $date): static
    {
        $this->bsDefaultFocusedDate = $date;

        return $this;
    }

    public function disabledDates(array | Closure $dates): static
    {
        $this->disabledDates = $dates;

        return $this;
    }

    public function getNepaliFormatDate(CarbonInterface | NepaliDate| string | Closure | null $date): ?string
    {
        $date = $this->evaluate($date);
        if ($date instanceof CarbonInterface) {
            $date = NepaliDate::fromAd($date);
        }
        if ($date instanceof NepaliDate) {
            $date = $date->format();
        }
        return $date;
    }

    public function getDisabledDates(): array
    {
        $dates = $this->evaluate($this->disabledDates);
        $nepaliDates = array_map(function ($date) {
            return $this->getNepaliFormatDate($date);
        }, $dates);
        return $nepaliDates;
    }

    public function getDefaultFocusedDate(): ?string
    {
        $defaultFocusedDate = $this->evaluate($this->bsDefaultFocusedDate);

        if (filled($defaultFocusedDate)) {
            if ($defaultFocusedDate instanceof CarbonInterface) {
                $defaultFocusedDate->setTimezone($this->getTimezone());
            }
        }

        return $this->getNepaliFormatDate($defaultFocusedDate);
    }

    public function getDehydrateStateToNepali(): bool
    {

        return $this->evaluate($this->dehydrateStateInNepali);
    }

    public function mutateDehydratedState(mixed $state): mixed
    {
        try {
            $newState = NepaliDate::parse($state, $this->getFormat(), 'np')->locale('en')->format($this->getFormat());
        } catch (\Throwable $e) {
            $newState = $state;
        }
        return parent::mutateDehydratedState($newState);
    }

    public function mutatesDehydratedState(): bool
    {
        return parent::mutatesDehydratedState() || (!$this->getDehydrateStateToNepali() && $this->getLocale() == 'np');
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
