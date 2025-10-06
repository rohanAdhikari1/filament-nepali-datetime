<?php

declare(strict_types=1);

namespace RohanAdhikari\FilamentNepaliDatetime\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use InvalidArgumentException;

class NepaliDate extends DateConverter
{
    public int $bsYear;

    public int $bsMonth;

    public int $bsDay;

    public int $dayOfWeek = 1;

    protected string $locale = 'en';

    protected Carbon $adDate;

    public static function now(): self
    {
        return self::fromAd(Carbon::now());
    }

    public static function create(int $year, int $month, int $day, int $hour = 0, int $minute = 0, int $second = 0): self
    {
        $instance = new self;
        $instance->bsYear = $year;
        $instance->bsMonth = $month;
        $instance->bsDay = $day;
        $ad = $instance->convertBsToAd($year, $month, $day);
        $instance->adDate = Carbon::create($ad['year'], $ad['month'], $ad['day'], $hour, $minute, $second);

        return $instance;
    }

    public static function fromAd(Carbon | string $date): self
    {
        $instance = new self;
        if (! $date instanceof CarbonInterface) {
            $date = Carbon::parse($date);
        }
        $instance->adDate = $date;
        [$instance->bsYear, $instance->bsMonth, $instance->bsDay, $instance->dayOfWeek] = $instance->convertAdToBs($date->year, $date->month, $date->day);

        return $instance;
    }

    public static function parse(string $dateString, ?string $format = null, ?string $locale = null): self
    {
        $locale ??= preg_match('/[०-९]/u', $dateString) ? 'np' : 'en';
        if ($locale === 'np') {
            $dateString = self::toEnglishDigits($dateString);
        }

        // Supported tokens
        $tokenPatterns = [
            // Year
            'Y' => '(?P<Y>\d{4})',
            'y' => '(?P<y>\d{2})',

            // Month
            'm' => '(?P<m>\d{1,2})',
            'n' => '(?P<n>\d{1,2})',
            'F' => '(?P<F>[^,\s]+)',
            'M' => '(?P<M>[^,\s]+)',

            // Day
            'd' => '(?P<d>\d{1,2})',
            'j' => '(?P<j>\d{1,2})',

            // Weekday
            'l' => '(?P<l>[^,\s]+)',
            'D' => '(?P<D>[^,\s]+)',

            // Time
            'H' => '(?P<H>\d{2})',
            'i' => '(?P<i>\d{2})',
            's' => '(?P<s>\d{2})',
            'u' => '(?P<u>\d{1,6})', // microseconds

            // AM/PM
            'a' => '(?P<a>am|pm)',
            'A' => '(?P<A>AM|PM)',
        ];

        // Common patterns if no format explicitly given
        $commonFormats = $format
            ? [$format]
            : [
                'Y-m-dTH:i:s.uZ',   // ISO8601 with microseconds + Z
                'Y-m-dTH:i:sZ',     // ISO8601 with Z
                'Y-m-dTH:i:s',      // ISO8601 without Z
                'Y-m-d H:i:s',      // Standard datetime
                'Y-m-d',            // Date only
                'd-m-Y',            // European
                'm/d/Y',            // US
            ];

        foreach ($commonFormats as $fmt) {
            $regex = preg_replace_callback('/[A-Za-z]/', fn($m) => $tokenPatterns[$m[0]] ?? $m[0], $fmt);

            if (! preg_match('#^' . $regex . '$#u', $dateString, $matches)) {
                continue; // try next format
            }

            // Year
            $year = $matches['Y'] ?? (isset($matches['y']) ? (int) ('20' . $matches['y']) : null);

            // Month
            if (! empty($matches['F'])) {
                $month = $locale === 'np'
                    ? self::getBSMonthNumberFromNepali($matches['F'])
                    : self::getBSMonthNumberFromEnglish($matches['F']);
            } elseif (! empty($matches['M'])) {
                $month = $locale === 'np'
                    ? self::getBSMonthNumberFromNepali($matches['M'])
                    : self::getBSMonthNumberFromEnglish($matches['M']);
            } else {
                $month = $matches['m'] ?? $matches['n'] ?? null;
            }

            // Day
            $day = $matches['d'] ?? $matches['j'] ?? null;

            if (! $year || ! $month || ! $day) {
                continue;
            }

            // Time
            $hour = $matches['H'] ?? $matches['G'] ?? $matches['h'] ?? $matches['g'] ?? 0;
            $minute = $matches['i'] ?? 0;
            $second = $matches['s'] ?? 0;

            // AM/PM
            if (isset($matches['a']) || isset($matches['A'])) {
                $ampm = strtolower($matches['a'] ?? $matches['A']);
                $hour = (int) $hour;
                if ($ampm === 'pm' && $hour < 12) {
                    $hour += 12;
                }
                if ($ampm === 'am' && $hour == 12) {
                    $hour = 0;
                }
            }

            return self::create((int) $year, (int) $month, (int) $day, (int) $hour, (int) $minute, (int) $second)
                ->locale($locale);
        }

        throw new InvalidArgumentException("Failed to parse date string: {$dateString}");
    }

    public function year(): int
    {
        if ($this->locale == 'np') {
            return (int) self::toNepaliDigits($this->bsYear);
        }

        return $this->bsYear;
    }

    public function month(): int
    {
        if ($this->locale == 'np') {
            return (int) self::toNepaliDigits($this->bsMonth);
        }

        return $this->bsMonth;
    }

    public function day(): int
    {
        if ($this->locale == 'np') {
            return (int) self::toNepaliDigits($this->bsDay);
        }

        return $this->bsDay;
    }

    public function toAd(): Carbon
    {
        return $this->adDate;
    }

    public function toBsArray(): array
    {
        return [
            'year' => $this->year(),
            'month' => $this->month(),
            'day' => $this->day(),
        ];
    }

    public function format(string $format = 'Y-m-d'): string
    {
        $year = (string) $this->bsYear;
        $month = $this->bsMonth;
        $day = $this->bsDay;
        $dow = $this->dayOfWeek;

        $hour24 = $this->adDate->hour;
        $hour12 = $hour24 % 12 ?: 12;
        $minute = $this->adDate->minute;
        $second = $this->adDate->second;

        $H = str_pad((string) $hour24, 2, '0', STR_PAD_LEFT);
        $h = str_pad((string) $hour12, 2, '0', STR_PAD_LEFT);

        $replacements = [
            // Year
            'Y' => $this->locale === 'np' ? self::toNepaliDigits($year) : $year,
            'y' => $this->locale === 'np' ? self::toNepaliDigits(substr($year, -2)) : substr($year, -2),

            // Month
            'm' => $this->locale === 'np' ? self::toNepaliDigits(str_pad((string) $month, 2, '0', STR_PAD_LEFT)) : str_pad((string) $month, 2, '0', STR_PAD_LEFT),
            'n' => $this->locale === 'np' ? self::toNepaliDigits($month) : $month,
            'F' => $this->locale === 'np' ? self::getBSMonthInNepali($month) : self::getBSMonthInEnglish($month),
            'M' => $this->locale === 'np' ? self::getBSMonthShortInNepali($month) : self::getBSMonthShortInEnglish($month),

            // Day
            'd' => $this->locale === 'np' ? self::toNepaliDigits(str_pad((string) $day, 2, '0', STR_PAD_LEFT)) : str_pad((string) $day, 2, '0', STR_PAD_LEFT),
            'j' => $this->locale === 'np' ? self::toNepaliDigits($day) : $day,

            // Day of week
            'l' => $this->locale === 'np' ? self::getDayOfWeekInNepali($dow) : self::getDayOfWeekInEnglish($dow),
            'D' => $this->locale === 'np' ? self::getDayOfWeekShortInNepali($dow) : self::getDayOfWeekShortInEnglish($dow),

            'H' => $this->locale === 'np' ? self::toNepaliDigits($H) : $H,
            'G' => $this->locale === 'np' ? self::toNepaliDigits($hour24) : (string) $hour24,
            'h' => $this->locale === 'np' ? self::toNepaliDigits($h) : $h,
            'g' => $this->locale === 'np' ? self::toNepaliDigits($hour12) : (string) $hour12,
            'i' => $this->locale === 'np' ? self::toNepaliDigits($minute) : (string) $minute,
            's' => $this->locale === 'np' ? self::toNepaliDigits($second) : (string) $second,
            'a' => $this->locale === 'np'
                ? ($hour24 < 12 ? 'पूर्वाह्न' : 'अपराह्न')
                : ($hour24 < 12 ? 'am' : 'pm'),
            'A' => $this->locale === 'np'
                ? ($hour24 < 12 ? 'पूर्वाह्न' : 'अपराह्न')
                : ($hour24 < 12 ? 'AM' : 'PM'),
        ];

        return preg_replace_callback('/[A-Za-z]/u', fn($m) => $replacements[$m[0]] ?? $m[0], $format);
    }

    public function locale(string $locale): self
    {
        if ($locale != 'en' && $locale != 'np') {
            throw new InvalidArgumentException('Invalid locale');
        }
        $this->locale = $locale;

        return $this;
    }

    public function addDay(): self
    {
        $this->adDate->addDay();
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function subDay(): self
    {
        $this->adDate->subDay();
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function addDays(int $days): self
    {
        $this->adDate->addDays($days);
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function subDays(int $days): self
    {
        $this->adDate->subDays($days);
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function addMonth(): self
    {
        $this->adDate->addMonth();
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function subMonth(): self
    {
        $this->adDate->subMonth();
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function addMonths(int $months): self
    {
        $this->adDate->addMonths($months);
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function subMonths(int $months): self
    {
        $this->adDate->subMonths($months);
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function addyear(): self
    {
        $this->adDate->addYear();
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function subYear(): self
    {
        $this->adDate->subYear();
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function addyears(int $years): self
    {
        $this->adDate->addYears($years);
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }

    public function subYears(int $years): self
    {
        $this->adDate->subMonths($years);
        [$this->bsYear, $this->bsMonth, $this->bsDay, $this->dayOfWeek] = $this->convertADToBS($this->adDate->year, $this->adDate->month, $this->adDate->day);

        return $this;
    }
}
