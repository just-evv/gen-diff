<?php

declare(strict_types=1);

namespace Differ\DiffGenerator;

use Exception;

use function Functional\sort;

/**
 * @throws Exception
 */
function createLeaf(string $name, string $type, mixed $value1, mixed $value2 = null): array
{
    return match ($type) {
        'no changes', 'removed', 'added' => ['name' => $name, 'type' => $type, 'value' => $value1],
        'changed' => ['name' => $name, 'type' => $type, 'value' => $value1, 'value2' => $value2],
        default => throw new Exception("undefined type"),
    };
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

function getValue(array $node): mixed
{
    return $node['value'];
}

function getValue2(array $node): mixed
{
    return $node['value2'];
}

function compareTrees(array $tree1, array $tree2): array
{
    $keys1 = array_keys($tree1);
    $keys2 = array_keys($tree2);
    $merged = array_unique(array_merge($keys1, $keys2));

    $allKeys = sort($merged, fn($left, $right) => strcmp($left, $right));

    return array_map(function ($key) use ($tree1, $tree2): array {
        return match (true) {
            array_key_exists($key, $tree1) && array_key_exists($key, $tree2) => match (true) {
                is_array($tree1[$key]) && is_array($tree2[$key])
                => ['name' => $key, 'type' => 'nested', 'children' => compareTrees($tree1[$key], $tree2[$key])],
                $tree1[$key] === $tree2[$key] => createLeaf($key, 'no changes', $tree1[$key]),
                default => createLeaf($key, 'changed', $tree1[$key], $tree2[$key]),
            },
            array_key_exists($key, $tree1) => createLeaf($key, 'removed', $tree1[$key]),
            array_key_exists($key, $tree2) => createLeaf($key, 'added', $tree2[$key]),
            default => throw new Exception("something went wrong"),
        };
    }, $allKeys);
}
