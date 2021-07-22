<?php

declare(strict_types=1);

namespace Differ\Formatter\Stylish;

use Exception;

use function Differ\DiffGenerator\getName;
use function Differ\DiffGenerator\getType;
use function Differ\DiffGenerator\getChildren;
use function Differ\DiffGenerator\getValue;
use function Differ\DiffGenerator\getValue2;

function formatValue(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return is_null($value) ? 'null' :  (string) $value;
}

/**
 * @throws Exception
 */
function getValuePrefix(string $type): mixed
{
    $prefixRemovedValue = '  - ';
    $prefixAddedValue = '  + ';
    $prefixNoChanges = '    ';

    if ($type === 'removed') {
        return $prefixRemovedValue;
    } elseif ($type === 'added') {
        return $prefixAddedValue;
    } elseif ($type === 'changed') {
        return [$prefixRemovedValue, $prefixAddedValue];
    } elseif ($type === 'no changes') {
        return $prefixNoChanges;
    }else {
        throw new Exception('undefined type');
    }
}
/*
function makeString(array $requiredArguments, string $prefix = '    '): string
{
    [$depth, $name, $value, $isValueArray] = $requiredArguments;
    $indentation = '    ';
    $prefixIndentation = str_repeat($indentation, $depth);

    if ($isValueArray === true) {
        return $prefixIndentation . $prefix . $name . ": {\n" . $value . "\n" . $prefixIndentation . $indentation . "}";
    }
    return $prefixIndentation . $prefix . $name . ': ' . $value;
}
*/
function formatArray(array $value, int $depth): string
{
    $valueKeys = array_keys($value);
    $result =  array_map(function ($key) use ($value, $depth): string {
        $indentation = '    ';
        $prefixIndentation = str_repeat($indentation, $depth);
        if (is_array($value[$key])) {
            $newValue = $value[$key];
            return $prefixIndentation . $indentation . $key . ": {\n" . formatArray($newValue, $depth + 1) . "\n" . $prefixIndentation . $indentation . "}";
                //makeString($depth, $key, formatArray($newValue, $depth + 1));
        }
        return makeString($depth, $key, formatValue($value[$key]));
    }, $valueKeys);
    return implode("\n", $result);
}

function makeString($depth, $name, $value, string $prefix = '    ')
{
    $prefixIndentation = str_repeat('    ', $depth);

    if (!is_array($value)) {
        return $prefixIndentation . $prefix . $name . ': ' . formatValue($value);
    } else {
        $valueKeys = array_keys($value);

        $result =  array_map(function ($key) use ($value, $depth): string {
            $prefixIndentation = str_repeat('    ', $depth + 1);
            if (is_array($value[$key])) {
                $newValue = $value[$key];
                return $prefixIndentation . $key . ": {\n" . formatArray($newValue, $depth + 1) . "\n" . $prefixIndentation . "}";
            }
            return "$prefixIndentation $key: " . formatValue($value[$key]);
        }, $valueKeys);

        return implode("\n", $result);
    }
}
/*
function makeString($depth, $name, $value, string $prefix = '    ')
{

    $newValue = is_array($value) ? formatArray($value, $depth + 1) : formatValue($value);
    $indentation = '    ';
    $prefixIndentation = str_repeat($indentation, $depth);
    if (is_array($value)) {
        return $prefixIndentation . $prefix . $name . ": {\n" . $newValue . "\n" . $prefixIndentation . $indentation . "}";
    }
    return $prefixIndentation . $prefix . $name . ': ' . $newValue;
}

*/
function stylishCreator(array $tree, int $depth = 0): array
{
    return array_map(function ($node) use ($depth): string {
        $name = getName($node);
        $type = getType($node);
        if ($type === 'nested') {
            $children = getChildren($node);
            $result = stylishCreator($children, $depth + 1);

            return makeString($depth, $name, implode('\n', $result));
        }

        $value = getValue($node);
        //$valueToString = is_array($value) ? formatArray($value, $depth + 1) : formatValue($value);
        //$argumentsForMakeString = [$depth, $name, $valueToString, is_array($value)];

        if ($type === 'changed') {
            $value2 = getValue2($node);
            //$valueToString2 = is_array($value2) ? formatArray($value2, $depth + 1) : formatValue($value2);
            //$argumentsForMakeString2 = [$depth, $name, $valueToString2, is_array($value2)];
            $id = getValuePrefix($type);
            $result1 = makeString($depth, $name, $value, $id[0]);
            $result2 = makeString($depth, $name, $value2, $id[1]);
            return $result1 . "\n" . $result2;
        } else {
            return makeString($depth, $name, $value, getValuePrefix($type));
        }
    }, $tree);
}

function genStylish(array $tree): string
{
    $stylishTree = stylishCreator($tree);
    $treeToString = implode("\n", $stylishTree);
    return "{\n" . $treeToString . "\n}";
}
