<?php

declare(strict_types=1);

namespace Gendiff\CompareFiles;

use function Functional\sort;
use function Functional\flatten;
use function Gendiff\DiffNode\createNode;
use function Gendiff\DiffNode\setNoChanges;
use function Gendiff\DiffNode\setBefore;
use function Gendiff\DiffNode\setAfter;

function compareFiles(array $file1, array $file2): array
{
    $keys1 = array_keys($file1);
    $keys2 = array_keys($file2);
    $merged = array_merge($keys1, $keys2);

    $allKeys = sort($merged, fn ($left, $right) => strcmp($left, $right));

    $result = array_map(function ($key) use ($file1, $file2) {
        if (array_key_exists($key, $file1) && array_key_exists($key, $file2)) {
            if (is_array($file1[$key]) && is_array($file2[$key])) {
                return createNode(compareFiles($file1[$key], $file2[$key]), [], []);
            } elseif ($file1[$key] === $file2[$key]) {
                return createNode($file1[$key], [], []);
            } else {
                return createNode([], $file1[$key], $file2[$key]);
            }
        } elseif (array_key_exists($key, $file1)) {
            return createNode([], $file1[$key], []);
        } elseif (array_key_exists($key, $file2)) {
            return createNode([], [], $file2[$key]);
        };
        return [];
    }, $allKeys);

    //print_r(array_combine($allKeys, $result));

    return array_combine($allKeys, $result);
/*
    return array_reduce($allKeys, function ($acc, $key) use ($file1, $file2) {
        $currentNode = createNode();
        if (array_key_exists($key, $file1) && array_key_exists($key, $file2)) {
            if (is_array($file1[$key]) && is_array($file2[$key])) {
                $currentNode = setNoChanges($currentNode, compareFiles($file1[$key], $file2[$key]));
            } elseif ($file1[$key] === $file2[$key]) {
                $currentNode = setNoChanges($currentNode, $file1[$key]);
            } else {
                $currentNode = setBefore($currentNode, $file1[$key]);
                $currentNode = setAfter($currentNode, $file2[$key]);
            }
        } elseif (array_key_exists($key, $file1)) {
            $currentNode = setBefore($currentNode, $file1[$key]);
        } elseif (array_key_exists($key, $file2)) {
            $currentNode = setAfter($currentNode, $file2[$key]);
        };
        $acc[$key] = $currentNode;
        return $acc;
    }, []);
*/
}
