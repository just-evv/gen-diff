<?php

declare(strict_types=1);

namespace Gendiff\Gendiff;

use Symfony\Component\Yaml\Yaml;
use function PHPUnit\Framework\throwException;

function getExtension(string $pathToFile): string
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}

function jsonParse($pathToFile): array
{
    $file = file_get_contents($pathToFile);
    return json_decode($file, true);
}

function yamlParse(string $pathToFile): array
{
    return (array) Yaml::parseFile($pathToFile, Yaml::PARSE_OBJECT_FOR_MAP);
}

function parseFile(string $pathToFile): array
{
    if (getExtension($pathToFile) === 'json') {
        return jsonParse($pathToFile);
    } elseif (getExtension($pathToFile) === 'yaml' || 'yml') {
        return yamlParse($pathToFile);
    } else {
        throwException("{$pathToFile} file is not parsable");
    }
}
