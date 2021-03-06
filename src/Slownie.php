<?php

namespace SpecFile;

class Slownie
{
    private static $UNITIES = [
        'zero',
        'jeden',
        'dwa',
        'trzy',
        'cztery',
        'pięć',
        'sześć',
        'siedem',
        'osiem',
        'dziewięć',
    ];

    public static function printSpelledOut($amount)
    {
        if (!is_numeric($amount)) {
            throw new NotANumberException(sprintf('"%s" is not a number', $amount));
        }
        
        if ($amount >= 1e15) {
            throw new OutOfRangeException(sprintf('%d is too big to print as trillions', $amount));
        }

        return static::printHelper($amount, 0);
    }

    private static function printHelper($amount, $log_1000)
    {
        // Based on CIA The World Factbook, see
        // <https://www.cia.gov/LIBRARY/publications/the-world-factbook/rankorder/2215rank.html>,
        // we dont't need more units than to trillion (Poland uses long scale, so it's 'bilion' in Polish).
        // Due to lack of precission of float (and values bigger than PHP_INT_MAX are always floats) we're screwed up
        // anyway.
        $UNITS = [
            [
                'złoty',
                'złote',
                'złotych',
            ],
            [
                'tysiąc',
                'tysiące',
                'tysięcy',
            ],
            [
                'milion',
                'miliony',
                'milionów'
            ],
            [
                'miliard',
                'miliardy',
                'miliardów',
            ],
            [
                'bilion',
                'biliony',
                'bilionów',
            ],
        ];

        $answer = '';
        
        if ($amount < 0) {
            $answer = 'minus ';
            $amount = -$amount;
        }

        $todo = (int)floor($amount / 1000);
        $rest = $amount % 1000;
        if ($todo) {
            $answer .= static::printHelper($todo, $log_1000+1);
        }

        if ($todo && $rest) {
            $answer .= ' ';
        }

        if ($rest || !intval($amount)) {
            $answer .= static::printNumbersBelow1000($rest, $log_1000 == 0);
        }

        if (!($rest == 1 && $log_1000 != 0) && ($rest || $log_1000 == 0)) {
            $answer .= ' ';
        }

        if ($rest || $log_1000 == 0) {
            $answer .=  $UNITS[$log_1000][static::pluralCategory($amount)];
        }

        if ($log_1000 == 0) {
            $answer .= sprintf(' %02d/100', round(fmod($amount, 1) * 100));
        }

        return $answer;
    }

    private static function printNumbersBelow1000($amount, $printOne)
    {
        if ($amount == 1 && !$printOne) {
            return '';
        }

        if ($amount < 10) {
            return static::printUnities($amount);
        }

        if ($amount < 100) {
            return static::printNumbersBelow100($amount);
        }

        $HUNDREDS = [
            '',
            'sto',
            'dwieście',
            'trzysta',
            'czterysta',
        ];

        for ($i = 5; $i < 10; $i++) {
            array_push($HUNDREDS, static::$UNITIES[$i] . 'set');
        }

        $answer = $HUNDREDS[(int)floor($amount / 100)];
        $rest = $amount % 100;
        if ($rest) {
            $answer .= ' ' . static::printNumbersBelow100($rest);
        }

        return $answer;
    }

    private static function printNumbersBelow100($amount)
    {
        if ($amount < 10) {
            return static::printUnities($amount);
        }

        $TEENS = [
            'dziesięć',
            'jedenaście',
            'dwanaście',
            'trzynaście',
            'czternaście',
            'piętnaście',
            'szesnaście',
            'siedemnaście',
            'osiemnaście',
            'dziewiętnaście',
        ];

        if ($amount >= 10 && $amount < 20) {
            return $TEENS[$amount % 10];
        }

        $TYS = [
            '',
            '',
            'dwadzieścia',
            'trzydzieści',
            'czterdzieści',
        ];

        for ($i = 5; $i < 10; $i++) {
            array_push($TYS, static::$UNITIES[$i] . 'dziesiąt');
        }

        $answer = $TYS[(int)floor($amount / 10)];
        $rest = $amount % 10;
        if ($rest) {
            $answer .= ' ' . static::printUnities($rest);
        }

        return $answer;
    }

    private static function printUnities($amount)
    {
        return static::$UNITIES[$amount];
    }

    // See <https://www.unicode.org/cldr/charts/34/supplemental/language_plural_rules.html#pl>.
    const ONE = 0;
    const FEW = 1;
    const MANY = 2;

    private static function pluralCategory($amount)
    {
        if (intval($amount) == 1) {
            return static::ONE;
        }

        $unities = $amount % 10;
        $teens = $amount % 100;
        if (($unities >= 2 && $unities <= 4) && !($teens >= 12 && $teens <= 14)) {
            return static::FEW;
        }

        return static::MANY;
    }
}
