<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Json;

use function Gendiff\DiffNode\getAfter;
use function Gendiff\DiffNode\getBefore;
use function Gendiff\DiffNode\getName;
use function Gendiff\DiffNode\getNoChanges;
use function Gendiff\DiffNode\isValueSet;

function jsonHelper(array $tree): array
{
    return array_reduce($tree, function ($acc, $node) {
        $noChangesValue = $node['noChanges'];
        $name = getName($node);
        if (isValueSet($noChangesValue)) {
            $acc[$name] = is_array($noChangesValue) ? jsonHelper($noChangesValue) : $noChangesValue;
        }
        $valueBefore = $node['before'];
        $valueAfter = $node['after'];
        if (isValueSet($valueBefore) && isValueSet($valueAfter)) {
            $acc[$name] = ['first file' => $valueBefore, 'second file' => $valueAfter];
        } elseif (isValueSet($valueBefore)) {
            $acc[$name] = ['first file' => $valueBefore];
        } elseif (isValueSet($valueAfter)) {
            $acc[$name] = ['second file' => $valueAfter];
        };
        return $acc;
    }, []);
}

function json($array): string
{
    return json_encode(jsonHelper($array));
}
