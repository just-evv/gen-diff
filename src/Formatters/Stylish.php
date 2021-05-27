<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Stylish;

use function Gendiff\CompareFiles\getAfter;
use function Gendiff\CompareFiles\getBefore;
use function Gendiff\CompareFiles\getNoChanges;
use function Gendiff\CompareFiles\isValueSet;

function formatValue($value): string
{
    if (gettype($value) !== "string") {
        return json_encode($value);
    }
    return $value;
}

function renderValue($node, $depth): string
{
    $result = "{\n";
    $indentation = '    ';
    $prefixIndentation = str_repeat($indentation, $depth);

    foreach ($node as $key => $value) {
        $result .= $prefixIndentation . $indentation . $key . ': ';
        $result .= is_array($value) ? renderValue($value, $depth + 1) : formatValue($value);
        $result .= "\n";
    }
    return $result . $prefixIndentation . '}';
}

function stylish(array $node, $depth = 0): string
{
    $result = "{\n";
    $indentation = '    ';
    $beforeId = '  - ';
    $afterId = '  + ';

    $prefixIndentation = str_repeat($indentation, $depth);

    foreach ($node as $key => $value) {
        $noChangesValue = getNoChanges($value);

        if (isValueSet($noChangesValue)) {
            $result .= $prefixIndentation . $indentation . $key . ': ';
            $result .= is_array($noChangesValue) ? stylish($noChangesValue, $depth + 1) : formatValue($noChangesValue);
        } else {
            $beforeValue = getBefore($value);
            $afterValue = getAfter($value);

            if (isValueSet($beforeValue)) {
                $result .= $prefixIndentation . $beforeId . $key . ': ';
                $result .= is_array($beforeValue) ? renderValue($beforeValue, $depth + 1) : formatValue($beforeValue);
            }

            if (isValueSet($afterValue)) {
                if (isValueSet($beforeValue)) {
                    $result .= "\n";
                }
                $result .= $prefixIndentation . $afterId . $key . ': ';
                $result .= is_array($afterValue) ? renderValue($afterValue, $depth + 1) : formatValue($afterValue);
            }
        }
        $result .= "\n";
    };
    return $result . $prefixIndentation . '}';
}
