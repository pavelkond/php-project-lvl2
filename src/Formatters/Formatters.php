<?php

namespace Differ\Formatters\Formatters;

use function Differ\Formatters\Stylish\formatStylish;
use function Differ\Formatters\Plain\formatPlain;
use function Differ\Formatters\Json\formatJson;

function format(array $data, string $formatType): string|false
{
    switch ($formatType) {
        case 'stylish':
            return formatStylish($data);
        case 'plain':
            return formatPlain($data);
        case 'json':
            return formatJson($data);
        default:
            return false;
    }
}
