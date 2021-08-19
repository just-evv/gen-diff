<?php

declare(strict_types=1);

namespace Differ\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

function jsonParse(string $fileContent): array
{
    return json_decode($fileContent, true);
}

function yamlParse(string $fileContent): array
{
    return Yaml::parse($fileContent);
}

function parseFile(string $fileContent, string $extension): array
{
    if ($extension === 'json') {
        return jsonParse($fileContent);
    } elseif (in_array($extension, ['yaml', 'yml'], true)) {
        return yamlParse($fileContent);
    } else {
        throw new Exception("{$fileContent} invalid extension");
    }
}
