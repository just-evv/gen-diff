<?php

declare(strict_types=1);

namespace Gendiff\Gendiff;

use Symfony\Component\Yaml\Yaml;

function getExtension(string $pathToFile): string
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}

function jsonParse($pathToFile): array
{
    $file = file_get_contents($pathToFile);
    return json_decode($file, true);
}

function yamlParseHelper(object $object): array
{
    $array = (array) $object;
    return array_map(fn ($node) => is_object($node) ? yamlParseHelper($node) : $node, $array);
}

function yamlParse(string $pathToFile): array
{
    $parsedToObject = Yaml::parseFile($pathToFile, Yaml::PARSE_OBJECT_FOR_MAP);
    return yamlParseHelper($parsedToObject);
}

function parseFile(string $pathToFile): array
{
    if (getExtension($pathToFile) === 'json') {
        return jsonParse($pathToFile);
    } elseif (getExtension($pathToFile) === 'yaml' || 'yml') {
        return yamlParse($pathToFile);
    } else {
        throw new \Exception("{$pathToFile} invalid extension");
    }
}
