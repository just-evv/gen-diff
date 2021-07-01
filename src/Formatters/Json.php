<?php

declare(strict_types=1);

namespace Gendiff\Formatter\Json;

use Exception;

function json(array $tree): string
{
    $result = json_encode($tree, JSON_FORCE_OBJECT);
    if ($result === false) {
        throw new Exception("something went wrong");
    }
    return $result;
}
