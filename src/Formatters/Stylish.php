<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Stylish;

use Exception;
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
    }
    return is_null($value) ? 'null' :  (string) $value;
}

function getValueId(string $type): mixed
{
    $idRemovedValue = '  - ';
    $idAddedValue = '  + ';

    if ($type === 'removed') {
        return $idRemovedValue;
    } elseif ($type === 'added') {
        return $idAddedValue;
    } elseif ($type === 'changed') {
        return [$idRemovedValue, $idAddedValue];
    } else {
        throw new Exception('undefined type');
    }
}

function makeString(array $requiredArguments, string $id = '    '): string
{
    [$depth, $name, $value, $isValueArray] = $requiredArguments;
    $indentation = '    ';
    $prefixIndentation = str_repeat($indentation, $depth);

    if ($isValueArray === true) {
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
            return makeString([$depth, $key, formatArray($newValue, $depth + 1), true]);
        }
        return makeString([$depth, $key, formatValue($value[$key]), false]);
    }, $valueKeys);
    return implode("\n", $result);
}

function stylishCreator(array $tree, int $depth = 0): array
{
    return array_map(function ($node) use ($depth): string {
        $name = getName($node);
        $type = getType($node);
        if ($type === 'no changes') {
            $value = isNode($node) ? stylishCreator(getChildren($node), $depth + 1) : getValue($node);
            $valueToString = is_array($value) ? implode("\n", $value) : formatValue($value);
            $argumentsForMakeString = [$depth, $name, $valueToString, is_array($value)];
            return makeString($argumentsForMakeString);
        }

        $value = getValue($node);
        $valueToString = is_array($value) ? formatArray($value, $depth + 1) : formatValue($value);
        $argumentsForMakeString = [$depth, $name, $valueToString, is_array($value)];

        if ($type === 'changed') {
            $value2 = getValue2($node);
            $valueToString2 = is_array($value2) ? formatArray($value2, $depth + 1) : formatValue($value2);
            $argumentsForMakeString2 = [$depth, $name, $valueToString2, is_array($value2)];
            $id = getValueId($type);
            $result1 = makeString($argumentsForMakeString, $id[0]);
            $result2 = makeString($argumentsForMakeString2, $id[1]);
            return $result1 . "\n" . $result2;
        } else {
            return makeString($argumentsForMakeString, getValueId($type));
        }
    }, $tree);
}

function genStylish(array $tree): string
{
    $stylishTree = stylishCreator($tree);
    $treeToString = implode("\n", $stylishTree);
    return "{\n" . $treeToString . "\n}";
}
