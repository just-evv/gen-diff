<?php

namespace Gendiff\Formatter\Plain;

use PHPUnit\Framework\TestCase;

class PlainTest extends TestCase
{
    public function testPlainFlat()
    {
        $array = ['common' => [
            'before' => [],
            'after' => [],
            'noChanges' =>
                ['setting1' =>
                    ['before' => 'value 1', 'after' => 'value 2', 'noChanges' => []],
                    'setting2' =>
                        ['before' => 'value 3', 'after' => [], 'noChanges' => []]

                ]

        ]
        ];
        $expected = <<<'EOD'
Property 'common.setting1' was updated. From 'value 1' to 'value 2'
Property 'common.setting2' was removed
EOD;
        $this->assertEquals($expected, plain($array));
    }
}
