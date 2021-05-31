<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Json;

use function Gendiff\CompareFiles\getAfter;
use function Gendiff\CompareFiles\getBefore;
use function Gendiff\CompareFiles\getNoChanges;
use function Gendiff\CompareFiles\isValueSet;

function json($array): string
{
    $result = [];

    foreach ($array as $key => $value) {
        $newValue = getNoChanges($value);

        if (isValueSet($newValue)){
            $result[$key] = is_array($newValue) ? json($newValue) : $newValue;
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
    print_r($result);
    return json_encode($result);
}
