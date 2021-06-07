<?php

declare(strict_types=1);

namespace Gendiff\DiffNode;

function createNode(): array
{
    return ['before' => [], 'after' => [], 'noChanges' => []];
}

function setBefore(&$node, $value): void
{
    $node['before'] = $value;
}

function setAfter(&$node, $value): void
{
    $node['after'] = $value;
}

function setNoChanges(&$node, $value): void
{
    $node['noChanges'] = $value;
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

function isValueSet($value): bool
{
    return $value !== [];
}
