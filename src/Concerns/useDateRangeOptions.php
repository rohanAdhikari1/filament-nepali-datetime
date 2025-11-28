<?php

namespace RohanAdhikari\FilamentNepaliDateTime\Concerns;

use Carbon\CarbonInterface;
use Closure;
use RohanAdhikari\FilamentNepaliDateTime\Model\RangeSpan;
use RohanAdhikari\NepaliDate\NepaliDateInterface;

trait useDateRangeOptions
{
    protected CarbonInterface | NepaliDateInterface | string | Closure | null $startDate = null;

    protected CarbonInterface | NepaliDateInterface | string | Closure | null $endDate = null;

    protected bool | Closure $showDropDowns = false;

    protected bool | Closure $linkedCalendars = true;

    protected bool | Closure $alwaysShowCalendars = true;

    protected bool | Closure $showCustomRangeLabel = false;

    protected array | Closure $ranges = [];

    protected array | Closure $appendRanges = [];

    protected array | Closure $prependRanges = [];

    protected Closure | RangeSpan | null $maxSpan = null;

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

    public function showCustomRangeLabel(bool | Closure $condition = true): static
    {
        $this->showCustomRangeLabel = $condition;

        return $this;
    }

    public function maxSpan(Closure | RangeSpan | null $maxSpan): static
    {
        $this->maxSpan = $maxSpan;

        return $this;
    }

    public function ranges(array | Closure $ranges): static
    {
        $this->ranges = $ranges;

        return $this;
    }

    public function appendRanges(array | Closure $ranges): static
    {
        $this->appendRanges = $ranges;

        return $this;
    }

    public function prependRanges(array | Closure $ranges): static
    {
        $this->prependRanges = $ranges;

        return $this;
    }
}
