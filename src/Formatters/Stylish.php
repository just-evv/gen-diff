<?php

declare(strict_types=1);

namespace Differ\Formatter\Stylish;

use Exception;

use function Differ\DiffGenerator\getName;
use function Differ\DiffGenerator\getType;
use function Differ\DiffGenerator\getChildren;
use function Differ\DiffGenerator\getValue;
use function Differ\DiffGenerator\getValue2;

function formatValue(mixed $value, int $depth): string
{
    if (is_array($value)) {
        return formatArray($value, $depth + 1);
    } elseif (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return is_null($value) ? 'null' :  (string) $value;
}

/**
 * @throws Exception
 */
function getPrefix(string $type): mixed
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
    } else {
        throw new Exception('undefined type');
    }
}

function formatArrayWithOpenCloseBraces(array $result, int $depth): string
{
    $indentation = '    ';

    return "{\n" . implode("\n", $result) . "\n" . str_repeat($indentation, $depth) . "}";
}

function formatArray(array $value, int $depth): string
{
    $valueKeys = array_keys($value);
    $result =  array_map(function ($key) use ($value, $depth): string {
        return makeString($depth, $key, $value[$key]);
    }, $valueKeys);

    return formatArrayWithOpenCloseBraces($result, $depth);
}


function makeString(int $depth, string $name, mixed $value, string $prefix = '    '): string
{
    $formattedValue = formatValue($value, $depth);

    $indentation = '    ';
    $prefixIndentation = str_repeat($indentation, $depth);

    return $prefixIndentation . $prefix . $name . ': ' . $formattedValue;
}

/**
 * @throws Exception
 */
function formatNode(array $node, int $depth): string
{
    $name = getName($node);
    $value = getValue($node);
    $type = getType($node);
    $prefix = getPrefix($type);

    if ($type === 'changed') {
        $value2 = getValue2($node);
        $result1 = makeString($depth, $name, $value, $prefix[0]);
        $result2 = makeString($depth, $name, $value2, $prefix[1]);

        return $result1 . "\n" . $result2;
    }
    return  makeString($depth, $name, $value, $prefix);
}

function genStylish(array $tree, int $depth = 0): string
{
    $res = array_map(function ($node) use ($depth): string {
        $name = getName($node);
        $type = getType($node);
        if ($type === 'nested') {
            $value = genStylish(getChildren($node), $depth + 1);
            return makeString($depth, $name, $value);
        }

        return FormatNode($node, $depth);
    }, $tree);

    return formatArrayWithOpenCloseBraces($res, $depth);
}
