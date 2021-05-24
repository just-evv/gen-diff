<?php

namespace Gendiff\Formatter;

use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    public function testSpacer()
    {
        $array = ['key' => 'value', 'key 2' => ['key 3' => 'value 3']];
        $expected = ['  key' => 'value', '  key 2' => ['    key 3' => 'value 3']];

        $this->assertEquals($expected, spacer($array));
    }

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
}
