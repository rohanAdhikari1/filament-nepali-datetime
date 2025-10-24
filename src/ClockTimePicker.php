<?php

namespace RohanAdhikari\FilamentNepaliDatetime;

use Carbon\CarbonInterface;
use Carbon\Exceptions\InvalidFormatException;
use Closure;
use DateTime;
use Filament\Forms\Components\Concerns\CanBeReadOnly;
use Filament\Forms\Components\Concerns\HasAffixes;
use Filament\Forms\Components\Concerns\HasDatalistOptions;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Contracts\HasAffixActions;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Support\Carbon;
use Illuminate\View\ComponentAttributeBag;
use RohanAdhikari\FilamentNepaliDatetime\StateCasts\NepaliDateTimeStateCast;
use RohanAdhikari\NepaliDate\NepaliDate;
use RohanAdhikari\NepaliDate\NepaliDateInterface;

class ClockTimePicker extends Field implements HasAffixActions
{
    use CanBeReadOnly;
    use HasAffixes;
    use HasDatalistOptions;
    use HasExtraAlpineAttributes;
    use HasExtraInputAttributes;
    use HasPlaceholder;

    protected string $view = 'filament-nepali-datetime::clock-time-picker';

    protected string | Closure | null $displayFormat = null;

    /**
     * @var array<array<mixed> | Closure>
     */
    protected array $extraTriggerAttributes = [];

    protected string | Closure | null $format = null;

    protected bool | Closure $hasSeconds = true;

    protected bool | Closure $shouldCloseOnTimeSelection = true;

    protected CarbonInterface | NepaliDateInterface | string | Closure | null $maxTime = null;

    protected CarbonInterface | NepaliDateInterface | string | Closure | null $minTime = null;

    protected CarbonInterface | NepaliDateInterface | string | Closure | null $defaultFocusedTime = null;

    protected string | Closure | null $timezone = null;

    protected string | Closure | null $locale = null;

    /**
     * @var array<DateTime | NepaliDate | string> | Closure
     */
    protected array | Closure $disabledTimes = [];

    /**
     * @return array<StateCast>
     */
    public function getDefaultStateCasts(): array
    {
        return [
            ...parent::getDefaultStateCasts(),
            app(NepaliDateTimeStateCast::class, [
                'format' => $this->getFormat(),
                'internalFormat' => $this->getInternalFormat(),
                'locale' => $this->getLocale(),
                'timezone' => $this->getTimezone(),
            ]),
        ];
    }

    public function getInternalFormat(): string
    {
        return $this->hasSeconds() ? 'H:i:s' : 'H:i';
    }

    public function displayFormat(string | Closure | null $format): static
    {
        $this->displayFormat = $format;

        return $this;
    }

    /**
     * @param  array<mixed> | Closure  $attributes
     */
    public function extraTriggerAttributes(array | Closure $attributes, bool $merge = false): static
    {
        if ($merge) {
            $this->extraAttributes[] = $attributes;
        } else {
            $this->extraAttributes = [$attributes];
        }

        return $this;
    }

    public function format(string | Closure | null $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function maxTime(CarbonInterface | string | Closure | null $time): static
    {
        $this->maxTime = $time;

        $this->rule(static function (ClockTimePicker $component) {
            return "before_or_equal:{$component->getMaxTime()}";
        }, static fn(ClockTimePicker $component): bool => (bool) $component->getMaxTime());

        return $this;
    }

    public function minTime(CarbonInterface | string | Closure | null $time): static
    {
        $this->minTime = $time;

        $this->rule(static function (ClockTimePicker $component) {
            return "after_or_equal:{$component->getMinTime()}";
        }, static fn(ClockTimePicker $component): bool => (bool) $component->getMinTime());

        return $this;
    }

    public function defaultFocusedTime(CarbonInterface | string | Closure | null $date): static
    {
        $this->defaultFocusedTime = $date;

        return $this;
    }

    /**
     * @param  array<DateTime | NepaliDate | string> | Closure  $times
     */
    public function disabledTimes(array | Closure $times): static
    {
        $this->disabledTimes = $times;

        return $this;
    }

    public function timezone(string | Closure | null $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function locale(string | Closure | null $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function seconds(bool | Closure $condition = true): static
    {
        $this->hasSeconds = $condition;

        return $this;
    }

    public function closeOnTimeSelection(bool | Closure $condition = true): static
    {
        $this->shouldCloseOnTimeSelection = $condition;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getExtraTriggerAttributes(): array
    {
        $temporaryAttributeBag = new ComponentAttributeBag;

        foreach ($this->extraTriggerAttributes as $extraTriggerAttributes) {
            $temporaryAttributeBag = $temporaryAttributeBag->merge($this->evaluate($extraTriggerAttributes), escape: false);
        }

        return $temporaryAttributeBag->getAttributes();
    }

    public function getExtraTriggerAttributeBag(): ComponentAttributeBag
    {
        return new ComponentAttributeBag($this->getExtraTriggerAttributes());
    }

    public function getFormat(): string
    {
        $format = $this->evaluate($this->format);

        if ($format) {
            return (string) $format;
        }

        $format = 'H:i';

        if (! $this->hasSeconds()) {
            return $format;
        }

        return "{$format}:s";
    }

    public function getDisplayFormat(): string
    {
        $format = $this->evaluate($this->displayFormat);

        if ($format) {
            return (string) $format;
        }

        return $this->getFormat();
    }

    public function getMaxTime(): ?string
    {
        return $this->evaluate($this->maxTime);
    }

    public function getMinTime(): ?string
    {
        return $this->evaluate($this->minTime);
    }

    public function getDefaultFocusedTime(): ?string
    {
        $defaultFocusedTime = $this->evaluate($this->defaultFocusedTime);

        if (filled($defaultFocusedTime)) {
            if (! $defaultFocusedTime instanceof CarbonInterface) {
                try {
                    $defaultFocusedTime = Carbon::createFromFormat($this->getFormat(), (string) $defaultFocusedTime, config('app.timezone'));
                } catch (InvalidFormatException $exception) {
                    try {
                        $defaultFocusedTime = Carbon::parse($defaultFocusedTime, config('app.timezone'));
                    } catch (InvalidFormatException $exception) {
                        return null;
                    }
                }
            }

            $defaultFocusedTime = $defaultFocusedTime->setTimezone($this->getTimezone());
        }

        return $defaultFocusedTime;
    }

    /**
     * @return array<string>
     */
    public function getDisabledTimes(): array
    {
        return $this->evaluate($this->disabledTimes);
    }

    public function getTimezone(): string
    {
        return $this->evaluate($this->timezone) ?? FilamentTimezone::get();
    }

    public function getLocale(): string
    {
        $locale = $this->evaluate($this->locale) ?? 'en';
        if (! in_array($locale, ['en', 'np'])) {
            return 'en';
        }

        return $locale;
    }

    public function hasSeconds(): bool
    {
        return (bool) $this->evaluate($this->hasSeconds);
    }

    public function shouldCloseOnTimeSelection(): bool
    {
        return (bool) $this->evaluate($this->shouldCloseOnTimeSelection);
    }
}
