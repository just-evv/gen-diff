<?php

declare(strict_types=1);

namespace Gendiff\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

function getExtension(string $pathToFile): string
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}

function fileGetContent(string $pathToFile): string
{
    $file = file_get_contents($pathToFile);
    if ($file === false) {
        throw new Exception("{$pathToFile} failed to get content");
    };
    return $file;
}

function jsonParse(string $pathToFile): array
{
    return json_decode(fileGetContent($pathToFile), true);
}

function yamlParseHelper(object $object): array
{
    $array = (array) $object;
    return array_map(fn ($node) => is_object($node) ? yamlParseHelper($node) : $node, $array);
}

function yamlParse(string $pathToFile): array
{
    $parsedToObject = Yaml::parse(fileGetContent($pathToFile), Yaml::PARSE_OBJECT_FOR_MAP);
    return yamlParseHelper($parsedToObject);
}

function parseFile(string $pathToFile): array
{
    $extension = getExtension($pathToFile);
    if ($extension === 'json') {
        return jsonParse($pathToFile);
    } elseif (in_array($extension, ['yaml', 'yml'], true)) {
        return yamlParse($pathToFile);
    } else {
        throw new Exception("{$pathToFile} invalid extension");
    }
}
