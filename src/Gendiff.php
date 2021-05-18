<?php

declare(strict_types=1);

namespace Gendiff\Gendiff;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

function formatValue($value): string
{
    if (gettype($value) !== "string") {
        return json_encode($value);
    }
    return $value;
}

function formatOutput($marker, $key, $value): string
{
    return '  ' . $marker . $key . ' = ' . formatValue($value);
}

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


function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish')
{
    $file1 = parseFile($pathToFile1);
    $file2 = parseFile($pathToFile2);

    $keys = array_keys(array_merge($file1, $file2));
    sort($keys);

    $result = array_map(fn($key) => compareFiles($key, $file1, $file2), $keys);
    return "{\n" . implode("\n", $result) . "\n}\n";
}
