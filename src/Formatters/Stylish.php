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

function makeString(int $depth, string $name, string $value, string $type = 'string', string $id = '    '): string
{
    $indentation = '    ';
    $prefixIndentation = str_repeat($indentation, $depth);

    if ($type === 'array') {
        return $prefixIndentation . $id . $name . ": {\n" . $value . "\n" . $prefixIndentation . $indentation . "}";
    }
    return $prefixIndentation . $id . $name . ': ' . $value;
}

function formatArray(array $value, int $depth): string
{
    $valueKeys = array_keys($value);
    $result =  array_map(function ($key) use ($value, $depth): string {
        if (is_array($value[$key])) {
            $newValue = $value[$key];
            return makeString($depth, $key, formatArray($newValue, $depth + 1), 'array');
        }
        return makeString($depth, $key, formatValue($value[$key]));
    }, $valueKeys);
    return implode("\n", $result);
}

function stylishHelper(array $tree, int $depth = 0): array
{
    $beforeId = '  - ';
    $afterId = '  + ';

    return array_map(function ($node) use ($beforeId, $afterId, $depth): string {
        $noChangesValue = $node['noChanges'];
        $name = getName($node);
        if (isValueSet($noChangesValue)) {
            $check = is_array($noChangesValue) ?
                stylishHelper($noChangesValue, $depth + 1) : formatValue($noChangesValue);
            $tmp = is_array($check) ? implode("\n", $check) : $check;
            return is_array($check) ?
                makeString($depth, $name, $tmp, 'array') : makeString($depth, $name, $tmp);
        }
        $valueBefore = $node['before'];
        $valueAfter = $node['after'];
        if (isValueSet($valueBefore)) {
            $checkBefore = is_array($valueBefore)
                ? formatArray($valueBefore, $depth + 1) : formatValue($valueBefore);
            $resultBefore = is_array($valueBefore)
                ? makeString($depth, $name, $checkBefore, 'array', $beforeId)
                : makeString($depth, $name, $checkBefore, '', $beforeId);
            if (isValueSet($valueAfter)) {
                $checkAfter = is_array($valueAfter)
                    ? formatArray($valueAfter, $depth + 1) : formatValue($valueAfter);
                $resultAfter = is_array($valueAfter)
                    ? makeString($depth, $name, $checkAfter, 'array', $afterId)
                    : makeString($depth, $name, $checkAfter, '', $afterId);
                return $resultBefore . "\n" . $resultAfter;
            };
            return $resultBefore;
        }
        if (isValueSet($valueAfter)) {
            $check = is_array($valueAfter)
                ? formatArray($valueAfter, $depth + 1) : formatValue($valueAfter);
            return is_array($valueAfter)
                ? makeString($depth, $name, $check, 'array', $afterId)
                : makeString($depth, $name, $check, '', $afterId);
        }
        return '';
    }, $tree);
}


function stylish(array $array): string
{
    $helped = stylishHelper($array);
    $string = implode("\n", $helped);
    return "{\n" . $string . "\n}";
}
