<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Plain;

use function Gendiff\CompareFiles\getAfter;
use function Gendiff\CompareFiles\getBefore;
use function Gendiff\CompareFiles\getNoChanges;

function plain($array): string
{
    return 'you chose plain';
}