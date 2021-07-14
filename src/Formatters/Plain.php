<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Plain;

use function Functional\flatten;
use function Gendiff\CompareFiles\getName;
use function Gendiff\CompareFiles\getType;
use function Gendiff\CompareFiles\getChildren;
use function Gendiff\CompareFiles\isNode;
use function Gendiff\CompareFiles\getValue;
use function Gendiff\CompareFiles\getValue2;

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
        } elseif ($type === 'changed') {
            $value1 = checkValue(getValue($node));
            $value2 = checkValue(getValue2($node));
            return  "Property '$path' was updated. From $value1 to $value2";
        } elseif ($type === 'removed') {
            return  "Property '$path' was removed";
        } elseif ($type === 'added') {
            $addedValue = checkValue(getValue($node));
            return  "Property '$path' was added with value: $addedValue";
        };
        return '';
    }, $tree);
    $filtered = array_filter(flatten($result));
    return implode("\n", $filtered);
}
