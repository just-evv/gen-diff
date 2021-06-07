<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Json;

use function Gendiff\DiffNode\getAfter;
use function Gendiff\DiffNode\getBefore;
use function Gendiff\DiffNode\getNoChanges;
use function Gendiff\DiffNode\isValueSet;

function jsonHelper($array): array
{
    $result = [];

    foreach ($array as $key => $value) {
        $newValue = getNoChanges($value);

        if (isValueSet($newValue)) {
            $result[$key] = is_array($newValue) ? jsonHelper($newValue) : $newValue;
        };
        $valueBefore = getBefore($value);
        $valueAfter = getAfter($value);
        if (isValueSet($valueBefore) && isValueSet($valueAfter)) {
            $result[$key] = ['first file' => $valueBefore, 'second file' => $valueAfter]  ;
        } elseif (isValueSet($valueBefore)) {
            $result[$key]['first file'] = $valueBefore;
        } elseif (isValueSet($valueAfter)) {
            $result[$key]['second file'] = $valueAfter;
        }
    }
    return $result;
}

function json($array): string
{
    return json_encode(jsonHelper($array));
}
