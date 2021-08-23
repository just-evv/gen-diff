<?php

declare(strict_types=1);

namespace Differ\Differ;

use Exception;

use function Differ\Parsers\parse;
use function Differ\DiffGenerator\compareTrees;
use function Differ\Formatter\format;

/**
 * @throws Exception
 */

function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = 'stylish'): string
{
    $fileContent1 = parse(fileGetContent($pathToFile1), getExtension($pathToFile1));
    $fileContent2 = parse(fileGetContent($pathToFile2), getExtension($pathToFile2));

    $diff = compareTrees($fileContent1, $fileContent2);

    return format($diff, $formatName);
}

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
