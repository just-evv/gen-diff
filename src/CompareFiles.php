<?php

declare(strict_types=1);

namespace Gendiff\CompareFiles;

use function Gendiff\DiffNode\createNode;
use function Gendiff\DiffNode\setNoChanges;
use function Gendiff\DiffNode\setBefore;
use function Gendiff\DiffNode\setAfter;

function compareFiles(array $file1, array $file2): array
{
    $keys1 = array_keys($file1);
    $keys2 = array_keys($file2);
    $allKeys = array_merge($keys1, $keys2);
    sort($allKeys);

    $acc = [];

    foreach ($allKeys as $key) {
        $currentNode = createNode();

        if (array_key_exists($key, $file1) && array_key_exists($key, $file2)) {
            if (is_array($file1[$key]) && is_array($file2[$key])) {
                setNoChanges($currentNode, compareFiles($file1[$key], $file2[$key]));
            } elseif ($file1[$key] === $file2[$key]) {
                setNoChanges($currentNode, $file1[$key]);
            } else {
                setBefore($currentNode, $file1[$key]);
                setAfter($currentNode, $file2[$key]);
            }
        } elseif (array_key_exists($key, $file1)) {
            setBefore($currentNode, $file1[$key]);
        } elseif (array_key_exists($key, $file2)) {
            setAfter($currentNode, $file2[$key]);
        };
        $acc[$key] = $currentNode;
    }
    return $acc;
}
