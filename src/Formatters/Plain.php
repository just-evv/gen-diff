<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Plain;

use function Functional\flatten;
use function Gendiff\CompareFiles\getName;
use function Gendiff\CompareFiles\getType;
use function Gendiff\CompareFiles\getChildren;
use function Gendiff\CompareFiles\isNode;
use function Gendiff\CompareFiles\getRemoved;
use function Gendiff\CompareFiles\getAdded;

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

function plain(array $tree, string $rootPath = null): string
{
    $result =  array_map(function ($node) use ($rootPath): string {
        $name = getName($node);
        $path = isset($rootPath) ? implode('.', [$rootPath, $name]) : $name;
        $type = getType($node);
        $result = '';
        if ($type === 'no changes') {
            if (isNode($node)) {
                $result =  plain(getChildren($node), $path);
            }
        } elseif ($type === 'changed') {
            $value1 = checkValue(getRemoved($node));
            $value2 = checkValue(getAdded($node));
            $result =   "Property '$path' was updated. From $value1 to $value2";
        } elseif ($type === 'removed') {
            $result =   "Property '$path' was removed";
        } elseif ($type === 'added') {
            $addedValue = checkValue(getAdded($node));
            $result =  "Property '$path' was added with value: $addedValue";
        };
        return $result;
    }, $tree);
    $filtered = array_filter(flatten($result));
    return implode("\n", $filtered);
}
