<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Gendiff\Parsers\parseFile;
use function Gendiff\CompareFiles\compareFiles;
use function Gendiff\Formatter\formatter;

function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = ''): string
{
    $file1 = parseFile($pathToFile1);
    $file2 = parseFile($pathToFile2);

    $diff = compareFiles($file1, $file2);

    return formatter($diff, $formatName);
}
