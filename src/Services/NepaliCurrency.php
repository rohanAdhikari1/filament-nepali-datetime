<?php

declare(strict_types=1);

namespace RohanAdhikari\FilamentNepaliDatetime\Services;

use InvalidArgumentException;

final class NepaliCurrency
{
    private static $wordsEn = [
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety',
    ];

    private static $wordsNp = [
        '',
        'एक',
        'दुई',
        'तीन',
        'चार',
        'पाँच',
        'छ',
        'सात',
        'आठ',
        'नौ',
        'दस',
        'एघार',
        'बाह्र',
        'तेह्र',
        'चौध',
        'पन्ध्र',
        'सोह्र',
        'सत्र',
        'अठाह्र',
        'उन्नाइस',
        'बीस',
        'एकाइस',
        'बाइस',
        'तेइस',
        'चौबीस',
        'पचीस',
        'छब्बीस',
        'सत्ताइस',
        'अठ्ठाइस',
        'उनन्तीस',
        'तीस',
        'एकतीस',
        'बतीस',
        'तेतीस',
        'चौतीस',
        'पैतीस',
        'छतीस',
        'सरतीस',
        'अरतीस',
        'उननचालीस',
        'चालीस',
        'एकचालीस',
        'बयालिस',
        'तीरचालीस',
        'चौवालिस',
        'पैंतालिस',
        'छयालिस',
        'सरचालीस',
        'अरचालीस',
        'उननचास',
        'पचास',
        'एकाउन्न',
        'बाउन्न',
        'त्रिपन्न',
        'चौवन्न',
        'पच्पन्न',
        'छपन्न',
        'सन्ताउन्न',
        'अन्ठाउँन्न',
        'उनान्न्साठी',
        'साठी',
        'एकसाठी',
        'बासाठी',
        'तीरसाठी',
        'चौंसाठी',
        'पैसाठी',
        'छैसठी',
        'सत्सठ्ठी',
        'अर्सठ्ठी',
        'उनन्सत्तरी',
        'सतरी',
        'एकहत्तर',
        'बहत्तर',
        'त्रिहत्तर',
        'चौहत्तर',
        'पचहत्तर',
        'छहत्तर',
        'सत्हत्तर',
        'अठ्हत्तर',
        'उनास्सी',
        'अस्सी',
        'एकासी',
        'बयासी',
        'त्रीयासी',
        'चौरासी',
        'पचासी',
        'छयासी',
        'सतासी',
        'अठासी',
        'उनान्नब्बे',
        'नब्बे',
        'एकान्नब्बे',
        'बयान्नब्बे',
        'त्रियान्नब्बे',
        'चौरान्नब्बे',
        'पंचान्नब्बे',
        'छयान्नब्बे',
        'सन्तान्‍नब्बे',
        'अन्ठान्नब्बे',
        'उनान्सय',
    ];

    public static function nepaliNumberFormat($num)
    {
        $num = $num;

        $parts = explode('.', $num);
        $int = $parts[0];
        $dec = isset($parts[1]) && intval($parts[1]) > 0 ? '.' . $parts[1] : '';

        $last3 = substr($int, -3);
        $rest = substr($int, 0, -3);

        if ($rest != '') {
            $last3 = ',' . $last3;
        }

        $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ',', $rest);

        return $rest . $last3 . $dec;
    }

    public static function getNepaliCurrency($amount, $format = true, $symbol = true, $only = false, $locale = 'np')
    {
        $formattedAmount = (string) $amount;
        $currencySymbol = false;
        if ($symbol) {
            $currencySymbol = is_string($symbol) ? $symbol : 'रू';
        }
        if ($format) {
            $formattedAmount = self::nepaliNumberFormat($formattedAmount, 2);
        }
        if ($locale === 'np') {
            $formattedAmount = DateConverter::toNepaliDigits($formattedAmount);
        }
        if ($currencySymbol) {
            $formattedAmount = $currencySymbol . ' ' . $formattedAmount;
        }
        if ($only) {
            $postfix = $locale === 'np' ? 'मात्र' : '/-';
            $formattedAmount = $formattedAmount . ' ' . $postfix;
        }

        return $formattedAmount;
    }

    public static function getNepaliWord(string $amount, bool $currency = true, $locale = 'np', bool $only = false)
    {
        $integer = $amount;
        $decimal = '';
        if (strpos($amount, '.') !== false) {
            [$integer, $dec] = explode('.', $amount, 2);
            $decimal = substr($dec, 0, 2);
        }
        $result = self::converter($locale)($integer, $currency);
        if ($currency && $result) {
            $result .= $locale == 'np' ? ' रुपैंया' : ' Rupees';
        }
        if ($decimal > 0) {
            if ($currency) {
                $join = ' and ';
                $end = ' Paisa';
                if ($locale == 'np') {
                    $join = ', ';
                    $end = ' पैसा';
                }
                $result .= $join . self::converter($locale)($decimal, $currency) . $end;
            } else {
                $result .= '.  ' . self::converter($locale)($decimal, $currency);
            }
        }
        if ($only) {
            $postfix = $locale === 'np' ? 'मात्र' : 'Only';
            $result .= ' ' . $postfix;
        }

        return $result;
    }

    public static function converter($locale = 'en'): callable
    {
        if ($locale === 'np') {
            return function ($num, $withCurrency = true) {
                return self::convertNepali($num, $withCurrency);
            };
        } else {
            return function ($num, $withCurrency = true) {
                return self::convertEnglish($num, $withCurrency);
            };
        }
    }

    private static function twoDigitToWords($num)
    {
        if ($num < 20) {
            return self::$wordsEn[$num];
        }
        if ($num < 100) {
            $tens = floor($num / 10) * 10;
            $ones = $num % 10;

            return self::$wordsEn[$tens] . ($ones ? ' ' . self::$wordsEn[$ones] : '');
        }

        return self::$wordsEn[$num];
    }

    private static function convertEnglish($n)
    {
        if ($n == '0') {
            return self::$wordsEn[0];
        }

        $units = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore', 'Arab', 'Kharab', 'Neel', 'Padma', 'Shankh'];
        $str = '';

        $parts[] = substr($n, -2);
        $n = substr($n, 0, -2);

        $parts[] = substr($n, -1);
        $n = substr($n, 0, -1);

        while (strlen($n) > 0) {
            $parts[] = substr($n, -2);
            $n = substr($n, 0, -2);
        }

        $parts = array_reverse($parts);
        $unitIndex = count($parts) - 1;
        if ($unitIndex > 9) {
            throw new InvalidArgumentException('Does not support numbers greater than Shankh');
        }

        foreach ($parts as $p) {
            $num = intval($p);
            if ($num > 0) {
                $str .= ' ' . self::twoDigitToWords($num) . ' ' . $units[$unitIndex];
            }
            $unitIndex--;
        }

        return trim(preg_replace('/\s+/', ' ', $str));
    }

    private static function convertNepali($n)
    {
        if ($n == '0') {
            return self::$wordsNp[0];
        }

        $units = ['', 'सय', 'हजार', 'लाख', 'करोड', 'अरब', 'खरब', 'नील', 'पद्म', 'शंख'];

        $str = '';

        $parts[] = substr($n, -2);
        $n = substr($n, 0, -2);

        $parts[] = substr($n, -1);
        $n = substr($n, 0, -1);

        while (strlen($n) > 0) {
            $parts[] = substr($n, -2);
            $n = substr($n, 0, -2);
        }

        $parts = array_reverse($parts);
        $unitIndex = count($parts) - 1;

        if ($unitIndex > 9) {
            throw new InvalidArgumentException('Does not support numbers greater than शंख');
        }

        foreach ($parts as $p) {
            $num = intval($p);
            if ($num > 0) {
                $str .= ' ' . self::$wordsNp[$num] . ' ' . $units[$unitIndex];
            }
            $unitIndex--;
        }

        return trim(preg_replace('/\s+/', ' ', $str));
    }
}
