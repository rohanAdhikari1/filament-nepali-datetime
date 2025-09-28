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
}
