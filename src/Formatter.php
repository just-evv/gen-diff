<?php

declare(strict_types=1);

namespace Differ\Formatter;

use Exception;

use function Differ\Formatter\Stylish\genStylish;
use function Differ\Formatter\Plain\genPlain;
use function Differ\Formatter\Json\genJson;

/**
 * @throws Exception
 */
function format(array $tree, string $formatName): string
{
    return match ($formatName) {
        'stylish' => genStylish($tree),
        'plain' => genPlain($tree),
        'json' => genJson($tree),
        default => throw new Exception('Undefined format name'),
    };
}
