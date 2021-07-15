<?php

declare(strict_types=1);

namespace Differ\Formatter;

use Exception;

use function Differ\Formatter\Stylish\genStylish;
use function Differ\Formatter\Plain\genPlain;
use function Differ\Formatter\Json\genJson;

function format(array $file, string $formatName): string
{
    if ($formatName === 'stylish') {
        return genStylish($file);
    } elseif ($formatName === 'plain') {
        return genPlain($file);
    } elseif ($formatName === 'json') {
        return genJson($file);
    } else {
        throw new Exception('Undefined format name');
    }
}
