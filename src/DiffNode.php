<?php

declare(strict_types=1);

namespace Gendiff\DiffNode;

function createNode($name, $noChanges, $before = [], $after = []): array
{
    return ['name' => $name, 'noChanges' => $noChanges, 'before' => $before, 'after' => $after];
}

function getName(array $node): string
{
    return $node['name'];
}

function isValueSet($value): bool
{
    return $value !== [];
}

function getBefore($node)
{
    return $node['before'];
}

function getAfter($node)
{
    return $node['after'];
}

function getNoChanges($node)
{
    return $node['noChanges'];
}
