<?php

namespace Differ\Formatters\Stylish;

const INDENT = '    ';
const ADD_ROW = '  + ';
const REMOVE_ROW = '  - ';

function toString(mixed $value, int $depth = 1): string
{
    $valueType = gettype($value);
    switch ($valueType) {
        case 'boolean':
            return is_bool($value) && $value ? 'true' : 'false';
        case 'NULL':
            return 'null';
        case 'array':
            return stringifyArray($value, $depth);
        default:
            return (string) $value;
    }
}

function stringifyArray(array $data, int $depth): string
{
    $result = array_reduce(array_keys($data), function ($acc, $key) use ($data, $depth) {
        $value = is_array($data[$key])
            ? stringifyArray($data[$key], $depth + 1)
            : $data[$key];
        return [...$acc, getIntendedRow($key, $value, $depth)];
    }, ['{']);

    return implode(
        PHP_EOL,
        [...$result, str_repeat(INDENT, $depth - 1) . "}"]
    );
}

function getAddedRow(string $key, mixed $value, int $depth = 1): string
{
    return str_repeat(INDENT, $depth - 1) . ADD_ROW . "$key: $value";
}

function getRemovedRow(string $key, mixed $value, int $depth = 1): string
{
    return str_repeat(INDENT, $depth - 1) . REMOVE_ROW . "$key: $value";
}

function getIntendedRow(string $key, mixed $value, int $depth = 1): string
{
    return str_repeat(INDENT, $depth) . "$key: $value";
}

function formatData(array $data, int $depth = 1)
{
    $result = array_reduce(array_keys($data), function ($acc, $key) use ($data, $depth) {
        if (array_keys($data[$key]) === [0, 1]) {
            [$valBefore, $valAfter] = $data[$key];
            $valBeforeStr = toString(json_decode($valBefore, true), $depth + 1);
            $valAfterStr = toString(json_decode($valAfter, true), $depth + 1);
            if ($valBefore !== $valAfter) {
                if (is_null($valBefore)) {
                    return [...$acc, getAddedRow($key, $valAfterStr, $depth)];
                } elseif (is_null($valAfter)) {
                    return [...$acc, getRemovedRow($key, $valBeforeStr, $depth)];
                } else {
                    return [
                        ...$acc,
                        getRemovedRow($key, $valBeforeStr, $depth),
                        getAddedRow($key, $valAfterStr, $depth)
                    ];
                }
            } else {
                return [...$acc, getIntendedRow($key, $valBeforeStr, $depth)];
            }
        } else {
            $nestedFormat = formatData($data[$key], $depth + 1);
            return [...$acc, getIntendedRow($key, '{', $depth), ...$nestedFormat];
        }
    }, []);

    return [...$result, str_repeat(INDENT, $depth - 1) . "}"];
}

function formatStylish(array $data): string
{
    $formatted = ['{', ...formatData($data)];

    return implode(PHP_EOL, $formatted);
}
