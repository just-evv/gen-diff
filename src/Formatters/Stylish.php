<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Stylish;

use function Gendiff\CompareFiles\getName;
use function Gendiff\CompareFiles\getType;
use function Gendiff\CompareFiles\getChildren;

function formatValue($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return is_null($value) ? 'null' :  (string) $value;
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

function stylishCreator(array $tree, int $depth = 0): array
{
    $removedId = '  - ';
    $addedId = '  + ';

    return array_map(function ($node) use ($removedId, $addedId, $depth): string {
        $name = getName($node);
        $type = getType($node);
        $children = getChildren($node);
        if ($type === 'no changes') {
            $value = !empty($children) ? stylishCreator($children, $depth + 1) : $node['value1'];
            $valueToString = is_array($value) ? implode("\n", $value) : formatValue($value);
            return is_array($value) ? makeString($depth, $name, $valueToString, 'array') : makeString($depth, $name, $value);
        }

        $value = $node['value1'];
        $valueToString = is_array($value) ? formatArray($value, $depth + 1) : formatValue($value);

        if ($type === 'changed') {
            $value2 = $node['value2'];
            $valueToString2 = is_array($value2) ? formatArray($value2, $depth + 1) : formatValue($value2);

            $result1 = is_array($value)
                ? makeString($depth, $name, $valueToString, 'array', $removedId)
                : makeString($depth, $name, $valueToString, '', $removedId);
            $result2 = is_array($value2)
                ? makeString($depth, $name, $valueToString2, 'array', $addedId)
                : makeString($depth, $name, $valueToString2, '', $addedId);
            return $result1 . "\n" . $result2;
        } elseif ($type === 'removed') {
            return is_array($value)
                ? makeString($depth, $name, $valueToString, 'array', $removedId)
                : makeString($depth, $name, $valueToString, '', $removedId);
        } elseif ($type === 'added') {
            return is_array($value)
                ? makeString($depth, $name, $valueToString, 'array', $addedId)
                : makeString($depth, $name, $valueToString, '', $addedId);
        }
        return '';
    }, $tree);
}

function stylish(array $tree): string
{
    $stylishTree = ['{', ...stylishCreator($tree)];
    $stylishTree[] = '}';
    return implode("\n", $stylishTree);
}
