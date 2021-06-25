<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Plain;

use function Functional\flatten;
use function Gendiff\DiffNode\getName;
use function Gendiff\DiffNode\isValueSet;

function formatValue($value): string
{
    if (gettype($value) !== "string") {
        return json_encode($value);
    }
    return "'$value'";
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
        $noChanges = $node['noChanges'];
        if (isValueSet($noChanges) && is_array($noChanges)) {
            return plain($noChanges, $path);
        }
        $valueBefore = $node['before'];
        $valueAfter = $node['after'];
        //$path = implode('.', $rootPath);
        if (isValueSet($valueBefore) && isValueSet($valueAfter)) {
            $value1 = checkValue($valueBefore);
            $value2 = checkValue($valueAfter);
            return  "Property '$path' was updated. From $value1 to $value2";
        } elseif (isValueSet($valueBefore)) {
            return  "Property '$path' was removed";
        } elseif (isValueSet($valueAfter)) {
            $addedValue = checkValue($valueAfter);
            return  "Property '$path' was added with value: $addedValue";
        };
        return '';
    }, $tree);
    $filtered = array_filter(flatten($result));
    return implode("\n", $filtered);
}
