<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Gendiff\genDiff;

class GendiffTest extends TestCase
{
    private string $pathToFile1;
    private string $pathToFile2;

    public function setUp(): void
    {
        $this->pathToFile1 = __DIR__ . '/fixtures/nested/json/file1.json';
        $this->pathToFile2 = __DIR__ . '/fixtures/nested/json/file2.json';
    }

    /**
     * @covers \Gendiff\CompareFiles\compareFiles
     * @covers \Gendiff\CompareFiles\createNode
     * @covers \Gendiff\CompareFiles\setNoChanges
     * @covers \Gendiff\CompareFiles\setAfter
     * @covers \Gendiff\CompareFiles\setBefore
     * @covers \Gendiff\Formatter\formatter
     * @covers \Gendiff\Parsers\parseFile
     * @covers \Gendiff\Formatter\Stylish\stylish
     * @covers \Gendiff\CompareFiles\getAfter
     * @covers \Gendiff\CompareFiles\getBefore
     * @covers \Gendiff\CompareFiles\getNoChanges
     * @covers \Gendiff\CompareFiles\isValueSet
     * @covers \Gendiff\Formatter\Json\json
     * @covers \Gendiff\Formatter\Json\jsonHelper
     * @covers \Gendiff\Formatter\Plain\checkValue
     * @covers \Gendiff\Formatter\Plain\formatValue
     * @covers \Gendiff\Formatter\Plain\plain
     * @covers \Gendiff\Formatter\Stylish\formatValue
     * @covers \Gendiff\Formatter\Stylish\renderValue
     * @covers \Gendiff\Gendiff\genDiff
     * @covers \Gendiff\Parsers\getExtension
     * @covers \Gendiff\Parsers\jsonParse
     */
    public function testGenDiff1()
    {
        $expected1 = <<<'EOD'
{
    common: {
      + follow: false
        setting1: Value 1
      - setting2: 200
      - setting3: true
      + setting3: null
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
        setting6: {
            doge: {
              - wow: 
              + wow: so much
            }
            key: value
          + ops: vops
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
      - nest: {
            key: value
        }
      + nest: str
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
        fee: 100500
    }
}
EOD;
        $actual1 = genDiff($this->pathToFile1, $this->pathToFile2);

        $this->assertEquals($expected1, $actual1);

        $expected2 = <<<'EOD'
Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to null
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]
EOD;
        $formatName1 = 'plain';
        $actual2 = genDiff($this->pathToFile1, $this->pathToFile2, $formatName1);

        $this->assertEquals($expected2, $actual2);

        $expected3 = <<<'EOD'
{"common":{"follow":{"second file":false},"setting1":"Value 1","setting2":{"first file":200},"setting3":{"first file":true,"second file":null},"setting4":{"second file":"blah blah"},"setting5":{"second file":{"key5":"value5"}},"setting6":{"doge":{"wow":{"first file":"","second file":"so much"}},"key":"value","ops":{"second file":"vops"}}},"group1":{"baz":{"first file":"bas","second file":"bars"},"foo":"bar","nest":{"first file":{"key":"value"},"second file":"str"}},"group2":{"first file":{"abc":12345,"deep":{"id":45}}},"group3":{"second file":{"deep":{"id":{"number":45}},"fee":100500}}}
EOD;
        $formatName2 = 'json';
        $actual3 = genDiff($this->pathToFile1, $this->pathToFile2, $formatName2);

        $this->assertEquals($expected3, $actual3);
    }
}
