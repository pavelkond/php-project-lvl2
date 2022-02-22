<?php

namespace Differ\Formatters\Stylish;

const INDENT = '    ';
const ADD_ROW = '  + ';
const REMOVE_ROW = '  - ';

function toString($value)
{
    $valueType = gettype($value);
    switch ($valueType) {
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'NULL':
            return 'null';
        default:
            return (string) $value;
    }
}

function stringifyArray($data, $depth)
{
    $result = ['{'];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $value = stringifyArray($value, $depth + 1);
        }
        $result[] = getIntendedRow($key, $value, $depth);
    }
    $result[] = str_repeat(INDENT, $depth - 1) . "}";

    return implode(PHP_EOL, $result);
}

function getAddedRow($key, $value, $depth = 1)
{
    return str_repeat(INDENT, $depth - 1) . ADD_ROW . "{$key}: {$value}";
}

function getRemovedRow($key, $value, $depth = 1)
{
    return str_repeat(INDENT, $depth - 1) . REMOVE_ROW . "{$key}: {$value}";
}

function getIntendedRow($key, $value, $depth = 1)
{
    return str_repeat(INDENT, $depth) . "{$key}: {$value}";
}

function iter(array $data, $depth = 1)
{
    $result = [];
    foreach ($data as $key => $value) {
        if (array_keys($value) === [0, 1]) {
            [$valBefore, $valAfter] = $value;
            if (!is_null($valBefore)) {
                $valBefore = is_array(json_decode($valBefore, true))
                    ? stringifyArray(json_decode($valBefore, true), $depth + 1)
                    : toString(json_decode($valBefore, true));
            }
            if (!is_null($valAfter)) {
                $valAfter = is_array(json_decode($valAfter, true))
                    ? stringifyArray(json_decode($valAfter, true), $depth + 1)
                    : toString(json_decode($valAfter, true));
            }
            if ($valBefore === $valAfter) {
                $result[] = getIntendedRow($key, $valBefore, $depth);
            } elseif (is_null($valBefore)) {
                $result[] = getAddedRow($key, $valAfter, $depth);
            } elseif (is_null($valAfter)) {
                $result[] = getRemovedRow($key, $valBefore, $depth);
            } else {
                $result[] = getRemovedRow($key, $valBefore, $depth);
                $result[] = getAddedRow($key, $valAfter, $depth);
            }
        } else {
            $nestedFormat = iter($value, $depth + 1);
            $result[] = getIntendedRow($key, '{', $depth);
            foreach ($nestedFormat as $str) {
                $result[] = $str;
            }
        }
    }
    $result[] = str_repeat(INDENT, $depth - 1) . "}";

    return $result;
}

function formatStylish(array $data): string
{
    $formatted = iter($data);
    array_unshift($formatted, '{');

    return implode(PHP_EOL, $formatted);
}
