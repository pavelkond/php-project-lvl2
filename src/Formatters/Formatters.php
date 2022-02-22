<?php

namespace Differ\Formatters\Formatters;

use function Differ\Formatters\Stylish\formatStylish;
use function Differ\Formatters\Plain\formatPlain;

function format(array $data, string $formatType)
{
    switch ($formatType) {
        case 'stylish':
            return formatStylish($data);
        case 'plain':
            return formatPlain($data);
        default:
            return false;
    }
}
