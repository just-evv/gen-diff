<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Stylish;

use function Gendiff\CompareFiles\getName;
use function Gendiff\CompareFiles\getType;
use function Gendiff\CompareFiles\getChildren;
use function Gendiff\CompareFiles\isNode;

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
        if ($type === 'no changes') {
            $value = isNode($node) ? stylishCreator(getChildren($node), $depth + 1) : $node['value'];
            $valueToString = is_array($value) ? implode("\n", $value) : formatValue($value);
            return is_array($value)
                ? makeString($depth, $name, $valueToString, 'array')
                : makeString($depth, $name, $value);
        }

        if ($type === 'changed') {
            $valueRemoved = $node['removed'];
            $valueToString = is_array($valueRemoved)
                ? formatArray($valueRemoved, $depth + 1)
                : formatValue($valueRemoved);

            $valueAdded = $node['added'];
            $valueToString2 = is_array($valueAdded)
                ? formatArray($valueAdded, $depth + 1)
                : formatValue($valueAdded);

            $result1 = is_array($valueRemoved)
                ? makeString($depth, $name, $valueToString, 'array', $removedId)
                : makeString($depth, $name, $valueToString, '', $removedId);
            $result2 = is_array($valueAdded)
                ? makeString($depth, $name, $valueToString2, 'array', $addedId)
                : makeString($depth, $name, $valueToString2, '', $addedId);
            return $result1 . "\n" . $result2;
        } elseif ($type === 'removed') {
            $valueRemoved = $node['removed'];
            $valueToString = is_array($valueRemoved)
                ? formatArray($valueRemoved, $depth + 1)
                : formatValue($valueRemoved);
            return is_array($valueRemoved)
                ? makeString($depth, $name, $valueToString, 'array', $removedId)
                : makeString($depth, $name, $valueToString, '', $removedId);
        } elseif ($type === 'added') {
            $valueAdded = $node['added'];
            $valueToString = is_array($valueAdded) ? formatArray($valueAdded, $depth + 1) : formatValue($valueAdded);
            return is_array($valueAdded)
                ? makeString($depth, $name, $valueToString, 'array', $addedId)
                : makeString($depth, $name, $valueToString, '', $addedId);
        }
        return '';
    }, $tree);
}

function stylish(array $tree): string
{
    $stylishTree = stylishCreator($tree);
    $treeToString = implode("\n", $stylishTree);
    return "{\n" . $treeToString . "\n}";
}
