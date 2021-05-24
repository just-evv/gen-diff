<?php

declare(strict_types=1);

namespace Gendiff\Gendiff;

use function Gendiff\Parsers\parseFile;
use function Gendiff\Compare\compareFiles;
use function Gendiff\Formatter\stylish;

function formatOutput($marker, $key, $value): string
{
    return '  ' . $marker . $key . ' = ' . formatValue($value);
}
/*
function compareFiles($key, $array1, $array2): string
{
    $onlyInFirst = '- ';
    $inBoth = '  ';
    $onlyInSecond = '+ ';

    if (array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
        if ($array1[$key] === $array2[$key]) {
            return formatOutput($inBoth, $key, $array1[$key]);
        } else {
            return formatOutput($onlyInFirst, $key, $array1[$key]) . "\n" .
                formatOutput($onlyInSecond, $key, $array2[$key]);
        }
    } elseif (array_key_exists($key, $array1) && !array_key_exists($key, $array2)) {
        return  formatOutput($onlyInFirst, $key, $array1[$key]);
    } elseif (!array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
        return  formatOutput($onlyInSecond, $key, $array2[$key]);
    }
}
*/

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish')
{
    $file1 = parseFile($pathToFile1);
    $file2 = parseFile($pathToFile2);

    $diff = compareFiles($file1, $file2);

    return stylish($diff);
}
