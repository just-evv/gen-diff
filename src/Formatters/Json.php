<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Json;

use _HumbugBoxec8571fe8659\Nette\Neon\Exception;
use function Functional\flatten;
use function Gendiff\DiffNode\getName;
use function Gendiff\DiffNode\isValueSet;
use function PHPUnit\Framework\throwException;

function jsonHelper(array $tree): array
{
    $result = array_map(function ($node) {
        $noChangesValue = $node['noChanges'];
        $name = getName($node);
        if (isValueSet($noChangesValue)) {
            return [$name =>  is_array($noChangesValue) ? jsonHelper($noChangesValue) : $noChangesValue];
        }
        $valueBefore = $node['before'];
        $valueAfter = $node['after'];
        if (isValueSet($valueBefore) && isValueSet($valueAfter)) {
            return [$name => ['first file' => $valueBefore, 'second file' => $valueAfter]];
        } elseif (isValueSet($valueBefore)) {
            return [$name => ['first file' => $valueBefore]];
        } elseif (isValueSet($valueAfter)) {
            return [$name => ['second file' => $valueAfter]];
        };
        return '';
    }, $tree);
    return array_merge(...$result);
}

function json(array $tree): string
{
    $result = json_encode(jsonHelper($tree));
    if ($result === false) {
        throw new Exception("something went wrong");
    }
    return $result;
}
