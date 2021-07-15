<?php

declare(strict_types=1);

namespace Gendiff\Formatter;

use Exception;

use function Gendiff\Formatter\Stylish\genStylish;
use function Gendiff\Formatter\Plain\genPlain;
use function Gendiff\Formatter\Json\genJson;

function formatter(array $file, string $formatName): string
{
    if ($formatName === '' || $formatName === 'stylish') {
        return genStylish($file);
    } elseif ($formatName === 'plain') {
        return genPlain($file);
    } elseif ($formatName === 'json') {
        return genJson($file);
    } else {
        throw new Exception('Undefined format name');
    }
}
