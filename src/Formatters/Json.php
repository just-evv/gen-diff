<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Json;

use Exception;

use function Gendiff\CompareFiles\getName;
use function Gendiff\CompareFiles\getType;
use function Gendiff\CompareFiles\getChildren;
use function Gendiff\CompareFiles\getValue1;
use function Gendiff\CompareFiles\getValue2;

function jsonHelper(array $tree)
{
    $result = array_map(function ($node) {

        $name = getName($node);
        $type = getType($node);
        $children = getChildren($node);

        if ($type === 'no changes' ) {
            return [$name => !empty($children) ? jsonHelper($children) : getValue1($node)];
        } elseif ($type === 'changed') {
            return [$name => ['first file' => getValue1($node), 'second file' => getValue2($node)]];
        } elseif ($type === 'removed') {
            return [$name => ['first file' => getValue1($node)]];
        } elseif ($type === 'added') {
            return [$name => ['second file' => getValue1($node)]];
        }
        return [];
    }, $tree);
    return array_merge(...$result);
}

function json(array $tree)
{
    $result = json_encode(jsonHelper($tree));
    if ($result === false) {
        throw new Exception("something went wrong");
    }
    return $result;
}
