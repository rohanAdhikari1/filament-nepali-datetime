<?php

namespace RohanAdhikari\FilamentNepaliDateTime\Concerns;

use Carbon\CarbonInterface;
use Closure;
use RohanAdhikari\FilamentNepaliDateTime\Model\RangeSpan;
use RohanAdhikari\NepaliDate\Exceptions\NepaliDateFormatException;
use RohanAdhikari\NepaliDate\NepaliDate;
use RohanAdhikari\NepaliDate\NepaliDateInterface;

trait useDateRangeOptions
{
    protected CarbonInterface | NepaliDateInterface | string | Closure | null $startDate = null;

    protected CarbonInterface | NepaliDateInterface | string | Closure | null $endDate = null;

    protected bool | Closure $showDropDowns = false;

    protected bool | Closure $linkedCalendars = true;

    protected bool | Closure $alwaysShowCalendars = true;

    protected bool | Closure $showCustomRangeLabel = false;

    protected bool | Closure $showRangeLabels = true;

    protected array | Closure $ranges = [];

    protected array | Closure $appendRanges = [];

    protected array | Closure $prependRanges = [];

    protected RangeSpan | null $maxSpan = null;

    public function startDate(CarbonInterface | NepaliDateInterface | string | Closure $date): static
    {
        $this->startDate = $date;

        return $this;
    }

    public function endDate(CarbonInterface | NepaliDateInterface | string | Closure $date): static
    {
        $this->endDate = $date;

        return $this;
    }

    public function showDropDowns(bool | Closure $condition = true): static
    {
        $this->showDropDowns = $condition;

        return $this;
    }

    public function linkedCalendars(bool | Closure $condition = true): static
    {
        $this->linkedCalendars = $condition;

        return $this;
    }

    public function alwaysShowCalendars(bool | Closure $condition = true): static
    {
        $this->alwaysShowCalendars = $condition;

        return $this;
    }

    public function showRangeLabels(bool | Closure $condition = true): static
    {
        $this->showRangeLabels = $condition;

        return $this;
    }

    public function showCustomRangeLabel(bool | Closure $condition = true): static
    {
        $this->showCustomRangeLabel = $condition;

        return $this;
    }

    public function maxSpan(RangeSpan | null $maxSpan): static
    {
        $this->maxSpan = $maxSpan;

        return $this;
    }

    public function ranges(array | Closure $ranges, Closure | bool $merge = false): static
    {
        if ($merge) {
            $this->ranges[] = $ranges;
        } else {
            $this->ranges = $ranges;
        }

        return $this;
    }

    public function appendRanges(array | Closure $ranges, Closure | bool $merge = false): static
    {
        if ($merge) {
            $this->appendRanges[] = $ranges;
        } else {
            $this->appendRanges = $ranges;
        }

        return $this;
    }

    public function prependRanges(array | Closure $ranges, Closure | bool $merge = false): static
    {
        if ($merge) {
            $this->prependRanges[] = $ranges;
        } else {
            $this->prependRanges = $ranges;
        }

        return $this;
    }

    public function evaluateNepaliDate(string | NepaliDateInterface | CarbonInterface | Closure | null $date): NepaliDateInterface | null
    {
        if (blank($date)) {
            return null;
        }
        $date = $this->evaluate($date);
        if (is_string($date)) {
            try {
                return NepaliDate::parse($date);
            } catch (NepaliDateFormatException) {
                return null;
            }
        }
        if ($date instanceof CarbonInterface) {
            return NepaliDate::fromAd($date->toDateTime());
        }
        if (!$date instanceof NepaliDateInterface) {
            return null;
        }
        return $date;
    }

    //getters

    public function getShowRangeLabels(): bool
    {
        return $this->evaluate($this->showRangeLabels);
    }

    public function getStartDate(): NepaliDateInterface | null
    {
        return $this->evaluateNepaliDate($this->startDate);
    }

    public function getEndDate(): NepaliDateInterface | null
    {
        return $this->evaluateNepaliDate($this->endDate);
    }

    public function getShowDropDowns(): bool
    {
        return $this->evaluate($this->showDropDowns);
    }

    public function getIsLinkedCalendars(): bool
    {
        return $this->evaluate($this->linkedCalendars);
    }

    public function getIsAlwaysShowCalendars(): bool
    {
        return $this->evaluate($this->alwaysShowCalendars);
    }

    public function getShowCustomRangeLabel(): bool
    {
        return $this->evaluate($this->showCustomRangeLabel);
    }

    public function getMaxSpan(): array | null
    {
        return $this->maxSpan?->toArray();
    }

    public function getRanges(): array
    {
        $prependRanges = $this->evaluate($this->prependRanges);
        $ranges = $this->evaluate($this->ranges);
        $appendRanges = $this->evaluate($this->appendRanges);
        return [...$prependRanges, ...$ranges, ...$appendRanges];
    }
}
