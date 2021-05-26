<?php

namespace Gendiff\Formatter\Stylish;

use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    public function testStylish()
    {
        $array = ['key' => 'value', 'key 2' => ['key 3' => false]];

        $expected = <<<'EOD'
{
  key: value
  key 2: {
    key 3: false
  }
}
EOD;

        $this->assertEquals($expected, stylish($array));
    }

    public function testStylish2()
    {
        $array = ['common' => [
            'before' => [],
            'after' => [],
            'noChanges' =>
                ['setting1' =>
                    ['before' => [], 'after' => [], 'noChanges' => 'value 1']
                ]

        ]
        ];
        $expected = <<<'EOD'
{
    common: {
        setting1: value 1
    }
}
EOD;
        $this->assertEquals($expected, stylish($array));
    }
}
