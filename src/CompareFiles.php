<?php

declare(strict_types=1);

namespace Gendiff\CompareFiles;

use function Functional\sort;

use function Gendiff\DiffNode\createNode;

function compareFiles(array $file1, array $file2): array
{
    $keys1 = array_keys($file1);
    $keys2 = array_keys($file2);
    $merged = array_merge($keys1, $keys2);

    $allKeys = sort($merged, fn($left, $right) => strcmp($left, $right));

    return array_map(function ($key) use ($file1, $file2): array {
        if (array_key_exists($key, $file1) && array_key_exists($key, $file2)) {
            if (is_array($file1[$key]) && is_array($file2[$key])) {
                return createNode($key, compareFiles($file1[$key], $file2[$key]));
            } elseif ($file1[$key] === $file2[$key]) {
                return createNode($key, $file1[$key]);
            } else {
                return createNode($key, [], $file1[$key], $file2[$key]);
            }
        } elseif (array_key_exists($key, $file1)) {
            return createNode($key, [], $file1[$key]);
        } elseif (array_key_exists($key, $file2)) {
            return createNode($key, [], [], $file2[$key]);
        };
        return [];
    }, $allKeys);
}
