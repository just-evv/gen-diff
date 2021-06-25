<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Stylish;

use function Gendiff\DiffNode\getName;
use function Gendiff\DiffNode\isValueSet;

function formatValue($value): string
{
    if (gettype($value) !== "string") {
        return json_encode($value);
    }
    return $value;
}

function makePrefix(int $depth, string $name, string $id = '    '): string
{
    $indentation = '    ';
    $prefixIndentation = str_repeat($indentation, $depth);
    return $prefixIndentation . $id . $name . ': ';
}

function formatForArray(int $depth, string $name, $value, string $id = '    '): string
{
    $indentation = '    ';
    $prefixIndentation = str_repeat($indentation, $depth);
    return $prefixIndentation . $id . $name . ": {\n" . $value . "\n" . $prefixIndentation . $indentation . "}";
}

function formatArray(array $value, $depth)
{
    $valueKeys = array_keys($value);
    $result =  array_map(function ($key) use ($value, $depth) {
        if (is_array($value[$key])) {
            $newValue = $value[$key];
            return formatForArray($depth, $key, formatArray($newValue, $depth + 1));
        }
        return makePrefix($depth, $key) . formatValue($value[$key]);
    }, $valueKeys);
    return implode("\n", $result);
}

function stylishHelper(array $tree, $depth = 0)
{
    $beforeId = '  - ';
    $afterId = '  + ';

    $result = array_reduce($tree, function ($acc, $node) use ($beforeId, $afterId, $depth) {
        $noChangesValue = $node['noChanges'];
        $name = getName($node);
        if (isValueSet($noChangesValue)) {
            $check = is_array($noChangesValue) ?
                stylishHelper($noChangesValue, $depth + 1) : formatValue($noChangesValue);
            $tmp = is_array($check) ? implode("\n", $check) : $check;
            $acc[] = is_array($check) ?
                formatForArray($depth, $name, $tmp) : makePrefix($depth, $name) . $tmp;
        }
         $valueBefore = $node['before'];
        $valueAfter = $node['after'];
        if (isValueSet($valueBefore)) {
            $check = is_array($valueBefore) ?
                formatArray($valueBefore, $depth + 1) : formatValue($valueBefore);
            $acc[] = is_array($valueBefore) ?
                formatForArray($depth, $name, $check, $beforeId) : makePrefix($depth, $name, $beforeId) . $check;
        }
        if (isValueSet($valueAfter)) {
            $check = is_array($valueAfter) ?
                formatArray($valueAfter, $depth + 1) : formatValue($valueAfter);
            $acc[] = is_array($valueAfter) ?
                formatForArray($depth, $name, $check, $afterId) : makePrefix($depth, $name, $afterId) . $check;
        }
        return $acc;
    }, []);
    return $result;
}

function stylish(array $array)
{
    $helped = stylishHelper($array);
    $string = implode("\n", $helped);
    $formatted = "{\n" . $string . "\n}";
    return $formatted;
}
