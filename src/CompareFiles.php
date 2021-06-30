<?php

declare(strict_types=1);

namespace Gendiff\CompareFiles;

use function Functional\sort;
//use function Gendiff\DiffNode\createNode;

function createNode(string $name, array $children = [], string $type = 'no changes', $value1 = '', $value2 = ''): array
{
    return ['name' => $name, 'type' => $type, 'children' => $children, 'value1' => $value1, 'value2' => $value2];
}

function getName(array $node): string
{
    return $node['name'];
}

function getType(array $node): string
{
    return $node['type'];
}

function getChildren(array $node): array
{
    return $node['children'];
}

function getValue1(array $node)
{
    return $node['value 1'];
}

function getValue2(array $node)
{
    return $node['value 2'];
}

function compareFiles(array $file1, array $file2): array
{
    $keys1 = array_keys($file1);
    $keys2 = array_keys($file2);
    $merged = array_unique(array_merge($keys1, $keys2));

    $allKeys = sort($merged, fn($left, $right) => strcmp($left, $right));

    return array_map(function ($key) use ($file1, $file2): array {
        if (array_key_exists($key, $file1) && array_key_exists($key, $file2)) {
            if (is_array($file1[$key]) && is_array($file2[$key])) {
                return createNode($key, compareFiles($file1[$key], $file2[$key]));
            } elseif ($file1[$key] === $file2[$key]) {
                return createNode($key, [], 'no changes', $file1[$key]);
            } else {
                return createNode($key, [], 'changed', $file1[$key], $file2[$key]);
            }
        } elseif (array_key_exists($key, $file1)) {
            return createNode($key, [], 'removed', $file1[$key]);
        } elseif (array_key_exists($key, $file2)) {
            return createNode($key, [], 'added', $file2[$key]);
        };
        return [];
    }, $allKeys);
}
