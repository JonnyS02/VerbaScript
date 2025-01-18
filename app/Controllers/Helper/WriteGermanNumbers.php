<?php

namespace App\Controllers\Helper;

use JetBrains\PhpStorm\NoReturn;

class WriteGermanNumbers
{
    const NUMERAL_SIGN = 'minus';
    const NUMERAL_HUNDREDS_SUFFIX = 'hundert';
    const NUMERAL_INFIX = 'und';

    private $lNumeral = array('null', 'eins', 'zwei', 'drei', 'vier',
        'fünf', 'sechs', 'sieben', 'acht', 'neun',
        'zehn', 'elf', 'zwölf', 'dreizehn', 'vierzehn',
        'fünfzehn', 'sechzehn', 'siebzehn', 'achtzehn', 'neunzehn');

    private $lTenner = array('', '', 'zwanzig', 'dreißig', 'vierzig',
        'fünfzig', 'sechzig', 'siebzig', 'achtzig', 'neunzig');

    private $lGroupSuffix = array(
        array('', ''),
        array('tausend ', 'tausend '),
        array('e Million ', ' Millionen '),
        array('e Milliarde ', ' Milliarden '),
        array('e Billion ', ' Billionen '),
    );

    /**
     * Wandelt die übergebene Zahl in Text um.
     * Wenn die Zahl Nachkommastellen hat, wird sie als Bruch im Format 100er ausgegeben.
     *
     * @param float $number Die zu konvertierende Zahl
     * @return string Das ausgeschriebene Zahlwort
     */
    public function convert(float $number): string
    {
        // Trenne die Ganzzahl und den Dezimalteil der Zahl
        $wholeNumber = (int)$number; // Ganzzahl
        $decimalPart = $number - $wholeNumber; // Dezimalteil

        // Verarbeite die Ganzzahl
        $text = $this->convertWholeNumber($wholeNumber);

        // Verarbeite den Dezimalteil als Bruch
        if ($decimalPart != 0) {
            $text .= ' ' . $this->convertDecimalPartToFraction($decimalPart);
        }

        return $text;
    }

    /**
     * Wandelt die Ganzzahl um.
     *
     * @param int $number Die Ganzzahl
     * @return string Das Zahlwort der Ganzzahl
     */
    private function convertWholeNumber(int $number): string
    {
        if ($number == 0) {
            return $this->lNumeral[0]; // null
        }

        if ($number < 0) {
            return self::NUMERAL_SIGN . ' ' . $this->convertPositiveWholeNumber(abs($number));
        }

        return $this->convertPositiveWholeNumber($number);
    }

    /**
     * Wandelt eine positive Ganzzahl in ein Zahlwort um.
     *
     * @param int $number Die positive Ganzzahl
     * @param int $groupLevel Das Gruppenlevel für Tausender, Millionen etc.
     * @return string Das Zahlwort
     */
    private function convertPositiveWholeNumber(int $number, int $groupLevel = 0): string
    {
        if ($number == 0) {
            return '';
        }

        $groupNumber = $number % 1000;
        $result = '';

        if ($groupNumber > 0) {
            // Hunderter
            $hundreds = floor($groupNumber / 100);
            if ($hundreds > 0) {
                // Bei "einshundert" nur "hundert" schreiben
                if ($hundreds == 1) {
                    $result .= 'einhundert';
                } else {
                    $result .= $this->lNumeral[$hundreds] . self::NUMERAL_HUNDREDS_SUFFIX;
                }
            }

            // Zehner und Einer
            $lastDigits = $groupNumber % 100;
            $tens = floor($lastDigits / 10);
            $ones = $lastDigits % 10;

            if ($lastDigits < 20) {
                if ($lastDigits == 1 && $groupLevel > 0) {
                    // Singular für größere Gruppen wie Tausend oder Million
                    $result .= 'eine';
                } else if ($ones > 0) {
                    $result .= $this->lNumeral[$lastDigits];
                }
            } else {
                if ($ones > 0) {
                    $result .= $this->lNumeral[$ones] . self::NUMERAL_INFIX;
                }
                $result .= $this->lTenner[$tens];
            }

            // Gruppen-Suffixe für Tausender, Millionen, etc.
            if (isset($this->lGroupSuffix[$groupLevel])) {
                $result .= $this->lGroupSuffix[$groupLevel][1];
            }
        }

        return $this->convertPositiveWholeNumber(floor($number / 1000), $groupLevel + 1) . $result;
    }

    /**
     * Konvertiert den Dezimalteil in einen Bruch.
     *
     * @param float $decimalPart Der Dezimalteil der Zahl
     * @return string Der Dezimalteil als Bruch im Format Zähler/100
     */
    private function convertDecimalPartToFraction(float $decimalPart): string
    {
        $decimalPart = abs($decimalPart);

        // Runde auf zwei Dezimalstellen
        $fraction = round($decimalPart * 100);

        return "$fraction/100";
    }

    #[NoReturn] function test(): void
    {
        $converter = new WriteGermanNumbers();
        echo $converter->convert(5) . '<br>';
        echo $converter->convert(5.75) . '<br>';
        echo $converter->convert(123) . '<br>';
        echo $converter->convert(123.45) . '<br>';
        echo $converter->convert(1999) . '<br>';
        echo $converter->convert(1000000) . '<br>';
        echo $converter->convert(2500000.99) . '<br>';
        echo $converter->convert(-5000) . '<br>';
        die();
    }
}