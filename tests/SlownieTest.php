<?php

namespace SpecFile;

use PHPUnit\Framework\TestCase;

class SlownieTest extends TestCase
{
    /**
     * @dataProvider validInputsProvider
     */
    public function testValidInputs($amount, $expected)
    {
        $this->assertSame($expected, Slownie::printSpelledOut($amount));
    }

    public function validInputsProvider()
    {
        return [
            [0, 'zero złotych 00/100'],
            [0.01, 'zero złotych 01/100'],
            [0.99, 'zero złotych 99/100'],
            ['0' , 'zero złotych 00/100'],
            ['0.01' , 'zero złotych 01/100'],
            ['0.99' , 'zero złotych 99/100'],
            [1, 'jeden złoty 00/100'],
            [1.0, 'jeden złoty 00/100'],
            ['1', 'jeden złoty 00/100'],
            ['1.0', 'jeden złoty 00/100'],
            [-1, 'minus jeden złoty 00/100'],
            [1.5, 'jeden złoty 50/100'],
            [10, 'dziesięć złotych 00/100'],
            [11, 'jedenaście złotych 00/100'],
            [12, 'dwanaście złotych 00/100'],
            [15, 'piętnaście złotych 00/100'],
            [20, 'dwadzieścia złotych 00/100'],
            [21, 'dwadzieścia jeden złotych 00/100'],
            [23, 'dwadzieścia trzy złote 00/100'],
            [25, 'dwadzieścia pięć złotych 00/100'],
            [101, 'sto jeden złotych 00/100'],
            [111, 'sto jedenaście złotych 00/100'],
            [112, 'sto dwanaście złotych 00/100'],
            [115, 'sto piętnaście złotych 00/100'],
            [118.08, 'sto osiemnaście złotych 08/100'],
            [121, 'sto dwadzieścia jeden złotych 00/100'],
            [123, 'sto dwadzieścia trzy złote 00/100'],
            [125, 'sto dwadzieścia pięć złotych 00/100'],
            [999, 'dziewięćset dziewięćdziesiąt dziewięć złotych 00/100'],
            [999.99, 'dziewięćset dziewięćdziesiąt dziewięć złotych 99/100'],
            [1121.76, 'tysiąc sto dwadzieścia jeden złotych 76/100'],
            [1234, 'tysiąc dwieście trzydzieści cztery złote 00/100'],
            [20000, 'dwadzieścia tysięcy złotych 00/100'],
            [200000, 'dwieście tysięcy złotych 00/100'],
            [1000123, 'milion sto dwadzieścia trzy złote 00/100'],
            [1234567, 'milion dwieście trzydzieści cztery tysiące pięćset sześćdziesiąt siedem złotych 00/100'],
            [2000000, 'dwa miliony złotych 00/100'],
            [2000123, 'dwa miliony sto dwadzieścia trzy złote 00/100'],
            [20000000, 'dwadzieścia milionów złotych 00/100'],
            [200000000, 'dwieście milionów złotych 00/100'],
            [-200000000, 'minus dwieście milionów złotych 00/100'],
            [1234567890,
            'miliard dwieście trzydzieści cztery miliony pięćset ' .
            'sześćdziesiąt siedem tysięcy osiemset dziewięćdziesiąt złotych 00/100'],
            [2000000000, 'dwa miliardy złotych 00/100'],
            [1234567890123,
            'bilion dwieście trzydzieści cztery miliardy pięćset sześćdziesiąt siedem milionów ' .
            'osiemset dziewięćdziesiąt tysięcy sto dwadzieścia trzy złote 00/100'],
            [2000000000000, 'dwa biliony złotych 00/100'],
            [123567890123456,
            'sto dwadzieścia trzy biliony pięćset sześćdziesiąt siedem miliardów ' .
            'osiemset dziewięćdziesiąt milionów sto dwadzieścia trzy tysiące ' .
            'czterysta pięćdziesiąt sześć złotych 00/100'],
            [999999999999999,
            'dziewięćset dziewięćdziesiąt dziewięć bilionów dziewięćset dziewięćdziesiąt dziewięć miliardów ' .
            'dziewięćset dziewięćdziesiąt dziewięć milionów dziewięćset dziewięćdziesiąt dziewięć tysięcy ' .
            'dziewięćset dziewięćdziesiąt dziewięć złotych 00/100'],
        ];
    }

    /**
     * @dataProvider notANumberInputsProvider
     */
    public function testNotANumberInputs($amount)
    {
        $this->expectException(NotANumberException::class);
        Slownie::printSpelledOut($amount);
    }

    public function notANumberInputsProvider()
    {
        return [
            ['one'],
            ['jeden'],
            ['jeden złoty 00/100'],
            ['hundred'],
        ];
    }
    
    /**
     * @dataProvider outOfRangeInputsProvider
     */
    public function testOutOfRangeInputs($amount)
    {
        $this->expectException(OutOfRangeException::class);
        Slownie::printSpelledOut($amount);
    }

    public function outOfRangeInputsProvider()
    {
        return [
            [1000000000000000],
            [1000000000000000.0],
            ['1000000000000000'],
            [1e15],
            [1000000000000001],
        ];
    }

    public function testGroszes()
    {
        for ($i = 0; $i < 100; $i++) {
            $this->assertSame(sprintf('zero złotych %02d/100', $i), Slownie::printSpelledOut($i / 100));
        }
    }
}
