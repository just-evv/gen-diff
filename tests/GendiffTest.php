<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GendiffTest extends TestCase
{
    private string $pathToFile1;
    private string $pathToFile2;

    public function setUp(): void
    {
        $this->pathToFile1 = '';
        $this->pathToFile2 = '';
    }

    /**
     * @covers \Gendiff\CompareFiles\compareFiles
     * @covers \Gendiff\CompareFiles\createNode
     * @covers \Gendiff\CompareFiles\createLeaf
     * @covers \Gendiff\CompareFiles\isNode
     * @covers \Gendiff\CompareFiles\getName
     * @covers \Gendiff\CompareFiles\getType
     * @covers \Gendiff\CompareFiles\getChildren
     * @covers \Gendiff\CompareFiles\getValue
     * @covers \Gendiff\CompareFiles\getValue2
     * @covers \Gendiff\Formatter\formatter
     * @covers \Gendiff\Parsers\parseFile
     * @covers \Gendiff\Parsers\getExtension
     * @covers \Gendiff\Parsers\fileGetContent
     * @covers \Gendiff\Formatter\Stylish\genStylish
     * @covers \Gendiff\Formatter\Stylish\formatValue
     * @covers \Gendiff\Formatter\Stylish\formatArray
     * @covers \Gendiff\Formatter\Stylish\makeString
     * @covers \Gendiff\Formatter\Stylish\getValueId
     * @covers \Gendiff\Formatter\Stylish\stylishCreator
     * @covers \Gendiff\Formatter\Json\genJson
     * @covers \Gendiff\Formatter\Plain\checkValue
     * @covers \Gendiff\Formatter\Plain\formatValue
     * @covers \Gendiff\Formatter\Plain\genPlain
     * @covers \Differ\Differ\genDiff
     * @covers \Gendiff\Parsers\jsonParse
     */
    public function testGenDiffJson()
    {
        $this->pathToFile1 = __DIR__ . '/fixtures/nested/json/file1.json';
        $this->pathToFile2 = __DIR__ . '/fixtures/nested/json/file2.json';

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

        $expected3 = file_get_contents(__DIR__ . '/fixtures/nested/output_json.json');
        $formatName2 = 'json';
        $actual3 = genDiff($this->pathToFile1, $this->pathToFile2, $formatName2);

        $this->assertEquals($expected3, $actual3);
    }
    /**
     * @covers \Gendiff\CompareFiles\compareFiles
     * @covers \Gendiff\CompareFiles\createNode
     * @covers \Gendiff\CompareFiles\createLeaf
     * @covers \Gendiff\CompareFiles\isNode
     * @covers \Gendiff\CompareFiles\getName
     * @covers \Gendiff\CompareFiles\getType
     * @covers \Gendiff\CompareFiles\getChildren
     * @covers \Gendiff\CompareFiles\getValue
     * @covers \Gendiff\CompareFiles\getValue2
     * @covers \Gendiff\Formatter\formatter
     * @covers \Gendiff\Parsers\parseFile
     * @covers \Gendiff\Parsers\getExtension
     * @covers \Gendiff\Parsers\fileGetContent
     * @covers \Gendiff\Formatter\Stylish\genStylish
     * @covers \Gendiff\Formatter\Stylish\formatValue
     * @covers \Gendiff\Formatter\Stylish\formatArray
     * @covers \Gendiff\Formatter\Stylish\makeString
     * @covers \Gendiff\Formatter\Stylish\getValueId
     * @covers \Gendiff\Formatter\Stylish\stylishCreator
     * @covers \Gendiff\Formatter\Json\genJson
     * @covers \Gendiff\Formatter\Plain\checkValue
     * @covers \Gendiff\Formatter\Plain\formatValue
     * @covers \Gendiff\Formatter\Plain\genPlain
     * @covers \Differ\Differ\genDiff
     * @covers \Gendiff\Parsers\yamlParse
     * @covers \Gendiff\Parsers\yamlParseHelper
     */
    public function testGenDiffYaml()
    {
        $this->pathToFile1 = __DIR__ . '/fixtures/nested/yaml/file1.yaml';
        $this->pathToFile2 = __DIR__ . '/fixtures/nested/yaml/file2.yaml';

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

        $expected3 = file_get_contents(__DIR__ . '/fixtures/nested/output_json.json');
        $formatName2 = 'json';
        $actual3 = genDiff($this->pathToFile1, $this->pathToFile2, $formatName2);

        $this->assertEquals($expected3, $actual3);
    }
}
