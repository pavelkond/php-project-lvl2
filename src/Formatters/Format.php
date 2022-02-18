<?php

namespace Differ\Formatters\Format;

use function Differ\Formatters\Stylish\formatStylish;

function format(array $data, string $formatType)
{
    switch ($formatType) {
        case 'stylish':
            return formatStylish($data);
        default:
            return false;
    }
}
