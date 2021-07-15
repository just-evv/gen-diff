<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Differ\Parsers\parseFile;
use function Differ\DiffGenerator\compareFiles;
use function Differ\Formatter\format;

function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = 'stylish'): string
{
    $file1 = parseFile($pathToFile1);
    $file2 = parseFile($pathToFile2);

    $diff = compareFiles($file1, $file2);

    return format($diff, $formatName);
}
