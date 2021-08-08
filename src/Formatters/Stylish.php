<?php

declare(strict_types=1);

namespace Differ\Formatter\Stylish;

use Exception;

use function Differ\DiffGenerator\getName;
use function Differ\DiffGenerator\getType;
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

function genStylish(array $tree, int $depth = 0): string
{
    $res = array_map(function ($node) use ($depth): string {
        $name = getName($node);
        $type = getType($node);
        if ($type === 'nested') {
            $value = genStylish(getValue($node), $depth + 1);
            return makeString($depth, $name, $value);
        }

        $value = getValue($node);

        if ($type === 'changed') {
            $value2 = getValue2($node);
            $prefix1 = '  - ';
            $prefix2 = '  + ';
            $result1 = makeString($depth, $name, $value, $prefix1);
            $result2 = makeString($depth, $name, $value2, $prefix2);

            return $result1 . "\n" . $result2;
        }

        $prefix = match ($type) {
            'added' => '  + ',
            'removed' => '  - ',
            'no changes' => '    ',
            default => throw new Exception('undefined type'),
        };

        return  makeString($depth, $name, $value, $prefix);
    }, $tree);

    return formatArrayWithOpenCloseBraces($res, $depth);
}
