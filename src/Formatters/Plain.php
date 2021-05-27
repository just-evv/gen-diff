<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Plain;

use function Gendiff\CompareFiles\getAfter;
use function Gendiff\CompareFiles\getBefore;
use function Gendiff\CompareFiles\getNoChanges;
use function Gendiff\CompareFiles\isValueSet;

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

function plain($array, $rootPath = ''): string
{
    $result = [];

    foreach ($array as $key => $value) {
        $path = $rootPath . $key;
        $noChangesValue = getNoChanges($value);
        if (isValueSet($noChangesValue) && is_array($noChangesValue)) {
            $path .= '.';
            $result[] = plain(getNoChanges($value), $path);
        }
        $valueBefore = getBefore($value);
        $valueAfter = getAfter($value);
        if (isValueSet($valueBefore) && isValueSet($valueAfter)) {
            $value1 = checkValue($valueBefore);
            $value2 = checkValue($valueAfter);
            $result[] = "Property '$path' was updated. From $value1 to $value2";
        } elseif (isValueSet($valueBefore)) {
            $result[] = "Property '$path' was removed";
        } elseif (isValueSet($valueAfter)) {
            $addedValue = checkValue($valueAfter);
            $result[] = "Property '$path' was added with value: $addedValue";
        }
    }
    return implode("\n", $result);
}
