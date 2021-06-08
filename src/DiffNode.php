<?php

declare(strict_types=1);

namespace Gendiff\DiffNode;

function createNode(): array
{
    return ['before' => [], 'after' => [], 'noChanges' => []];
}

function setBefore($node, $value): array
{
    $node['before'] = $value;
    return $node;
}

function setAfter($node, $value): array
{
    $node['after'] = $value;
    return $node;
}

function setNoChanges($node, $value): array
{
    $node['noChanges'] = $value;
    return $node;
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
