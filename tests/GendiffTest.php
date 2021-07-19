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
     * @covers \Differ\DiffGenerator\compareTrees
     * @covers \Differ\DiffGenerator\createNode
     * @covers \Differ\DiffGenerator\createLeaf
     * @covers \Differ\DiffGenerator\isNode
     * @covers \Differ\DiffGenerator\getName
     * @covers \Differ\DiffGenerator\getType
     * @covers \Differ\DiffGenerator\getChildren
     * @covers \Differ\DiffGenerator\getValue
     * @covers \Differ\DiffGenerator\getValue2
     * @covers \Differ\Formatter\format
     * @covers \Differ\Parsers\parseFile
     * @covers \Differ\Parsers\getExtension
     * @covers \Differ\Parsers\fileGetContent
     * @covers \Differ\Formatter\Stylish\genStylish
     * @covers \Differ\Formatter\Stylish\formatValue
     * @covers \Differ\Formatter\Stylish\formatArray
     * @covers \Differ\Formatter\Stylish\makeString
     * @covers \Differ\Formatter\Stylish\getValueId
     * @covers \Differ\Formatter\Stylish\stylishCreator
     * @covers \Differ\Formatter\Json\genJson
     * @covers \Differ\Formatter\Plain\checkValue
     * @covers \Differ\Formatter\Plain\formatValue
     * @covers \Differ\Formatter\Plain\genString
     * @covers \Differ\Formatter\Plain\genPlain
     * @covers \Differ\Differ\genDiff
     * @covers \Differ\Parsers\jsonParse
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
     * @covers \Differ\DiffGenerator\compareTrees
     * @covers \Differ\DiffGenerator\createNode
     * @covers \Differ\DiffGenerator\createLeaf
     * @covers \Differ\DiffGenerator\isNode
     * @covers \Differ\DiffGenerator\getName
     * @covers \Differ\DiffGenerator\getType
     * @covers \Differ\DiffGenerator\getChildren
     * @covers \Differ\DiffGenerator\getValue
     * @covers \Differ\DiffGenerator\getValue2
     * @covers \Differ\Formatter\format
     * @covers \Differ\Parsers\parseFile
     * @covers \Differ\Parsers\getExtension
     * @covers \Differ\Parsers\fileGetContent
     * @covers \Differ\Formatter\Stylish\genStylish
     * @covers \Differ\Formatter\Stylish\formatValue
     * @covers \Differ\Formatter\Stylish\formatArray
     * @covers \Differ\Formatter\Stylish\makeString
     * @covers \Differ\Formatter\Stylish\getValueId
     * @covers \Differ\Formatter\Stylish\stylishCreator
     * @covers \Differ\Formatter\Json\genJson
     * @covers \Differ\Formatter\Plain\checkValue
     * @covers \Differ\Formatter\Plain\formatValue
     * @covers \Differ\Formatter\Plain\genString
     * @covers \Differ\Formatter\Plain\genPlain
     * @covers \Differ\Differ\genDiff
     * @covers \Differ\Parsers\yamlParse
     * @covers \Differ\Parsers\yamlParseHelper
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
