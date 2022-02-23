<?php

namespace Differ\Formatters\Plain;

function stringify(mixed $value): string
{
    switch (gettype($value)) {
        case 'string':
            return "'$value'";
        case 'boolean':
            return is_bool($value) && $value ? 'true' : 'false';
        case 'NULL':
            return 'null';
        case 'array':
            return '[complex value]';
        default:
            return (string) $value;
    }
}

function getAddedRow(string $property, mixed $value): string
{
    return "Property $property was added with value: $value";
}

function getRemovedRow(string $property): string
{
    return "Property $property was removed";
}

function getUpdatedRow(string $property, string $oldValue, string $newValue): string
{
    return "Property $property was updated. From $oldValue to $newValue";
}

function formatData(array $data, string $propertyPath = ''): array
{
    return array_reduce(array_keys($data), function ($acc, $key) use ($data, $propertyPath) {
        $currentPropPath = $propertyPath === '' ? $key : "$propertyPath.$key";
        if (array_keys($data[$key]) === [0, 1]) {
            [$valBefore, $valAfter] = $data[$key];
            $valBeforeStr = stringify(json_decode($valBefore, true));
            $valAfterStr = stringify(json_decode($valAfter, true));
            if (is_null($valBefore)) {
                return [...$acc, getAddedRow(stringify($currentPropPath), $valAfterStr)];
            } elseif (is_null($valAfter)) {
                return [...$acc, getRemovedRow(stringify($currentPropPath))];
            } elseif ($valBefore !== $valAfter) {
                return [...$acc, getUpdatedRow(stringify($currentPropPath), $valBeforeStr, $valAfterStr)];
            }
        } else {
            $nestedResult = formatData($data[$key], $currentPropPath);
            return [...$acc, ...$nestedResult];
        }
        return $acc;
    }, []);
}

function formatPlain(array $data): string
{
    $formatted = formatData($data);

    return implode(PHP_EOL, $formatted);
}
