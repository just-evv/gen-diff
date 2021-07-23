<?php

namespace Hexlet\Code\Tests;

use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public function getPathFixture(string $fixtureName): string
    {
        return realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', $fixtureName]));
    }

    #[Pure]
    public function dataProvider(): array
    {
        return [
            [
                $this->getPathFixture('file1.json'),
                $this->getPathFixture('file2.json'),
                $this->getPathFixture('ExpectedStylish'),
                'stylish'
            ],
            [
                $this->getPathFixture('file1.yaml'),
                $this->getPathFixture('file2.yaml'),
                $this->getPathFixture('ExpectedStylish'),
                'stylish'
            ],
            [
                $this->getPathFixture('file1.json'),
                $this->getPathFixture('file2.json'),
                $this->getPathFixture('ExpectedPlain'),
                'plain'
            ],
            [
                $this->getPathFixture('file1.yaml'),
                $this->getPathFixture('file2.yaml'),
                $this->getPathFixture('ExpectedPlain'),
                'plain'
            ],
            [
                $this->getPathFixture('file1.json'),
                $this->getPathFixture('file2.json'),
                $this->getPathFixture('ExpectedJson.json'),
                'json'
            ],
            [
                $this->getPathFixture('file1.yaml'),
                $this->getPathFixture('file2.yaml'),
                $this->getPathFixture('ExpectedJson.json'),
                'json'
            ],
            [
                $this->getPathFixture('file1.json'),
                $this->getPathFixture('file2.yaml'),
                $this->getPathFixture('ExpectedStylish'),
                'stylish'
            ]
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @covers       \Differ\DiffGenerator\compareTrees
     * @covers       \Differ\DiffGenerator\createNode
     * @covers       \Differ\DiffGenerator\getName
     * @covers       \Differ\DiffGenerator\getType
     * @covers       \Differ\DiffGenerator\getChildren
     * @covers       \Differ\DiffGenerator\getValue
     * @covers       \Differ\DiffGenerator\getValue2
     * @covers       \Differ\Formatter\format
     * @covers       \Differ\Parsers\parseFile
     * @covers       \Differ\Parsers\getExtension
     * @covers       \Differ\Parsers\fileGetContent
     * @covers       \Differ\Formatter\Stylish\genStylish
     * @covers       \Differ\Formatter\Stylish\formatValue
     * @covers       \Differ\Formatter\Stylish\formatArray
     * @covers       \Differ\Formatter\Stylish\makeString
     * @covers       \Differ\Formatter\Stylish\getValuePrefix
     * @covers       \Differ\Formatter\Stylish\stylishCreator
     * @covers       \Differ\Formatter\Json\genJson
     * @covers       \Differ\Formatter\Plain\checkValue
     * @covers       \Differ\Formatter\Plain\formatValue
     * @covers       \Differ\Formatter\Plain\genString
     * @covers       \Differ\Formatter\Plain\genPlain
     * @covers       \Differ\Differ\genDiff
     * @covers       \Differ\Parsers\jsonParse
     * @covers       \Differ\Parsers\yamlParse
     * @covers       \Differ\Parsers\yamlParseHelper
     * @throws \Exception
     */
    public function testGenDiff(string $path1, string $path2, string $expected, string $format): void
    {
        $this->assertStringEqualsFile($expected, genDiff($path1, $path2, $format));
    }
}
