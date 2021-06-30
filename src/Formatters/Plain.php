<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Plain;

use function Functional\flatten;
use function Gendiff\CompareFiles\getName;
use function Gendiff\CompareFiles\getType;
use function Gendiff\CompareFiles\getChildren;

function formatValue($value): string
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

function checkValue($value): string
{
    return is_array($value) ? '[complex value]' : formatValue($value);
}

function plain(array $tree, string $rootPath = null): string
{
    $result =  array_map(function ($node) use ($rootPath): string {
        $name = getName($node);
        $path = isset($rootPath) ? implode('.', [$rootPath, $name]) : $name;
        $type = getType($node);
        $children = getChildren($node);
        if ($type === 'no changes' && !empty($children)) {
                return plain($children, $path);
        } elseif ($type === 'changed') {
            $value1 = checkValue($node['value1']);
            $value2 = checkValue($node['value2']);
            return  "Property '$path' was updated. From $value1 to $value2";
        } elseif ($type === 'removed') {
            return  "Property '$path' was removed";
        } elseif ($type === 'added') {
            $addedValue = checkValue($node['value1']);
            return  "Property '$path' was added with value: $addedValue";
        };
        return '';
    }, $tree);
    $filtered = array_filter(flatten($result));
    return implode("\n", $filtered);
}
