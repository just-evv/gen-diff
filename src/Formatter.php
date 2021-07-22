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
function format(array $file, string $formatName): string
{
    return match ($formatName) {
        'stylish' => genStylish($file),
        'plain' => genPlain($file),
        'json' => genJson($file),
        default => throw new Exception('Undefined format name'),
    };
}
