<?php

namespace Gendiff\Parsers;

use PHPUnit\Framework\TestCase;

class ParsersTest extends TestCase
{
    public function testJsonParse()
    {
        $pathToFile = 'fixtures/nested/json/file1.json';
        $absolutePathToFile = getcwd() . '/' . $pathToFile;

        $result1 = jsonParse($pathToFile);
        $result2 = jsonParse($absolutePathToFile);

        $this->assertIsArray($result1);
        $this->assertIsArray($result2);
    }

    public function testYamlParse()
    {
        $pathToFile = 'fixtures/nested/yaml/file1.yaml';
        $absolutePathToFile = getcwd() . '/' . $pathToFile;

        $result2 = yamlParse($absolutePathToFile);
        $result1 = yamlParse($pathToFile);

        $this->assertIsArray($result1);
        $this->assertIsArray($result2);
    }

    public function testParseFile()
    {
        $pathToFileYaml = 'fixtures/nested/yaml/file1.yaml';
        $pathToFileJson = 'fixtures/nested/json/file1.json';

        $parsedYaml = parseFile($pathToFileYaml);
        $parsedJson = parseFile($pathToFileJson);

        $this-> assertEquals($parsedYaml, $parsedJson);
    }
}
