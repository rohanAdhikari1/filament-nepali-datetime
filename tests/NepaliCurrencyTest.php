<?php

namespace RohanAdhikari\FilamentNepaliDatetime\Tests;

use RohanAdhikari\FilamentNepaliDatetime\Services\NepaliCurrency;

class NepaliCurrencyTest extends TestCase
{
    public function testCurrencyConvert(): void
    {
        $money = NepaliCurrency::getNepaliCurrency('1890567567568576568.14', true, false, false);
        $this->assertEquals('१८,९०,५६,७५,६७,५६,८५,७६,५६८.१४', $money);
    }

    public function testNumberToWordEn(): void
    {
        $word1 = NepaliCurrency::getNepaliWord('123456000.12', locale: 'en');
        $this->assertEquals('Twelve Crore Thirty Four Lakh Fifty Six Thousand Rupees and Twelve Paisa', $word1);

        $word2 = NepaliCurrency::getNepaliWord('1890567567568576568.14', locale: 'en');
        $this->assertEquals('Eighteen Shankh Ninety Padma Fifty Six Neel Seventy Five Kharab Sixty Seven Arab Fifty Six Crore Eighty Five Lakh Seventy Six Thousand Five Hundred Sixty Eight Rupees and Fourteen Paisa', $word2);
    }


    public function testNumberToWordNp(): void
    {
        $word1 = NepaliCurrency::getNepaliWord('123456000.12');
        $this->assertEquals('बाह्र करोड चौतीस लाख छपन्न हजार रुपैंया, बाह्र पैसा', $word1);

        $word2 = NepaliCurrency::getNepaliWord('1890567567568576568.14');
        $this->assertEquals('अठाह्र शंख नब्बे पद्म छपन्न नील पचहत्तर खरब सत्सठ्ठी अरब छपन्न करोड पचासी लाख छहत्तर हजार पाँच सय अर्सठ्ठी रुपैंया, चौध पैसा', $word2);
    }
}
