<?php

declare(strict_types=1);

namespace Gendiff\Formatter;

use function Gendiff\Formatter\Stylish\stylish;
use function Gendiff\Formatter\Plain\plain;
use function Gendiff\Formatter\Json\json;

function formatter(array $file, string $formatName): string
{
    if ($formatName === 'plain') {
        return plain($file);
    } elseif ($formatName === 'json') {
        return json($file);
    }else {
        return stylish($file);
    }
}
