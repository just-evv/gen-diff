<?php

namespace Hexlet\Code\Tests;

use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    #[ArrayShape(['stylish' => "false|string", 'plain' => "false|string", 'json' => "false|string"])]
    public function getFixtures(): array
    {
        $stylish = file_get_contents(__DIR__ . '/fixtures/nested/ExpectedStylish');
        $plain = file_get_contents(__DIR__ . '/fixtures/nested/ExpectedPlain');
        $json = file_get_contents(__DIR__ . '/fixtures/nested/ExpectedJson.json');

        return ['stylish' => $stylish, 'plain' => $plain, 'json' => $json];
    }

    public function additionProvider(): array
    {
        $pathToFileJson1 =  __DIR__ . '/fixtures/nested/json/file1.json';
        $pathToFileJson2 =  __DIR__ . '/fixtures/nested/json/file2.json';

        $pathToFileYaml1 =  __DIR__ . '/fixtures/nested/yaml/file1.yaml';
        $pathToFileYaml2 =  __DIR__ . '/fixtures/nested/yaml/file2.yaml';

       return [
            [$pathToFileJson1, $pathToFileJson2],
            [$pathToFileYaml1, $pathToFileYaml2]
        ];
    }
    /**
     * @dataProvider additionProvider
     *
     * @covers \Differ\DiffGenerator\compareTrees
     * @covers \Differ\DiffGenerator\createLeaf
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
     * @covers \Differ\Formatter\Stylish\getValuePrefix
     * @covers \Differ\Formatter\Stylish\stylishCreator
     * @covers \Differ\Formatter\Json\genJson
     * @covers \Differ\Formatter\Plain\checkValue
     * @covers \Differ\Formatter\Plain\formatValue
     * @covers \Differ\Formatter\Plain\genString
     * @covers \Differ\Formatter\Plain\genPlain
     * @covers \Differ\Differ\genDiff
     * @covers \Differ\Parsers\jsonParse
     * @covers \Differ\Parsers\yamlParse
     * @covers \Differ\Parsers\yamlParseHelper
     */
    public function testGenDiff($path1, $path2) {
        $expected = $this->getFixtures();

        $this->assertEquals($expected['stylish'], genDiff($path1, $path2));
        $this->assertEquals($expected['stylish'], genDiff($path1, $path2, 'stylish'));
        $this->assertEquals($expected['plain'], genDiff($path1, $path2, 'plain'));
        $this->assertEquals($expected['json'], genDiff($path1, $path2, 'json'));
    }
}
