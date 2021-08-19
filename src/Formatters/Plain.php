<?php

declare(strict_types=1);

namespace Differ\Formatter\Plain;

use Exception;

use function Functional\flatten;
use function Differ\DiffGenerator\getName;
use function Differ\DiffGenerator\getType;
use function Differ\DiffGenerator\getChildren;
use function Differ\DiffGenerator\getValue;
use function Differ\DiffGenerator\getValue2;

function formatValue(mixed $value): string
{
    return match (true) {
        is_array($value) => '[complex value]',
        is_bool($value) => $value ? 'true' : 'false',
        is_null($value) => 'null',
        is_integer($value) => (string) $value,
        default => "'$value'",
    };
}

/**
 * @throws Exception
 */
function genString(string $type, array $node, string $path): string
{
    switch ($type) {
        case 'no changes':
            return '';
        case 'changed':
            $value1 = formatValue(getValue($node));
            $value2 = formatValue(getValue2($node));
            return  "Property '$path' was updated. From $value1 to $value2";
        case 'removed':
            return  "Property '$path' was removed";
        case 'added':
            $addedValue = formatValue(getValue($node));
            return  "Property '$path' was added with value: $addedValue";
        default:
            throw new Exception('type undefined');
    }
}

function genPlain(array $tree, string $rootPath = null): string
{
    $result =  array_map(function ($node) use ($rootPath): string {
        $name = getName($node);
        $path = isset($rootPath) ? implode('.', [$rootPath, $name]) : $name;
        $type = getType($node);
        if ($type === 'nested') {
            return genPlain(getChildren($node), $path);
        } else {
            return genString($type, $node, $path);
        }
    }, $tree);
    $filtered = array_filter(flatten($result));
    return implode("\n", $filtered);
}
