<?php

declare(strict_types=1);

namespace Differ\DiffGenerator;

use Exception;

use function Functional\sort;

/**
 * @throws Exception
 */
function createNode(string $name, string $type, mixed $value1, mixed $value2 = null): array
{
    return match ($type) {
        'no changes', 'removed', 'added', 'nested' => ['name' => $name, 'type' => $type, 'value' => $value1],
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
        if (!array_key_exists($key, $tree2)){
            return ['name' => $key, 'type' => 'removed', 'value' => $tree1[$key]];
        } elseif (!array_key_exists($key, $tree1)) {
            return ['name' => $key, 'type' => 'added', 'value' => $tree2[$key]];
        } elseif (is_array($tree1[$key]) && is_array($tree2[$key])) {
            return ['name' => $key, 'type' => 'nested', 'children' => compareTrees($tree1[$key], $tree2[$key])];
        } elseif ($tree1[$key] === $tree2[$key]) {
            return ['name' => $key, 'type' => 'no changes', 'value' => $tree1[$key]];
        } else {
            return ['name' => $key, 'type' => 'changed', 'value' => $tree1[$key], 'value2' =>$tree2[$key]];
        }
    },
        $allKeys);
}
