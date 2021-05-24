<?php

declare(strict_types=1);

namespace Gendiff\Formatter;

function spacer($node, $depth = 1)
{
    $identation = '  ';
    $currentIdentation = str_repeat($identation, $depth);

    $result = [];

    foreach ($node as $key => $value) {
        $newKey = $currentIdentation . $key;
        $newValue = is_array($value) ? spacer($value, $depth + 1) : $value;
        $result[$newKey] = $newValue;
    }

    return $result;
}

function formatValue($value): string
{
    if (gettype($value) !== "string") {
        return json_encode($value);
    }
    return $value;
}


function stylish(array $node, $depth = 2): string
{
    $result = '';
    $identation = '  ';
    $currentIdentation = str_repeat($identation, $depth);

    foreach ($node as $key => $value) {
        $newKey = $currentIdentation . $key;

        $newValue = is_array($value) ? stylish($value, $depth + 1) : formatValue($value);

        $currentString = $newKey . ': ';

        if (is_array($value)) {
            $currentString .= $newValue . "\n";
        } else {
            $currentString .= formatValue($value) . "\n";
        }
        $result .= $currentString;
    };
    return "{\n" . $result . str_repeat($identation, $depth - 2) . '}';
}
