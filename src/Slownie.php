<?php

namespace SpecFile;

class Slownie
{
    private const UNITIES = [
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

    public function print($amount)
    {
        return static::printHelper($amount, 0);
    }

    private static function printHelper($amount, $log_1000)
    {
        // Based on CIA The World Factbook, see <https://www.cia.gov/LIBRARY/publications/the-world-factbook/rankorder/2215rank.html>,
        // we dont't need more units than to trillion (Poland uses long scale, so it's 'bilion' in Polish).
        // Due to lack of precission of float (and values bigger than PHP_INT_MAX are always floats) we're screwed up anyway.
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

        $todo = intdiv($amount, 1000);
        $rest = $amount % 1000;
        if ($todo) {
            $answer .= static::printHelper($todo, $log_1000+1);
        }

        if ($todo && $rest) {
      	    $answer .= ' ';
        }

        if ($rest || !intval($amount)) {
            $answer .= static::printHundreds($rest, $log_1000 == 0);
        }

        if (!($rest == 1 && $log_1000 != 0) && ($rest || $log_1000 == 0)) {
            $answer .= ' ';
        }

        if ($rest || $log_1000 == 0) {
            $answer .=  $UNITS[$log_1000][static::pluralCategory($amount)];
        }

        if ($log_1000 == 0) {
            $answer .= sprintf(' %02d/100', fmod($amount, 1) * 100);
        }

        return $answer;
    }

    private static function printHundreds($amount, $printOne)
    {
        if ($amount == 1 && !$printOne) {
            return '';
        }
        
        if ($amount < 10) {
            return static::printUnities($amount);
        }
        
        if ($amount < 100) {
            return static::printTysAndTeens($amount);
        }

        $HUNDREDS = [
            '',
            'sto',
            'dwieście',
            'trzysta',
            'czterysta',
        ];

        for ($i = 5; $i < 10; $i++) {
            array_push($HUNDREDS, static::UNITIES[$i] . 'set');
        }
        
        $answer = $HUNDREDS[intdiv($amount, 100)];
        $rest = $amount % 100;
        if ($rest) {
            $answer .= ' ' . static::printTysAndTeens($rest);
        }

        return $answer;
    }

    private static function printTysAndTeens($amount)
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
            array_push($TYS, static::UNITIES[$i] . 'dziesiąt');
        }

        $answer = $TYS[intdiv($amount, 10)];
        $rest = $amount % 10;
        if ($rest) {
            $answer .= ' ' . static::printUnities($rest);
        }

        return $answer;
    }

    private static function printUnities($amount)
    {
        return static::UNITIES[$amount];
    }

    // See <https://www.unicode.org/cldr/charts/34/supplemental/language_plural_rules.html#pl>.
    const ONE = 0;
    const FEW = 1;
    const MANY = 2;

    private static function pluralCategory($amount)
    {
        if ($amount == 1) {
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
