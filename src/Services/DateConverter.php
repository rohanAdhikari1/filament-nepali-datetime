<?php

declare(strict_types=1);

namespace RohanAdhikari\FilamentNepaliDatetime\Services;

use InvalidArgumentException;

abstract class DateConverter
{
    protected $calendarData = [
        0 => [2000, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        1 => [2001, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2 => [2002, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        3 => [2003, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        4 => [2004, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        5 => [2005, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        6 => [2006, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        7 => [2007, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        8 => [2008, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
        9 => [2009, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        10 => [2010, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        11 => [2011, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        12 => [2012, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        13 => [2013, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        14 => [2014, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        15 => [2015, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        16 => [2016, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        17 => [2017, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        18 => [2018, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        19 => [2019, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        20 => [2020, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        21 => [2021, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        22 => [2022, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        23 => [2023, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        24 => [2024, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        25 => [2025, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        26 => [2026, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        27 => [2027, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        28 => [2028, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        29 => [2029, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
        30 => [2030, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        31 => [2031, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        32 => [2032, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        33 => [2033, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        34 => [2034, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        35 => [2035, 30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
        36 => [2036, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        37 => [2037, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        38 => [2038, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        39 => [2039, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        40 => [2040, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        41 => [2041, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        42 => [2042, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        43 => [2043, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        44 => [2044, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        45 => [2045, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        46 => [2046, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        47 => [2047, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        48 => [2048, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        49 => [2049, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        50 => [2050, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        51 => [2051, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        52 => [2052, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        53 => [2053, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        54 => [2054, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        55 => [2055, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        56 => [2056, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
        57 => [2057, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        58 => [2058, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        59 => [2059, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        60 => [2060, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        61 => [2061, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        62 => [2062, 30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
        63 => [2063, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        64 => [2064, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        65 => [2065, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        66 => [2066, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
        67 => [2067, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        68 => [2068, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        69 => [2069, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        70 => [2070, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        71 => [2071, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        72 => [2072, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        73 => [2073, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        74 => [2074, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        75 => [2075, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        76 => [2076, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        77 => [2077, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        78 => [2078, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        79 => [2079, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        80 => [2080, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        81 => [2081, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        82 => [2082, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        83 => [2083, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        84 => [2084, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30],
        85 => [2085, 31, 32, 31, 32, 30, 31, 30, 30, 29, 30, 30, 30],
        86 => [2086, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        87 => [2087, 31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30],
        88 => [2088, 30, 31, 32, 32, 30, 31, 30, 30, 29, 30, 30, 30],
        89 => [2089, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        90 => [2090, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        91 => [2091, 31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30],
        92 => [2092, 30, 31, 32, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        93 => [2093, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30],
        94 => [2094, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30],
        95 => [2095, 31, 31, 32, 31, 31, 31, 30, 29, 30, 30, 30, 30],
        96 => [2096, 30, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        97 => [2097, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        98 => [2098, 31, 31, 32, 31, 31, 31, 29, 30, 29, 30, 29, 31],
        99 => [2099, 31, 31, 32, 31, 31, 31, 30, 29, 29, 30, 30, 30],
    ];

    protected static array $bsMonthInEnglish = [
        1 => 'Baisakh',
        2 => 'Jestha',
        3 => 'Ashar',
        4 => 'Shrawan',
        5 => 'Bhadra',
        6 => 'Ashoj',
        7 => 'Kartik',
        8 => 'Mangsir',
        9 => 'Poush',
        10 => 'Magh',
        11 => 'Falgun',
        12 => 'Chaitra',
    ];

    protected static array $dayOfWeekInEnglish = [
        1 => 'Sunday',
        2 => 'Monday',
        3 => 'Tuesday',
        4 => 'Wednesday',
        5 => 'Thursday',
        6 => 'Friday',
        7 => 'Saturday',
    ];

    protected static array $bsMonthShortInEnglish = [
        1 => 'Bai',
        2 => 'Jes',
        3 => 'Ash',
        4 => 'Shr',
        5 => 'Bha',
        6 => 'Aso',
        7 => 'Kar',
        8 => 'Man',
        9 => 'Pou',
        10 => 'Mag',
        11 => 'Fal',
        12 => 'Cha',
    ];

    protected static array $bsMonthShortInNepali = [
        1 => 'बै',
        2 => 'जे',
        3 => 'अस',
        4 => 'सा',
        5 => 'भ',
        6 => 'असो',
        7 => 'का',
        8 => 'मं',
        9 => 'पु',
        10 => 'मा',
        11 => 'फा',
        12 => 'चै',
    ];

    protected static array $bsMonthInNepali = [
        1 => 'वैशाख',
        2 => 'जेठ',
        3 => 'असार',
        4 => 'साउन',
        5 => 'भदौ',
        6 => 'असोज',
        7 => 'कार्तिक',
        8 => 'मंसिर',
        9 => 'पुष',
        10 => 'माघ',
        11 => 'फागुन',
        12 => 'चैत',
    ];

    protected static array $dayOfWeekInNepali = [
        1 => 'आइतवार',
        2 => 'सोमवार',
        3 => 'मङ्गलवार',
        4 => 'बुधवार',
        5 => 'बिहिवार',
        6 => 'शुक्रवार',
        7 => 'शनिवार',
    ];

    protected static array $dayOfWeekShortInEnglish = [
        1 => 'Sun',
        2 => 'Mon',
        3 => 'Tue',
        4 => 'Wed',
        5 => 'Thu',
        6 => 'Fri',
        7 => 'Sat',
    ];

    protected static array $dayOfWeekShortInNepali = [
        1 => 'आइत',
        2 => 'सोम',
        3 => 'मङ्गल',
        4 => 'बुध',
        5 => 'बिहि',
        6 => 'शुक्र',
        7 => 'शनि',
    ];

    protected static array $numbersInNepali = [
        0 => '०',
        1 => '१',
        2 => '२',
        3 => '३',
        4 => '४',
        5 => '५',
        6 => '६',
        7 => '७',
        8 => '८',
        9 => '९',
    ];

    protected const NORMAL_MONTHS = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    protected const LEAP_MONTHS = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    /* ------------------------
       Lookup Helpers
    ------------------------- */

    public static function getBSMonthShortInEnglish(int $month): string
    {
        return self::$bsMonthShortInEnglish[$month] ?? throw new InvalidArgumentException("Invalid BS month: $month");
    }

    public static function getBSMonthShortInNepali(int $month): string
    {
        return self::$bsMonthShortInNepali[$month] ?? throw new InvalidArgumentException("Invalid BS month: $month");
    }

    public static function getBSMonthNumberFromEnglish(string $name): int
    {
        $month = array_search($name, self::$bsMonthInEnglish, true)
            ?: array_search($name, self::$bsMonthShortInEnglish, true);

        if ($month === false) {
            throw new InvalidArgumentException("Invalid BS month name: $name");
        }

        return (int) $month;
    }

    public static function getBSMonthNumberFromNepali(string $name): int
    {
        $month = array_search($name, self::$bsMonthInNepali, true)
            ?: array_search($name, self::$bsMonthShortInNepali, true);

        if ($month === false) {
            throw new InvalidArgumentException("Invalid BS month name: $name");
        }

        return (int) $month;
    }

    public static function getBSMonthInEnglish($month): string
    {
        return self::$bsMonthInEnglish[$month] ?? throw new InvalidArgumentException("Invalid BS month: $month");
    }

    public static function getBSMonthInNepali($month): string
    {
        return self::$bsMonthInNepali[$month] ?? throw new InvalidArgumentException("Invalid BS month: $month");
    }

    public static function getDayOfWeekInEnglish(int $day): string
    {
        return self::$dayOfWeekInEnglish[$day] ?? throw new InvalidArgumentException("Invalid day of week: $day");
    }

    public static function getDayOfWeekInNepali(int $day): string
    {
        return self::$dayOfWeekInNepali[$day] ?? throw new InvalidArgumentException("Invalid day of week: $day");
    }

    public static function getDayOfWeekShortInEnglish(int $day): string
    {
        return self::$dayOfWeekShortInEnglish[$day] ?? throw new InvalidArgumentException("Invalid day of week: $day");
    }

    public static function getDayOfWeekShortInNepali(int $day): string
    {
        return self::$dayOfWeekShortInNepali[$day] ?? throw new InvalidArgumentException("Invalid day of week: $day");
    }

    public static function getDayOfWeekNumberFromEnglish(string $name): int
    {
        $day = array_search($name, self::$dayOfWeekInEnglish, true)
            ?: array_search($name, self::$dayOfWeekShortInEnglish, true);

        if ($day === false) {
            throw new InvalidArgumentException("Invalid day name: $name");
        }

        return (int) $day;
    }

    public static function getDayOfWeekNumberFromNepali(string $name): int
    {
        $day = array_search($name, self::$dayOfWeekInNepali, true)
            ?: array_search($name, self::$dayOfWeekShortInNepali, true);

        if ($day === false) {
            throw new InvalidArgumentException("Invalid day name: $name");
        }

        return (int) $day;
    }

    public static function getNumberInNepali(int $number): string
    {
        return self::$numbersInNepali[$number] ?? throw new InvalidArgumentException("Invalid number: $number");
    }

    public static function toNepaliDigits(int | string $value): string
    {
        $valueStr = (string) $value;

        return str_replace(range(0, 9), self::$numbersInNepali, $valueStr);
    }

    public static function toEnglishDigits(int | string $value): string
    {
        $valueStr = (string) $value;

        return str_replace(self::$numbersInNepali, range(0, 9), $valueStr);
    }

    /* ------------------------
       Validation
    ------------------------- */

    protected function validateEnglishDate(int $yy, int $mm, int $dd): void
    {
        if ($yy < 1944 || $yy > 2033) {
            throw new InvalidArgumentException('Supported AD years are only between 1944-2033.');
        }

        if ($mm < 1 || $mm > 12) {
            throw new InvalidArgumentException('Month must be between 1-12.');
        }

        $maxDays = $this->isLeapYear($yy) ? self::LEAP_MONTHS[$mm - 1] : self::NORMAL_MONTHS[$mm - 1];
        if ($dd < 1 || $dd > $maxDays) {
            throw new InvalidArgumentException("Invalid day $dd for month $mm in year $yy.");
        }
    }

    protected function validateNepaliDate(int $yy, int $mm, int $dd): void
    {
        if ($yy < 2000 || $yy > 2089) {
            throw new InvalidArgumentException('Supported BS years are only between 2000-2089.');
        }

        if ($mm < 1 || $mm > 12) {
            throw new InvalidArgumentException('Month must be between 1-12.');
        }

        $yearIndex = $yy - 2000;
        $maxDays = $this->calendarData[$yearIndex][$mm] ?? 0;

        if ($dd < 1 || $dd > $maxDays) {
            throw new InvalidArgumentException("Invalid day $dd for BS month $mm in year $yy.");
        }
    }

    /* ------------------------
       Utilities
    ------------------------- */

    protected function isLeapYear(int $year): bool
    {
        return ($year % 400 === 0) || ($year % 4 === 0 && $year % 100 !== 0);
    }

    protected function calculateTotalEnglishDays(int $year, int $month, int $day): int
    {
        $totalDays = 0;
        $initialYear = 1944;

        for ($i = $initialYear; $i < $year; $i++) {
            $months = $this->isLeapYear($i) ? self::LEAP_MONTHS : self::NORMAL_MONTHS;
            $totalDays += array_sum($months);
        }

        $months = $this->isLeapYear($year) ? self::LEAP_MONTHS : self::NORMAL_MONTHS;
        $totalDays += array_sum(array_slice($months, 0, $month - 1));

        return $totalDays + $day;
    }

    /**
     * Calculate total days from a reference Nepali date to the specified Nepali date.
     *
     * @param  int  $year  Nepali year
     * @param  int  $month  Nepali month (1-12)
     * @param  int  $day  Nepali day
     * @return int Total number of days
     */
    protected function calculateTotalNepaliDays($year, $month, $day): int
    {
        $totalDays = 0;
        $initialYear = 2000;

        // Complete years
        for ($i = $initialYear; $i < $year; $i++) {
            $yearIndex = $i - $initialYear;
            $totalDays += array_sum(array_slice($this->calendarData[$yearIndex], 1));
        }

        // Complete months in current year
        $yearIndex = $year - $initialYear;
        $totalDays += array_sum(array_slice($this->calendarData[$yearIndex], 1, $month - 1));

        return $totalDays + $day;
    }

    /* ------------------------
       Conversion Methods
    ------------------------- */

    /**
     * Convert an AD (Gregorian) date to BS (Bikram Sambat).
     *
     * @param  int  $yy  AD year (1944–2033 supported)
     * @param  int  $mm  AD month (1–12)
     * @param  int  $dd  AD day
     * @return array{year:int, month:int, day:int, dayOfWeek:int}
     */
    public function convertADToBS(int $yy, int $mm, int $dd): array
    {
        $this->validateEnglishDate($yy, $mm, $dd);

        $totalAdDays = $this->calculateTotalEnglishDays($yy, $mm, $dd);

        $bsYear = 2000;
        $bsMonth = 1;
        $bsDay = 1;
        $dayOfWeek = 4;

        $i = 0;
        $j = $bsMonth;

        while ($totalAdDays > 0) {
            $daysInMonth = $this->calendarData[$i][$j];

            $bsDay++;
            $dayOfWeek++;

            if ($dayOfWeek > 7) {
                $dayOfWeek = 1;
            }

            if ($bsDay > $daysInMonth) {
                $bsMonth++;
                $bsDay = 1;
                $j++;
            }

            if ($bsMonth > 12) {
                $bsYear++;
                $bsMonth = 1;
            }

            if ($j > 12) {
                $j = 1;
                $i++;
            }

            $totalAdDays--;
        }

        return [$bsYear, $bsMonth, $bsDay, $dayOfWeek];
    }

    /**
     * Convert a BS (Bikram Sambat) date to AD (Gregorian).
     *
     * @param  int  $yy  BS year (2000–2089 supported)
     * @param  int  $mm  BS month (1–12)
     * @param  int  $dd  BS day
     * @return array{year:int, month:int, day:int, dayOfWeek:int}
     */
    public function convertBSToAD(int $yy, int $mm, int $dd): array
    {
        $this->validateNepaliDate($yy, $mm, $dd);
        $totalBsDays = $this->calculateTotalNepaliDays($yy, $mm, $dd);

        $adYear = 1943;
        $adMonth = 4;
        $adDay = 13;

        while ($totalBsDays > 0) {
            $adDay++;
            $daysInMonth = $this->isLeapYear($adYear)
                ? self::LEAP_MONTHS[$adMonth - 1]
                : self::NORMAL_MONTHS[$adMonth - 1];

            if ($adDay > $daysInMonth) {
                $adMonth++;
                $adDay = 1;
            }

            if ($adMonth > 12) {
                $adYear++;
                $adMonth = 1;
            }
            $totalBsDays--;
        }

        return [
            'year' => $adYear,
            'month' => $adMonth,
            'day' => $adDay,
        ];
    }
}
