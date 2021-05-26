<?php

declare(strict_types=1);

namespace Gendiff\Formatter;

use function Gendiff\Formatter\Stylish\stylish;
use function Gendiff\Formatter\Plain\plain;


function formatter(array $file, string $formatName)
{
    if ($formatName === 'plain') {
        return plain($file);
    }
    else {
        return stylish($file);
    }
}