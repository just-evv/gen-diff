<?php

declare(strict_types = 1);
namespace Gendiff;

function readFiles(string $pathToFile1, string $pathToFile2): array
{
    $file1 = file_get_contents($pathToFile1);
    $file2 = file_get_contents($pathToFile2);
    return [$file1, $file2];
}

function jsonDecode(array $files): array
{
    $decodedFile1 = json_decode($files[0], true);
    $decodedFile2 = json_decode($files[1], true);
    return [$decodedFile1, $decodedFile2];
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
    return $marker. $key . ' = ' . formatValue($value);
}

function compareFiles($key, $array1, $array2): string
{
    $onlyInFirst = '- ';
    $inBoth = '  ';
    $onlyInSecond= '+ ';

    if (array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
        if ($array1[$key] === $array2[$key]) {
            return formatOutput($inBoth, $key, $array1[$key]);
        } else {
            return formatOutput($onlyInFirst, $key, $array1[$key]) . "\n" . formatOutput($onlyInSecond, $key, $array2[$key]);
        }
    } elseif (array_key_exists($key, $array1) && !array_key_exists($key, $array2)) {
        return  formatOutput($onlyInFirst, $key, $array1[$key]);
    } elseif (!array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
        return  formatOutput($onlyInSecond, $key, $array2[$key]);
    }
}

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish')
{
    $readFiles = readFiles($pathToFile1, $pathToFile2);
    [$file1, $file2] = jsonDecode($readFiles);

    $keys = array_keys(array_merge($file1, $file2));
    sort($keys);

    $result = array_map(fn($key) => compareFiles($key, $file1, $file2), $keys);
    return "{\n" . implode("\n", $result) . "\n}";
}
