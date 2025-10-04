<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RohanAdhikari\FilamentNepaliDatetime\Services\NepaliDate;

final class NepaliDateTest extends TestCase
{
    public function testAdConvert(): void
    {
        $date = NepaliDate::create(2082, 6, 12)->toAd();
        $this->assertEquals('2025-09-28', $date->format('Y-m-d'));
    }

    public function testNumericFormatEN(): void
    {
        $date = NepaliDate::create(2082, 6, 12)->locale('en');
        $this->assertEquals('2082-06-12', $date->format('Y-m-d'));
        $this->assertEquals('12-06-2082', $date->format('d-m-Y'));
    }

    public function testNumericFormatNP(): void
    {
        $date = NepaliDate::create(2082, 6, 12)->locale('np');
        $this->assertEquals('२०८२-०६-१२', $date->format('Y-m-d'));
        $this->assertEquals('१२-०६-२०८२', $date->format('d-m-Y'));
    }

    public function testNamedFormatEN(): void
    {
        $date = NepaliDate::create(2082, 6, 12)->locale('en');
        $this->assertEquals('Sunday, 12 Ashoj 2082', $date->format('l, j F Y'));
        $this->assertEquals('Sun, 12 Aso 2082', $date->format('D, j M Y'));
    }

    public function testNamedFormatNP(): void
    {
        $date = NepaliDate::create(2082, 6, 12)->locale('np');
        $this->assertEquals('आइतवार, १२ असोज २०८२', $date->format('l, j F Y'));
        $this->assertEquals('आइत, १२ असो २०८२', $date->format('D, j M Y'));
    }

    public function testParseNumericEN(): void
    {
        $date = NepaliDate::parse('2082-06-12', 'Y-m-d', 'en');
        $this->assertEquals(2082, $date->bsYear);
        $this->assertEquals(6, $date->bsMonth);
        $this->assertEquals(12, $date->bsDay);
    }

    public function testParseNumericNP(): void
    {
        $date = NepaliDate::parse('२०८२-०६-१२', 'Y-m-d', 'np');
        $this->assertEquals(2082, $date->bsYear);
        $this->assertEquals(6, $date->bsMonth);
        $this->assertEquals(12, $date->bsDay);
    }

    public function testParseNamedEN(): void
    {
        $date = NepaliDate::parse('Sunday, 12 Ashoj 2082', 'l, j F Y', 'en');
        $this->assertEquals(2082, $date->bsYear);
        $this->assertEquals(6, $date->bsMonth);
        $this->assertEquals(12, $date->bsDay);
    }

    public function testParseNamedNP(): void
    {
        $date = NepaliDate::parse('आइतवार, १२ असोज २०८२', 'l, j F Y', 'np');
        $this->assertEquals(2082, $date->bsYear);
        $this->assertEquals(6, $date->bsMonth);
        $this->assertEquals(12, $date->bsDay);
    }

    // public function testAddAndSubDay(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12);
    //     $date->addDay();
    //     $this->assertEquals('13', $date->day());
    //     $date->subDay();
    //     $this->assertEquals('12', $date->day());
    // }

    // public function testAddAndSubDays(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 29);
    //     $date->addDays(5);
    //     $this->assertEquals('3', $date->day());
    //     $this->assertEquals('7', $date->month());
    //     $date->subDays(35);
    //     $this->assertEquals('5', $date->month());
    //     $this->assertEquals('30', $date->day());
    // }

    // public function testAddAndSubMonth(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12);
    //     $date->addMonth();
    //     $this->assertEquals('7', $date->month());
    //     $date->subMonth();
    //     $this->assertEquals('6', $date->month());
    //     $this->assertEquals('12', $date->day());
    // }

    // public function testAddAndSubMonths(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12);
    //     $date->addMonths(7);
    //     $this->assertEquals('1', $date->month());
    //     $this->assertEquals('2083', $date->year());
    //     $date->subMonths(24);
    //     $this->assertEquals('1', $date->month());
    //     $this->assertEquals('2081', $date->year());
    // }

    // public function testAddAndSubYear(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12);
    //     $date->addYear();
    //     $this->assertEquals('2083', $date->year());
    //     $date->subYear();
    //     $this->assertEquals('2082', $date->year());
    // }

    // public function testAddAndSubYears(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12);
    //     $date->addYears(2);
    //     $this->assertEquals('2084', $date->year());
    //     $date->subYears(2);
    //     $this->assertEquals('2082', $date->year());
    // }

    // public function testAddAndSubDayNP(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12)->locale('np');
    //     $date->addDay();
    //     $this->assertEquals('१३', $date->day());
    //     $date->subDay();
    //     $this->assertEquals('१२', $date->day());
    // }

    // public function testAddAndSubDaysNP(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 29)->locale('np');
    //     $date->addDays(5);
    //     $this->assertEquals('३', $date->day());
    //     $this->assertEquals('७', $date->month());
    //     $date->subDays(35);
    //     $this->assertEquals('३०', $date->day());
    //     $this->assertEquals('५', $date->month());
    // }

    // public function testAddAndSubMonthNP(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12)->locale('np');
    //     $date->addMonth();
    //     $this->assertEquals('७', $date->month());
    //     $date->subMonth();
    //     $this->assertEquals('६', $date->month());
    //     $this->assertEquals('१२', $date->day());
    // }

    // public function testAddAndSubMonthsNP(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12)->locale('np');
    //     $date->addMonths(7);
    //     $this->assertEquals('१', $date->month());
    //     $this->assertEquals('२०८३', $date->year());
    //     $date->subMonths(24);
    //     $this->assertEquals('१', $date->month());
    //     $this->assertEquals('२०८१', $date->year());
    // }

    // public function testAddAndSubYearNP(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12)->locale('np');
    //     $date->addYear();
    //     $this->assertEquals('२०८३', $date->year());
    //     $date->subYear();
    //     $this->assertEquals('२०८२', $date->year());
    // }

    // public function testAddAndSubYearsNP(): void
    // {
    //     $date = NepaliDate::create(2082, 6, 12)->locale('np');
    //     $date->addYears(2);
    //     $this->assertEquals('२०८४', $date->year());
    //     $date->subYears(2);
    //     $this->assertEquals('२०८२', $date->year());
    // }


    public function testFromAdWithCarbonInstance(): void
    {
        $carbonDate = \Carbon\Carbon::create(2025, 9, 28);
        $date = NepaliDate::fromAd($carbonDate);
        $this->assertInstanceOf(NepaliDate::class, $date);
        $this->assertEquals(2082, $date->year());
        $this->assertEquals(6, $date->month());
        $this->assertEquals(12, $date->day());
    }

    public function testFromAdWithString(): void
    {
        $date = NepaliDate::fromAd('2025-09-28');
        $this->assertInstanceOf(NepaliDate::class, $date);
        $this->assertInstanceOf(\Carbon\Carbon::class, $date->toAd());
        $this->assertEquals(2025, $date->toAd()->year);
        $this->assertEquals(9, $date->toAd()->month);
        $this->assertEquals(28, $date->toAd()->day);
    }
}
