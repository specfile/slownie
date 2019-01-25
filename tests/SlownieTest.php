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
        $this->assertSame($expected, Slownie::print($amount));
    }

    public function validInputsProvider()
    {
        return [
            [0 , 'zero złotych 00/100'],
            [0.01 , 'zero złotych 01/100'],
            [0.99 , 'zero złotych 99/100'],
            [1 , 'jeden złoty 00/100'],
            [1.0 , 'jeden złoty 00/100'],
            [11 , 'jedenaście złotych 00/100'],
            [12 , 'dwanaście złotych 00/100'],
            [15 , 'piętnaście złotych 00/100'],
            [21 , 'dwadzieścia jeden złotych 00/100'],
            [23 , 'dwadzieścia trzy złote 00/100'],
            [25 , 'dwadzieścia pięć złotych 00/100'],
            [121 , 'sto dwadzieścia jeden złotych 00/100'],
            [123 , 'sto dwadzieścia trzy złote 00/100'],
            [125 , 'sto dwadzieścia pięć złotych 00/100'],
            [999 , 'dziewięćset dziewięćdziesiąt dziewięć 00/100'],
            [1234 , 'tysiąc dwieście trzydzieści cztery złote 00/100'],
            [1234567 , 'milion dwieście trzydzieści cztery tysiące pięćset sześćdziesiąt siedem złotych 00/100'],
            [1234567890 , 'miliard dwieście trzydzieści cztery miliony pięćset sześćdziesiąt siedem tysięcy osiemset dziewięćdziesiąt złotych 00/100'],
            [1234567890123 , 'bilion dwieście trzydzieści cztery miliardy pięćset sześćdziesiąt siedem milionów osiemset dziewięćdziesiąt tysięcy sto dwadzieścia trzy złote 00/100'],
            [123567890123456, 'sto dwadzieścia trzy biliony pięćset sześćdziesiąt siedem miliardów osiemset dziewięćdziesiąt milionów sto dwadzieścia trzy tysiące czterysta pięćdziesiąt sześć złotych 00/100'],
            [999999999999999, 'dziewięćset dziewięćdziesiąt dziewięć bilionów dziewięćset dziewięćdziesiąt dziewięć miliardy dziewięćset dziewięćdziesiąt dziewięć milionów dziewięćset dziewięćdziesiąt dziewięć tysięcy dziewięćset dziewięćdziesiąt dziewięć złotych 00/100'],
            [999999999999999.99, 'dziewięćset dziewięćdziesiąt dziewięć bilionów dziewięćset dziewięćdziesiąt dziewięć miliardy dziewięćset dziewięćdziesiąt dziewięć milionów dziewięćset dziewięćdziesiąt dziewięć tysięcy dziewięćset dziewięćdziesiąt dziewięć złotych 99/100'],
        ];
    }
}
