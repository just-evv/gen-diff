<?php

declare(strict_types=1);

namespace Differ\DiffGenerator;

use Exception;

use function Functional\sort;

function createNode(string $name, array $children = []): array
{
    return ['name' => $name, 'children' => $children, 'type' => 'no changes'];
}

function createLeaf(string $name, string $type, mixed $value1, mixed $value2 = null): array
{
    if ($type === 'no changes') {
        return ['name' => $name, 'type' => $type, 'value' => $value1];
    } elseif ($type === 'changed') {
        return ['name' => $name, 'type' => $type, 'value' => $value1, 'value2' => $value2];
    } elseif ($type === 'removed') {
        return ['name' => $name, 'type' => $type, 'value' => $value1];
    } elseif ($type === 'added') {
        return ['name' => $name, 'type' => $type, 'value' => $value1];
    } else {
        throw new Exception("undefined type");
    }
}

function getName(array $node): string
{
    return $node['name'];
}

function getType(array $node): string
{
    return $node['type'];
}

function isNode(array $node): bool
{
    return array_key_exists('children', $node);
}

function getChildren(array $node): array
{
    return $node['children'];
}

function getValue(array $node): mixed
{
    return $node['value'];
}

function getValue2(array $node): mixed
{
    return $node['value2'];
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
                return createLeaf($key, 'no changes', $file1[$key]);
            } else {
                return createLeaf($key, 'changed', $file1[$key], $file2[$key]);
            }
        } elseif (array_key_exists($key, $file1)) {
            return createLeaf($key, 'removed', $file1[$key]);
        } elseif (array_key_exists($key, $file2)) {
            return createLeaf($key, 'added', $file2[$key]);
        };
        return [];
    }, $allKeys);
}
