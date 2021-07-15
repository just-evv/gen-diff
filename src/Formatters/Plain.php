<?php

declare(strict_types=1);

namespace Differ\Formatter\Plain;

use Exception;

use function Functional\flatten;
use function Differ\DiffGenerator\getName;
use function Differ\DiffGenerator\getType;
use function Differ\DiffGenerator\getChildren;
use function Differ\DiffGenerator\isNode;
use function Differ\DiffGenerator\getValue;
use function Differ\DiffGenerator\getValue2;

function formatValue(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    } elseif (is_null($value)) {
        return 'null';
    } elseif (is_integer($value)) {
        return (string) $value;
    } else {
        return "'$value'";
    }
}

function checkValue(mixed $value): string
{
    return is_array($value) ? '[complex value]' : formatValue($value);
}

function genString(string $type, array $node, string $path): string
{
    if ($type === 'changed') {
        $value1 = checkValue(getValue($node));
        $value2 = checkValue(getValue2($node));
        return  "Property '$path' was updated. From $value1 to $value2";
    } elseif ($type === 'removed') {
        return  "Property '$path' was removed";
    } elseif ($type === 'added') {
        $addedValue = checkValue(getValue($node));
        return  "Property '$path' was added with value: $addedValue";
    } else {
        throw new Exception('type undefined');
    }
}

function genPlain(array $tree, string $rootPath = null): string
{
    $result =  array_map(function ($node) use ($rootPath): string {
        $name = getName($node);
        $path = isset($rootPath) ? implode('.', [$rootPath, $name]) : $name;
        $type = getType($node);
        if ($type === 'no changes') {
            if (isNode($node)) {
                return genPlain(getChildren($node), $path);
            }
        } else {
            return genString($type, $node, $path);
        };
        return '';
    }, $tree);
    $filtered = array_filter(flatten($result));
    return implode("\n", $filtered);
}
