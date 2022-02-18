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

function stringify($data, $depth)
{
    $result = ['{'];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $value = stringify($value, $depth + 1);
        }
        $result[] = getSameRow($key, $value, $depth);
    }
    $result[] = str_repeat(INDENT, $depth - 1) . "}";

    return implode(PHP_EOL, $result);
}

function getAddRow($key, $value, $depth = 1)
{
    return str_repeat(INDENT, $depth - 1) . ADD_ROW . "{$key}: {$value}";
}

function getRemoveRow($key, $value, $depth = 1)
{
    return str_repeat(INDENT, $depth - 1) . REMOVE_ROW . "{$key}: {$value}";
}

function getSameRow($key, $value, $depth = 1)
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
                    ? stringify(json_decode($valBefore, true), $depth + 1)
                    : toString(json_decode($valBefore, true));
            }
            if (!is_null($valAfter)) {
                $valAfter = is_array(json_decode($valAfter, true))
                    ? stringify(json_decode($valAfter, true), $depth + 1)
                    : toString(json_decode($valAfter, true));
            }
            if ($valBefore === $valAfter) {
                $result[] = getSameRow($key, $valBefore, $depth);
            } elseif (is_null($valBefore)) {
                $result[] = getAddRow($key, $valAfter, $depth);
            } elseif (is_null($valAfter)) {
                $result[] = getRemoveRow($key, $valBefore, $depth);
            } else {
                $result[] = getRemoveRow($key, $valBefore, $depth);
                $result[] = getAddRow($key, $valAfter, $depth);
            }
        } else {
            $nestedFormat = iter($value, $depth + 1);
            $result[] = str_repeat(INDENT, $depth) . "{$key}: {";
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
